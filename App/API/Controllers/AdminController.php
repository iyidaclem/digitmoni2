<?php
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\Middleware;
use core\http\Middleware\IndexMiddleware;
use core\Model as CoreModel;
//use core\Response;
use core\Response;
use database\DataBase;

/**
 * This is the SUPER ADMIN controller, majority if not all super admin features and capabilities 
 * reside here. Only one feature here can be accessed by for other admins- maintainance announcemnt. 
 * 
 * 
 */
class AdminController extends Controller{
  private $input;
  private $model;
  private $db;
  private $middleware;
  private $indexMiddleware;
  private $response;

  /**
   * @param string $controller
   * @param string $action
   */
  public function __construct(string $controller, string $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('users');
    $this->db = new DataBase();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->response = new Response();
  }

  /**
   * This endpoint handles the case of appointing a new admin. You will need a list of users to select your user or go 
   * on the particular users profile to capture their username and also pick the role you want to asign to them. Again you 
   * will need a drop down holding list of all roles. 
   * 
   * To call this endpoint, you make a POST REQUEST to ...api/admin/appoint/username/role 
   * 
   * @param mixed $username
   * @param mixed $role
   * 
   * POSSIBLE RESPONSES
   * 1. Returns 400 and a failure message.
   * 
   * 2. Returns 200 and success message.
   * 
   */
  public function appointAction($username,$role){
    //checking for request method.
    if($this->input->isPost()) return $this->response->SendResponse(
      403,false, 'Only POST requests are allowed'
    );
    //making sure that the user is the super admin
    if(!$this->indexMiddleware->isSuperAdmin()) return $this->response->SendResponse(
      401,false, 'You cant perform this action.'
    );
    //query the database to get detailse of the incoming admin using the username
    $incomingAdminData = $this->model->findByUsername('users', $username);
    //get his ACL as array 
    $incomingAdminACL = unserialize($incomingAdminData->acl);

    array_push($incomingAdminACL, $role);;
   
    $updatedACL = serialize($incomingAdminACL);
    
    // //update 
    $fields=[
     'acl'=>$updatedACL
    ];
    $appoint = $this->model->update($incomingAdminData->id, $fields);
    if(!$appoint) 
    return $this->response->SendResponse(400, false, "Failed to make this dude an admin for some reasons.");
    //returning success message with new admin detail
    $appointed = $this->model->findByUsername('users', $username);
    $acl = unserialize($appointed->acl);
    $appointed->acl =$acl;
    return $this->response->SendResponse(200, false, 'New admin appointed.',false,$appointed);

  }

  /**
   * This endpoint is called to provide a list of all admins. To call it, make a GET Request to 
   * ...api/admin/admin_list. 
   * 
   * POSSIBLE RESPONSES
   * 1. Returns 400 incase of no admins to list out or something went wrong with database.
   * 
   * 2. Returns 200 with a success message when successful.
   */
  public function admin_listAction(){
    //check request type 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );
    //check acl
    if(!$this->indexMiddleware->isSuperAdmin()) return $this->response->SendResponse(
      401, false, ACL_MSG
    );
    //query database 
    $model = new CoreModel('users');
    $admins = $model->find([ 'conditions' => 'admin = ?','bind' => ['yes']]);
    if(!$admins) 
    return $this->response->SendResponse(
      400, false, 'Either there are no admin or something went wrong.'
    );
    //send results with success response
    return $this->response->SendResponse(
      200, true, 'List of Admins', true, $admins
    );
  }

  /**
   * This endpoint when called with the two needed parameters revokes a users role. To call this endpoint
   * make a call to ...api/admin/revoke/username/user_role. 
   * 
   * @param string $username this is the username of the user whose role is to be rovoked.
   * @param string $role this is the particular role of the user that
   * 
   * POSSIBLE RESPONSES 
   * 1. Returns 400 with a "failed to update" message. This is the case when the role revoke fail.
   * 
   * 2. Returns 200 with a success message.
   */
  public function revokeAction($username,$role){
    if($this->input->isPost()) return $this->response->SendResponse(
      401,false, 'Only POST requests are allowed'
    );
    //making sure that the user is the super admin
    if(!$this->indexMiddleware->isSuperAdmin()) return $this->response->SendResponse(
      401,false, 'You cant perform this action.'
    );
    //query the database to fetch the details of the outgoing admin
    $outgoingAdminData = $this->model->findByUsername('users', $username);
    //
    $outgoingAdminACL = unserialize($outgoingAdminData->acl);
    
    if (($key = array_search($role, $outgoingAdminACL)) !== false) unset($outgoingAdminACL[$key]);
    
    $updatedACL = serialize(array_values($outgoingAdminACL));

    //Now update the database

    $fields=[
      'acl'=>$updatedACL
    ];
    if(!$this->model->update($outgoingAdminData->id, $fields)) 
    return $this->response->SendResponse(
      400, false, "Failed to make this get this dude out as an admin for some reasons."
    );
    
    //fetch deposed admin
    $deposed = $this->model->findByUsername('users', $username);
    $acl = unserialize($deposed->acl);
    $deposed->acl =$acl;
    //returning success message
    return $this->response->SendResponse(200, false, "This dude's admin role has been revoked", true, $deposed);

  }


  
  /**
   * This endpoint is for changing users password by the admin. To call this end point, 
   * make a POST call to: ...api/admin/change_user_password/{username}
   * 
   * You will supply form data in json format as follows:
   *  {
   *    "password":"existing password",
   *     "new_password":"new password",
   *     "confirm_password":"re-enter new password"
   *  }
   * 
   * @param mixed $targetUser this is the target username. 
   * 
   * @return [type] ALL POSSIBLE RESPONSES
   * 
   * 1. Returns 400 with "password mismatch message". 
   * 
   * 2. Returns 400 with "incorrect password" message. That is when the original password 
   * inserted by the user is not correct.
   * 
   * 3. Returns 400 with "failed to change password" message. That is when the change is not successful.
   * 
   * 4. Returns 200 with a success message.
   * 
   */
  public function change_user_passwordAction($targetUser){
    //check the request type 
    if(!$this->input->isPost())return $this->response->SendResponse(
      401, false, GET_MSG
    );
    //checking access token and acl
    if(!$this->indexMiddleware->isSuperAdmin()) 
    return $this->response->SendResponse(
      401, false, ACL_MSG
    );
   
    //process inputs
    $jsonData = file_get_contents('input://php');
    $data = json_decode($jsonData, true);
    //checking password with the 
    $msg =[];
    if($data->new_password !== $data->password_confirm) $msg['password_mismatch'] = 'Password mismatch.';
    return $this->response->SendResponse(
      400, false, $msg
    );
    //query database to see if the password supplied exists for the user
    $password = md5($data->password);
    $model = new CoreModel('users');
    if(!$model->findByMd5Password('users', $password)) return $this->response->SendResponse(
      400, false, "Incorrect password"
    );
    //USER REALLY SHOULD BE LOGGED OUT HERE. 

    //now change the password in database 
    $fields = [
      'password'=>$password
    ];
    $msg =[];
    if(!$model->update($this->indexMiddleware->loggedUserID, $fields)) $msg['update_msg'] = 'Update failed.';
    return $this->response->SendResponse(
      400, false, $msg
    );
    //LOG ACTION 
    
    //send success message 
    $msg['update_msg'] = 'Successfully updated.';
    return $this->response->SendResponse(200, true, $msg);

  }  


  /**
   * This endpoint is for posting site maintainance announcement. To call this endpoint
   * send a POST REQUEST to ...api/admin/maintainance submitting a form data in json
   * format as follows:
   * {
   *  "message":"message_body"
   * }
   * 
   * It either return 400 with a failure message or 200 with success message.
   * @return [type]
   */
  public function maintainanceAction(){
    //REQUEST METHOD check

    //ACL check


    //process json data
    $data = file_get_contents('input:/php');
    $jsonData = json_decode($data);
    $sanitized = FH::arraySanitize($jsonData);
    $username = $this->indexMiddleware->loggedUser();
   
    //setting up the fields
    $dateTime = ''; 
    $fields=[
      'username'=>$username,
      'display'=>'no_display',
      'message'=>$sanitized['message'],
      'date_time'=>$dateTime
    ];
    $setAnnounce = $this->model->insert($fields);

    if(!$setAnnounce) //LOGG ERROR
    return $this->response->SendResponse(400, false, 'Failed to annouce message.');
    //send proper response
    return $this->response->SendResponse(200, true, 'Announcement successful.');
  }


  /**
   * This end point is to be called after creating a new annoucement which will be hidden
   * by default. You have to call this endpoint as follows:
   * GET request to ...api/announcement_status/$dsiplay where display can be either- 
   * a. display 
   * b. no_display when you are done with maintaince and want to shut down announcement.
   * 
   * You can call this immediate on successfully setting of new announcement or you can 
   * make it a separate feature. 
   * @param mixed $display
   * 
   * @return [type] there are two possible returns
   * 1. False return: this is when there is problem updating the database with display status.
   * 2. True return: this is when the update is successful.
   * 
   * Both also returns a message that can be displayed to the user. Discretion of the front end 
   * person is advised on displaying the default message or not.
   */
  public function announcement_statusAction($display){
    //REQUEST CHECK

    //ACL CHECK

    //setting the fields
    $fields=[
      'display'=>$display
    ];
    //select last inserted ie one with highest ID
    $ID = '';
    //update the database to display or hide
    $announce = $this->model->update($ID, $fields);
    if(!$announce) //LOG ERROR
    return $this->response->SendResponse(400, false, 'Failed in making annoucement public.');
    //sending true or success response
    return $this->response->SendResponse(200, true, 'Announcement is now public.');

  }



}