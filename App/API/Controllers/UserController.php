<?php 
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\IndexMiddleware;
use core\http\Middleware\Middleware;
use core\Model as CoreModel;
//use Response;
use database\DataBase;
use core\Response;
use test\MiddlewareTest;

class UserController extends Controller{
  private $input, $model, $db, $user, $response, $middleware, $indexMiddleware;


  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('users');
    $this->db = new DataBase();
    $this->user = new Users();
    $this->response = new Response();
    $this->middleware = new Middleware();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];

  }


  /**
   * This endpoint(GET REQUEST) ...app/user/profile{username=null} when called returns a user's profile info. 
   * 
   * @param mixed $username=null it takes a username or nothing. You can use it with a username to add feature 
   * for admin wanting to view a users profile. No username supplied for when a user wants to view his or her 
   * own profile. 
   * 
   * POSSIBLE RESPONSES 
   * 
   * 1. Returns 404 with a 'problem' message.
   * 
   * 2. Returns profile data in json format.
   * 
   * @return [type]
   */
  public function profileAction($username=null){
    if(!$this->input->isGet()) return $this->response->SendResponse(
      400, false, POST_MSG
    );
   
    if(!$this->indexMiddleware->isUser())return $this->response->SendResponse(
      400, false, ACL_MSG
    );
    $username==null?$_username = $this->indexMiddleware->loggedUser():$_username=$username;
    
    //var_dump($_username); die();
    $fetchProfile = $this->model->findByUsername('users', $_username); 
    
    if(!$fetchProfile)return $this->response->SendResponse(
      404, false, 'There is a problem fetching your profile data.'
    );
    $acl = unserialize($fetchProfile->acl); 
    $fetchProfile->acl = $acl;
    return $this->response->SendResponse(200, false, null, true, $fetchProfile);
  }



  /**
   * The same thing that happened in fetching profile data is happening here except that this 
   * time around we use userID. You dont need to supply ID to this endpoint when using it for a user 
   * updating their profile. 
   * 
   * to call this endpoint, make a PUT REQUEST to ...app/user/update/{userID=null}
   * 
   * @param mixed $targetID=null
   * 
   * POSSIBLE RESPONSES 
   * 
   * 1. 500 with a "failed to update" message.
   * 
   * 2. 200 with success message.
   */
  public function updateAction($targetID=null){
    if(!$this->input->isPut()) return $this->response->SendResponse(
      401, false, 'Wrong request method.'
    );
    
    //$this->indexMiddleware->dump();
    if(!$this->indexMiddleware->isUser()) return $this->response->SendResponse(
      401, false, ACL_MSG
    );

    //handling the request data
    $putData = file_get_contents('php://input');
    $data = json_decode($putData);
  
    //Sanitizing all the input values
    $sanitized = [];
    $msg =[];
    foreach($data as $k => $v){
      if($k!='acl'){
        $pureVals = FH::sanitize($v);
      $sanitized[$k] = $pureVals;
      }
    }
    $acl = serialize($data->acl);
    //creating input fields array
    $fields=[
      'first_name'=>$sanitized['first_name'],
      'lastname'=>$sanitized['lastname'],
      'username'=>$sanitized['username'],
      'email'=>$sanitized['email'],
      'pword'=>$sanitized['pword'],
      'created_at'=>$sanitized['created_at'],
      'state'=>$sanitized['state'],
      'addres'=>$sanitized['addres'],
      'phone'=>$sanitized['phone'], 
      'acl'=>$acl,
      'entry_code'=>$sanitized['entry_code'],
      'ref_code'=>$sanitized['ref_code'],
      'acc_type'=>$sanitized['acc_type'],
      'activity'=>$sanitized['activity']
      ];
    //deciding which id to use 
    $loggedUserID = $this->indexMiddleware->loggedUserID();
    $targetID==null?$opsID = $loggedUserID:$opsID = $targetID;
     //now updating the database
    If(!$this->model->update($opsID, $fields)) //LOGG SOMETHING
    return $this->response->SendResponse(500, false, 'Sorry, there is a problem from our end. Our engineers are working on it.');
    //fetch updated details for display.
    $details = $this->model->findFirst([
      'conditions' => 'id = ?','bind' => [$opsID]]);
    $acl = unserialize($details->acl);
    $details->acl = $acl;

    return $this->response->SendResponse(200, true, '', false, $details);

  }




  /**
   * This is the endpoint for logging out of the application. To call this endpoint
   * make a GET REQUEST to ...ap/user/logout. 
   * 
   * POSSIBLE RESPONSES 
   * 
   * 1. Returns 401 with "logged out already" message.
   * 
   * 2. Returns 200 with a success message.
   * 
   * @return [type]
   */
  public function logoutAction(){
    if(!$this->input->isGet()) return $this->response->SendResponse(
      403, false, GET_MSG
    );
    if(!$this->indexMiddleware->isUser()) return $this->response->SendResponse(
      401, false, ACL_MSG
    );   
    //checking if the user is logged in 
    $loggedUsername = $this->indexMiddleware->loggedUser();
    $checkLogged = $this->model->findByUsername('session_tb', $loggedUsername);
   // var_dump($checkLogged);die();
    if(!$checkLogged)return $this->response->SendResponse(
      401, false, 'You are logged out already.'
    );

    $db = new DataBase();
    $logOut = $db->delete('session_tb', $checkLogged->id);

    if($logOut) return $this->response->SendResponse(200, false, 'You have successfully loggged out.');
  }
}