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
use core\Helper\Help;
use core\logger\Logger;

class AirtimeController extends Controller{
  private $db,$model,$resp,$logger, $middleware,$airtime, $input,$indexMiddleware,$fh, $help;

  public function __construct($controller, $action){
    parent::__construct($controller, $action);
    $this->db = new DataBase();
    $this->model = new coreModel('airtimes');
    $this->middleware = new Middleware();
    $this->fh = new FH();
    $this->input = new Input();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->resp = new Response;
    $this->airtime = new AirtimeCall();
    $this->help = new Help();
    $this->logger = new Logger();
  }

  /**
   * Summary: this function or controller action handles purchase
   * of airtime. It will take in json data from user input in the 
   * following format: 
   * {
   *  "network":"MTN",
   *  "phone":"080xxxxxxx",
   *  "amount":"200",
   * }
   * 
   * @return json 
   */
  public function airtimeAction(){
    if(!$this->input->isPost()) return $this->resp->SendResponse(
      403, false, POST_MSG);
    if(!$this->indexMiddleware->isUser()) return $this->resp->SendResponse(
      401, false, ACL_MSG);
    $jsonData = file_get_contents('input://php');
    $data = json_decode($jsonData);
    $sanitized = FH::arraySanitize($data);
    //generate unique reference 
    $ref = $this->middleware->generateRandomString(12);
    //getting date time
    $dateTime = $this->help->dateTimeNow();
    //prepare fields for initialization into the database 
    $fields=[
      'username'=>$this->indexMiddleware->loggedUser(),
      'network'=>$sanitized['network'], 'phone'=>$sanitized['phone'],
      'amount'=>$sanitized['amount'],'user_reference'=>$ref,
      'date_time'=>$dateTime, 'status'=>'initiated'
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
    //get the last insert ID  
    $lastInsertID = $this->model->lastIDinserted();
    //API CALL
    $airtimeRequest = $this->airtime->airtimeTopUpCall($sanitized['network'], $sanitized['phone'], $sanitized['amount'], $ref);
  }

  /**
   * After the purchase airtime action have been called, the frontend
   * will process its response and decide whether the airtime purchase 
   * went through or not. 
   * 
   * From that update the database accordingly about the transaction status, 
   * by calling this endpoint with transaction status as query parameter.
   * 
   * @param string $status: this parameter will be derived from response gotten from the
   * reponse of the previos call to purchase airtime. 
   * @param int $updateID will also be returned by the purchase airtime call. It is the ID of that
   * transaction in our own DB. 
   * You need it to update transaction status so append as query param thus: 
   * ...complete/{$status}/{$updateID}
   */ 

  public function completeAction($status, $updateID){
    if(!$this->input->isGet())return $this->resp->SendResponse(
      403, false, GET_MSG);
    if(!$this->indexMiddleware->isUser) return $this->resp->SendResponse(
      402, false, ACL_MSG);
    //define  a few variables 
    $loggedUserName = $this->indexMiddleware->loggedUser();
    //update the database
    $airtimeField = ['status'=>$status];
    $updateAirtimePurchaseStatus = $this->model->update($updateID, $airtimeField);

    if(!$updateAirtimePurchaseStatus) $this->logger->log(
      $loggedUserName, 'Failed to update his airtime transaction to '.$status, 'failed', 'page', 'high', 'user_agent');
  }
  
 

}