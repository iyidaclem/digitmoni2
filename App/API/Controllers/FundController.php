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

class FundController extends Controller{
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

  public function checkbalAction(){
    //check request method 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );
    //check access level 
    if($this->indexMiddleware->isUser()) return $this->response->SendResponse(
      401, false, ACL_MSG
    );
   $loggedInUser = 'Vince';
    $fund = $this->model->findByUsername($this->model->_table, $loggedInUser);
    $balance = $fund->balance;
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

  public function fundAction($reference){
    $amount = $_REQUEST['amount'];
    $loggedInUser = $this->middleware->loggedUser();
    $fields = [
      "balance"=>$amount,
    ];
    $fundAccount = $this->model->update($this->model->_table, $fields);

  }

  public function withdrawAction($amount){
    $loggedInUser = $this->middleware->loggedUser();
    /*check if the user have up to the amount and if not send decline response*/

    //send transfer request to paystack. 

    //if it fails, send fail response

    //if it succeeds, updtate the database
    $fields = [
      "balance"=>$amount,
    ];
    $withrwal = $this->model->update($this->model->_table, $fields);
  }


}