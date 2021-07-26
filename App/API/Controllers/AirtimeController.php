<?php 
namespace API\Controllers;

use API\Model\Fund;
use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\IndexMiddleware;
use core\Model as coreModel;
use core\Response;
use database\DataBase;
use core\http\Middleware\Middleware;
use core\Call\Utility\AirtimeCall;

class AirtimeController extends Controller{
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
    $this->airtime = new AirtimeCall();
  }

  public function airtime_offerAction(){
    if(!$this->input->isGet())return $this->resp->SendResponse(
      403, false, GET_MSG);
    if(!$this->indexMiddleware->isUser) return $this->resp->SendResponse(
      402, false, ACL_MSG);
    //API call 
    $airtimeOfferRequest = $this->airtime->airtimeOfferRequest();
    if(!$airtimeOfferRequest) return $this->resp->SendResponse(
      200, true, 'Network not available.');
    return $this->resp->SendResponse(200, true, 'Network available.', $airtimeOfferRequest);
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
    if(!$this->input->isGet())return $this->resp->SendResponse(
      403, false, GET_MSG);
    if(!$this->indexMiddleware->isUser) return $this->resp->SendResponse(
      402, false, ACL_MSG);
    //make API call 
    

  }

}