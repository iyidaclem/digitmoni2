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

class UadminController extends Controller{
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

  public function new_acc_ruleAction(){

  }

  public function update_ruleAction(){

  }

  public function user_utility_recordAction(){

  }

  public function searchAction(){

  }

  
}