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
    $this->model = new CoreModel('user_fund');
    $this->db = new DataBase();
    $this->middleware = new Middleware();
  }

  public function indexAction(){

    $loggedInUser = $this->middleware->loggedUser();
    $fund = $this->model->findByUsername($this->model->_table, $loggedInUser);
    $balance = $fund['balance'];
    return $this->jsonResponse([
      'http_status_code'=>200,
      'status'=>true,
      'message'=>"Your account balance is:",
      'data'=>[
        "balance"=>$balance,
        "account_owner"=>$loggedInUser
      ]
    ]);
  }

  public function fundAction($amount){

    $loggedInUser = $this->middleware->loggedUser();
    $fields = [
      "balance"=>$amount,
    ];
    $fundAccount = $this->model->update($this->model->_table, $fields);

  }

  public function withdrawAction($amount){
    $loggedInUser = $this->middleware->loggedUser();
    $fields = [
      "balance"=>$amount,
    ];
    $fundAccount = $this->model->update($this->model->_table, $fields);
  }


}