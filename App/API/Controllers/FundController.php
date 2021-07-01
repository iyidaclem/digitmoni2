<?php 
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\Middleware;
use core\Model as CoreModel;
use Response;
use database\DataBase;

class FundController extends Controller{
  private $input;
  private $model;
  private $db;
  private $middleware;
  
  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('users');
    $this->db = new DataBase();
    $this->middleware = new Middleware();
  }

  public function indexAction(){
    $loggedInUser = $this->middleware->loggedUser();
    
  }

  public function fundAction(){

  }

  public function withdrawAction(){

  }


}