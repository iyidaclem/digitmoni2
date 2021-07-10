<?php 
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\Middleware;
use core\Model as CoreModel;
use core\Response as CoreResponse;
use database\DataBase;

class DefaultController extends Controller{
  private $input;
  private $model;
  private $db;
  private $middleware;
  private $indexMiddleware;
  private $response;
  
  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('user_fund');
    $this->db = new DataBase();
    $this->middleware = new Middleware();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->response = new CoreResponse();
  }

  public function gen_referenceAction(){
    
  }

}