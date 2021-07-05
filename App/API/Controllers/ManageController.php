<?php 
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\IndexMiddleware;
use core\Model as CoreModel;
use core\Response;
use database\DataBase;

class Manage extends Controller{
  private $input;
  private $model;
  private $db;
  private $middleware;
  private $response;
  
  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('user_fund');
    $this->db = new DataBase();
    $this->response = new Response();
    $this->middleware = new IndexMiddleware();
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

    //send new password 

    //send a return message
  }

  public function resetpassword(){
    
  }  


}