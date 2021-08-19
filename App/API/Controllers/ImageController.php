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

class ImageController extends Controller{
  private $help,$resp,$middleware,$imageStore, $input,$indexMiddleware, $TVuser,$fh, $imageModel;
  
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

  public function deleteAction(){
    if(!$this->input->isDelete()) return $this->resp->SendResponse(
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
    $targetImage = $this->imageStore . ds . '/' . ds . $filename;
    if(file_exists($targetImage)) {
      if(!unlink($targetImage)) //LOG ERROR MESSAGE FOR DEVELOPER
       return $this->resp->SendResponse(400, false, 'Failed to delete image.');
    }
    //sending final message 
    return $this->resp->SendResponse(200, true, "Successfully deleted.");
  }

  public function get_imageAction(){
    //request validation

    //access level check

    
  }

}