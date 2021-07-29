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

class Pay_userController extends Controller{
  private $input, $model, $db, $resp, $indexMiddleware, $middleware;
  

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('users');
    $this->db = new DataBase();
    $this->resp = new Response();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
  }


public function testAction(){
  $interest = new Interest();
 $compoundInt= $interest->compundInterestMonthly(4,1000,8);
  return $this->resp->SendResponse(
    200, true, 'interest result', false,round($compoundInt, 2));
}

public function test2Action(){
  $interest = new Interest();
  $numOfDays = $interest->daysOfInvestment('2021-02-01', '2021-02-28');
  return $this->resp->SendResponse(200, true, 'interest result', false,$numOfDays);
}



 public function pay_oneAction(){

 } 

 public function pay_manyAction(){

 }

 public function due_investors(){

 }

 

}