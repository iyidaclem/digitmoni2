<?php 
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\IndexMiddleware;
use core\Model as CoreModel;
use Response;
use database\DataBase;

class Manage extends Controller{
  private $input;
  private $model;
  private $db;
  private $middleware;
  
  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('user_fund');
    $this->db = new DataBase();
    $this->middleware = new IndexMiddleware();
  }

  public function forgot_passwordAction(){
    
  }

  public function resetpassword(){

  }  


}