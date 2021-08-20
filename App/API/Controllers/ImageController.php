<?php
namespace API\Controllers;

use core\Controller;
use core\Input;
use core\FH;
use core\http\Middleware\Middleware;
use core\Response;
use core\http\Middleware\IndexMiddleware;
use API\Model\ImageModel;
use core\Helper\Help;

/** 
 * [Description ImageController]
 */
class ImageController extends Controller{
  private $help,$resp,$middleware,$imageStore, $input,$indexMiddleware, $TVuser,$fh, $imageModel;
  
  /**
   * Like every other controller in this application it takes two parameters- $controller and $action.
   * 
   * It differ from other controllers however in the sense that it have a variable whose value is 
   * path to the folder where we intend to store uploaded profile images.
   * @param mixed $controller
   * @param mixed $action
   */
  public function __construct($controller, $action){
    parent::__construct($controller, $action);
    $this->middleware = new Middleware();
    $this->fh = new FH();
    $this->input = new Input();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->help = new Help(); 
    $this->resp = new Response();
    $this->imageModel = new ImageModel('image_tb');
    $this->imageStore = ROOT . ds . 'userAsset/profileImage';
    //$this-> 
  }

  /**
   * To call this endpoint, make a POST request to ...app/image/upload with the image you want to upload. 
   * This time you will be submitting as "form-data" and not json. Your image in the front end must have 
   * the name attribute value as "photo".
   * 
   * 
   * @return [type]
   */
  public function uploadAction(){
    //request type check
    if(!$this->input->isPost()) return $this->resp->SendResponse(
      405, false, POST_MSG);
    //validating user access level
    if(!$this->indexMiddleware->isUser()) return $this->resp->SendResponse(
      403, false, ACL_MSG);
    //making sure a file is posted at all
    if(empty($_FILES)) return $this->resp->SendResponse(
      400, false, 'No file nor image is being uploaded!');
      
    $filename = $this->fh::sanitize($_FILES['photo']['name']);
    $fileSize = $this->fh::sanitize($_FILES['photo']['size']);
    $filetype = $this->fh::sanitize($_FILES['photo']['type']);
    $fileExtension = '';

    $typeArry = explode('/', $filetype);
    if(!in_array('image', $typeArry)) return $this->resp
    ->SendResponse(400, false, 'The uploaded file is not an image file');
    
    if($fileSize>2000000) return $this->resp->SendResponse(400, false, 'Image size cannot be above 2MB!');
    //CHECK IF IMAGE EXISTS IN TEMPORARY LOCATION BEFORE UPLOADING

    // $username = $this->indexMiddleware->loggedUser();
    // $userID = $this->indexMiddleware->userID();
      $username = 'Ikenna';
      $userID= 3;
    //setting fields ready
    $imageFields = [
      'userID'=>$userID,
      'username'=>$username,
      'image_name'=>$filename,
      'filesize'=>$fileSize,
    ];
 
    //first check if user have any existing profile image and if any, delete
    $imageExists = $this->imageModel->findFirst(['conditions' => 'id = ?','bind' => [$userID]]);
    if($imageExists) $this->imageModel->deleteByUsername('image_tb', $username);
   
    //finally upload the new profile image into the database
    $uploadImage = $this->imageModel->uploadImage($imageFields);
    if(!$uploadImage) //LOGG SOMETHING 
    return $this->resp->SendResponse(500, false, 'There is a problem uploading image.');
    //move image 
    echo "Image uploaded to database";
    $moveUpload =  move_uploaded_file($_FILES['photo']['tmp_name'], "$this->imageStore/$filename");
    if(!$moveUpload) return $this->resp->SendResponse(400, false, "Failed to store uploaded image.");
    return $this->resp->SendResponse(200, true, 'Image successfully uploaded.'); 
  }

  /**
   * @return [type]
   */
  public function deleteAction(){
    //verifying request type
    if(!$this->input->isGet()) return $this->resp->SendResponse(
      405, false, POST_MSG);
    //validating user access level
    if(!$this->indexMiddleware->isUser()) return $this->resp->SendResponse(
      403, false, ACL_MSG);

    //establish logged in user's identity
    $username = $this->indexMiddleware->loggedUser();
    $userID = $this->indexMiddleware->userID();

    //delete image from the database 
    $deleteImage = $this->imageModel->deleteByUsername('image_tb', $username);
    if(!$deleteImage) return $this->resp->SendResponse(
      500, false, 'Failed to delete Image.');
    //delete image form local storage
    $filename = '';
    $targetImage = $this->imageStore . ds . '/' . ds . $filename;
    if(file_exists($targetImage)) {
      if(!unlink($targetImage)) //LOG ERROR MESSAGE FOR DEVELOPER
       return $this->resp->SendResponse(400, false, 'Failed to delete image.');
    }
    //sending final message 
    return $this->resp->SendResponse(200, true, "Successfully deleted.");
  }

  /**
   * To call this endpoint, make a GET request to ...app/image/get_image. This endpoint provides 
   * the users profile picture. It doesnt need any parameter as it can assertain who the logged in user is. 
   * 
   * It returns the following parameters: 
   * imageUrl= the url for the specific users profile image.
   * image_name = the name of the users profile image.
   * image_id = the id assigned to the image by the database.
   * 
   * You need the first variable to display the image, and the last two to be stored in state for where you want 
   * to perform operations like delete or update on the image.
   * @return [type]
   */
  public function get_imageAction($username){
    //request validation

    //access level check
    if(!$this->indexMiddleware->isUser()) return $this->resp->SendResponse(401,false, ACL_MSG );
    //check if image exists for the user 
    $imageExists = $this->imageModel->findByUsername('image_tb', $username);
    if(!$imageExists) return $this->resp->SendResponse(404, false, 'No image found.');
    $filename = $imageExists->image_name;
    // check if http or https is being used in order to build up the url
    $httpOrHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
    // get the hostname / domain name for current url
		$host = $_SERVER['HTTP_HOST'];
    $ImageUrl = '';

    return $this->resp->SendResponse(200, true, '', false, $ImageUrl);
  }

}