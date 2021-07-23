<?php
namespace API\Controllers;

use API\Model\Fund;
use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\Middleware;
use core\Model as CoreModel;
use core\Response;
use database\DataBase;
use core\http\Middleware\IndexMiddleware;
use core\Call\Utility\TVcalls;
use core\Call\Utility\AirtimeCall;


class SpendController extends Controller {
  private $db,$model,$resp, $middleware,$airtime, $input,$indexMiddleware, $TVuser,$fh;
  
  public function __construct($controller, $action){
    parent::__construct($controller, $action);
    $this->db = new DataBase();
    $this->model = new coreModel($this->tabl);
    $this->middleware = new Middleware();
    $this->fh = new FH();
    $this->input = new Input();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->resp = new Response;
    $this->TVuser = new TVcalls();
    $this->airtime = new AirtimeCall();
  }

  public function tv_userverifyAction(){
    if(!$this->input->isPost()) return $this->resp->SendResponse(
      403, false, POST_MSG);
    if(!$this->indexMiddleware->isUser())return $this->resp->SendResponse(
      401, false, ACL_MSG);
    
    $userid = FH::sanitize($_REQUEST['userid']);
    $bill = FH::sanitize($_REQUEST['bill']);
    $cardNo = FH::sanitize($_REQUEST['card_no']);
    
    $tvRequest = $this->TVuser->validateTVUser($userid, $bill, $pass, $cardNo);
    if(!$tvRequest)return false;
    return $this->resp->SendResponse(200, true, $tvRequest);
  }

  public function ds_go_tvAction(){
    if(!$this->input->isPost()) return $this->resp->SendResponse(403, false, POST_MSG);
    if(!$this->indexMiddleware->isUser()) return $this->resp->SendResponse(401, false, ACL_MSG);

    //handle inputs 
    $userid = FH::sanitize($_REQUEST['userid']);
    $phone = FH::sanitize($_REQUEST['phone']);
    $amount = FH::sanitize($_REQUEST['amount']);
    $smartCardNo = FH::sanitize($_REQUEST['cardno']);
    $customerName = FH::sanitize($_REQUEST['customerName']);
    $invoice = FH::sanitize($_REQUEST['invoice']);
    $billType = FH::sanitize($_REQUEST['billtype']);
    $customerRef = FH::sanitize($_REQUEST['coustomer_ref']);
    //instantiate in database before making api call
    $fields =[
      'userid'=>$userid,'phone'=>$phone,
      'amount'=>$amount,'smartcardno'=>$smartCardNo,
      'customername'=>$customerName,'customerRef'=>$customerRef, 
      'invoice'=>$invoice,'billtype'=>$billType, 
    ];
    if(!$this->model->insert($fields))//HIGH PRIORITY ERROR LOG 
    return $this->resp->SendResponse(
      500, false, 'There is a problem. Try later please.'
    );
    //Make api call
    $tvSubRequest = $this->TVuser->tvSub();
    //process the outcome 

    //update the database 

    //return final message
  }

  public function startimeAction(){
    if(!$this->input->isPost()) return $this->resp->SendResponse(
      403, false,POST_MSG);
    if(!$this->indexMiddleware->isUser()) return $this->resp->SendResponse(
      401, false, ACL_MSG);
    
    //handle inputs
    $jsonData = file_get_contents('input://php');
    $data = json_decode($jsonData);
    $sanitized = FH::arraySanitize($data);
    //set up the fields
    $fields = [
      'userid'=>$sanitized['userid'], 'amount'=>$sanitized['amount'],
      'phone'=>$sanitized['phone'], 'smartcardno'=>$sanitized['smartcard']
    ];
    //insert into database before making API all.
    if(!$this->model->insert($fields)) //HIGH PRIORITY ERROR LOG
    return $this->resp->SendResponse(500, false, 'There is a problem. Please try again later.');
    //API CALL 
    $starTimeRequest = $this->TVuser->starTimeCall();
    //handle error

    //if success update database 

    //return final message
  }


  public function airtimeAction(){
    if(!$this->input->isPost()) return $this->resp->SendResponse(
      403, false, POST_MSG);
    if(!$this->indexMiddleware->isUser()) return $this->resp->SendResponse(
      401, false, ACL_MSG);
    $jsonData = file_get_contents('input://php');
    $data = json_decode($jsonData);
    $sanitized = FH::arraySanitize($data);
    //prepare fields for initialization into the database 
    $fields=[
      'userid'=>$sanitized['userid'], 'phone'=>$sanitized['phone'],
      'user_ref'=>$sanitized['user_ref'], 'amount'=>$sanitized['amount'],
      'network'=>$sanitized['network']
    ];
    //compare with amount spendable
    $fundModel = new Fund('user_fund');
    $accbal = $fundModel->UserAaccBalance($this->indexMiddleware->loggedUser());
    if($sanitized['amount']>=$accbal)return $this->resp->SendResponse(
      422, false, 'Insufficient funds.');
    //Initiate in database 
    if(!$this->model->insert($fields)) return $this->resp->SendResponse(
      500, false, 'There is a problem from our end. Please try again later.'
    );
    //API CALL
    $airtimeRequest = $this->airtime->airtimeCall();

    //handle error

    //handle success and update database

    //send final response
  }

  public function airtime_trx_statusAction(){

  }

  public function buy_dataAction(){
    if(!$this->input->isPost())return $this->resp->SendResponse(
      403, false, POST_MSG);
    if(!$this->indexMiddleware->isUser())return $this->resp->SendResponse(
      401, false, ACL_MSG);
    //handle inputs
    $jsonData = file_get_contents('input://php');
    $data = json_decode($jsonData);$sanitized = FH::arraySanitize($data);
    //set fields 
    $fields=[
      'userid'=>$sanitized['userid'],
      'network'=>$sanitized['network'],
      'phone'=>$sanitized['phone'],
      'amount'=>$sanitized['amount'],
      ''
    ];
  }

  public function data_offersAction(){
    
  }

  public function electricityAction(){

  }

  public function dataAction(){

  }

  public function insuranceAction(){
    
  }
}