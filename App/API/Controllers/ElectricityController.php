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
use core\Call\Utility\ElectricityCall;
use API\Model\Electricity;
use core\logger\Logger;

class ElectricityController extends Controller{
  private $electricityCall,$logger, $db,$model,$resp, $middleware,$airtime, $input,$indexMiddleware, $TVuser,$fh;

  public function __construct($controller, $action){
    parent::__construct($controller, $action);
    $this->db = new DataBase();
    $this->model = new coreModel($this->tabl);
    $this->middleware = new Middleware();
    $this->fh = new FH();
    $this->input = new Input();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->resp = new Response();
    $this->electricCall = new ElectricityCall();   
    $this->logger = new Logger();
  }

 public function available_discoAction(){
  if(!$this->input->isGet) return $this->resp->SendResponse(
    401,false, POST_MSG);
  if(!$this->indexMiddleware->isUser()) return $this->resp->SendResponse(
    403,false, ACL_MSG);
  //CALL THE API
  $availableDiscoRequest = $this->electricityCall->getAvailableDisco();
  if(!$availableDiscoRequest) return $this->resp->SendResponse(
    404, false, 'Discos not available.', false, []);
  //return correct message 
  return $this->resp->SendResponse(
   200, true, null, false, $availableDiscoRequest );
 }

 public function verify_meterAction(){
  if(!$this->input->isGet) return $this->resp->SendResponse(
    401,false, POST_MSG);
  if(!$this->indexMiddleware->isUser()) return $this->resp->SendResponse(
    403,false, ACL_MSG);
  //VALIDATE METER METER 
  $validateMeterRequest = $this->electricityCall->validateMeterCall();
  //return false message
  if(!$validateMeterRequest) return $this->resp->SendResponse(
    401, false, 'Invalid meter details.', false);
  //return correct message
  return $this->resp->SendResponse(200, true, null, false, $validateMeterRequest);
 }

 public function pay_billAction(){
  if(!$this->input->isGet) return $this->resp->SendResponse(
    401,false, POST_MSG);
  if(!$this->indexMiddleware->isUser()) return $this->resp->SendResponse(
    403,false, ACL_MSG);
  //prepare inputs
  $fields =[
    'username'=>$this->indexMiddleware->loggedUser(),
    'sys_userid'=>FH::sanitize($_REQUEST['userid']),
    'sys_user_ref'=>FH::sanitize($_REQUEST['user_ref']),
    'service'=>FH::sanitize($_REQUEST['service']),
    'meterno'=>FH::sanitize($_REQUEST['service']),
    'mtype'=>FH::sanitize($_REQUEST['mtype']),
    'amount'=>FH::sanitize($_REQUEST['amt']),
    'status'=>'initiated'
  ];
  //declaring instance of fund and electricity models
  $electricModel = new Electricity('eletricity');
  $fundModel = new Fund('user_fund');
  //retrireving and comparing account balance with amount of transaction
  $accBal = $fundModel->UserAaccBalance($this->indexMiddleware->loggedUser());
  if($accBal <= $_REQUEST['amt']) return $this->resp->SendResponse(
    422, false, 'Insufficient funds.');
  //instantiate transaction in the database before making a call 
  $initiateTrxInDatabase = $electricModel->insert($fields);
  if(!$initiateTrxInDatabase) $this->logger->log($this->indexMiddleware->loggedUser(), 
  'failed', 'initiating electricity bill payment in datatbase', null, 'extreme', 'user_agent');
  return $this->resp->SendResponse(
    500, false, 'Please there is a problem from our end. Try later.');
  //GET LAST INSERT ID
  $lastInsertID = $electricModel->lastIDinserted();
  //MAKE API CALL 
  $payElectricBillRequest = $this->electricityCall->payCall();
  if(!$payElectricBillRequest) return $this->resp->SendResponse(500, 
  false, 'Service curretly unavailable.', false, []);
  //update the database to COMPLETED transactions
  $fields = ['status'=>'completed'];
  if(!$electricModel->update($lastInsertID, $fields)) $this->logger->log($this->indexMiddleware->loggedUser(),
  'failed', 'updating electric bill payment to completed', 'eletric bill payment', 'high', 'user_agent');
  return $this->resp->SendResponse(
    201, true, 'Payment completed', false, $payElectricBillRequest);
  //log and send final message
  $this->logger->log($this->indexMiddleware->loggedUser(), 'succeeded', 'making electric bill payment',
   'electric bill page','good', 'user_agent'); 
  return $this->resp->SendResponse(200, true, null, false, $payElectricBillRequest);
 }

}