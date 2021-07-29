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
use core\compensation\Interest;
use PDO;
use PDOException;

class CompensateController extends Controller{
  private $input, $model, $db, $response, $indexMiddleware, $middleware;
  

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('users');
    $this->db = new DataBase();
    $this->response = new Response();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
  }


public function testAction(){
  $interest = new Interest();
  $interest->compundInterest(4,1000,8);
  return $this->resp->SendResponse(200, true, 'interest result', false,$interest);
}

public function test2Action(){
  $interest = new Interest();
  $interest->compundTable(4,1000,8);
  return $this->resp->SendResponse(200, true, 'interest result', false,$interest);
}



 public function pay_oneAction(){

 } 

 public function pay_manyAction(){

 }

 public function due_investors(){

 }

 

}