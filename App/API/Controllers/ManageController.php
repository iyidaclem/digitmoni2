<?php 
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\IndexMiddleware;
use core\http\Middleware\Middleware;
use core\Model as CoreModel;
use core\Response;
use database\DataBase;

class Manage extends Controller{
  private $input;
  private $model;
  private $db;
  private $indexMiddleware;
  private $middleware;
  private $response;
  
  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    // $this->model = new CoreModel('user_fund');
    $this->db = new DataBase();
    $this->response = new Response();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->middleware = new Middleware();
  }

  public function forgot_passwordAction(){
    //making sure it is a get Post request 
    if(!$this->input->isPost()) return $this->response->SendResponse(
      401, false, POST_MSG
    );
    //get input email and use it to search the database 
    $rawData = file_get_contents('input://php');
    $email =json_encode($rawData);
    $email = FH::sanitize($email);
    /*query the database to see if user exist and if he does, change his pass word*/
    $this->table = 'users';
    $UserWithEmail = $this->model->findByEmail('users', $email);
    if(!$UserWithEmail) return $this->response->SendResponse(
      400, false , 'There is no user with this email.'
    );
    //generate new password
    $newPassword = md5($this->middleware->rand6());
    $model = new CoreModel('users');
    $fields =[
      'password'=>$newPassword
    ];
    //save new password in database 
    $updatePassword = $model->update($UserWithEmail->id, $fields);
    if(!$updatePassword) return $this->response->SendResponse(
      400, false, 'Failed to create new password. Please contact admin via the contact link.'
    );
  
    //send new password as email 

    //send a return message
    $this->response->SendResponse(
      200,true, "Check your email for your new password and log in."
    );
  }

  public function change_passwordAction(){
    //check the request type 
    if(!$this->input->isPost())return $this->response->SendResponse(
      401, false, GET_MSG
    );
    //checking access token and acl
    if(!$this->indexMiddleware->isUser() && !$this->indexMiddleware->isSuperAdmin()) 
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


}