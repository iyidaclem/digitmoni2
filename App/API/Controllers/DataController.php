<?php
namespace API\Controllers;

use API\Model\Fund;
use core\Controller;
use core\Input;
use core\FH;
use core\http\Middleware\Middleware;
use API\Model\DataModel;
use core\Response;
use core\Call\Utility\Datacall;
use core\Helper\Help;

class DataController extends Controller{
  private $help, $dataModel,$resp,$middleware,$datacall, $input,$indexMiddleware, $TVuser,$fh;
  
  public function __construct($controller, $action){
    parent::__construct($controller, $action);
    $this->middleware = new Middleware();
    $this->fh = new FH();
    $this->input = new Input();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->resp = new Response;
    $this->help = new Help();
    $this->datacall = new Datacall();   
    $this->dataModel = new DataModel('data');
    //$this-> 
  }

  
  /**
   * To call this API, send a GET request to ...data/buy_data 
   * 
   * This endpoint is mobile network data purchasing. The details of the data 
   * purchase will be collected via form ans submitted to this endpoint as json data format. 
   * 
   * {
   *  "phone":"recipient phone no.",
   *  "network":"mobile network choice",
   *  "amount":"amount"
   * }
   * 
   * This endpoint will return our vendor's response verbatim, you will need 
   * to review their API documentation to be able to process the response. 
   * @return [type]
   */
  public function buy_dataAction(){
    if(!$this->input->isGet())return $this->resp->SendResponse(
      403, false, POST_MSG);
    if(!$this->indexMiddleware->isUser())return $this->resp->SendResponse(
      401, false, ACL_MSG);
    //handle inputs
    $jsonData = file_get_contents('input://php');
    $data = json_decode($jsonData);$sanitized = FH::arraySanitize($data);
    $ref = $this->help->Unique_Id_Gen("D", 10);
    $username = $this->indexMiddleware->loggedUser();
    //set fields 
    $fields=[
      'username'=>$username,
      'network'=>$sanitized['network'],
      'data_pack'=>$sanitized['pack'],
      'phone'=>$sanitized['phone'],
      'amount'=>$sanitized['amount'],
      'ref'=>$ref,
      'status'=>'initiated'
    ];
    //compare amount to account balance 
    //
    //instantiating transaction in database
    $dataBaseInstance = $this->dataModel->insert($fields);
    if(!$dataBaseInstance) //LOG ERROR
    return $this->resp->SendResponse(500, false, "There is a problem from our end. Service currently not available.");
    //make the API call
    $purchaseDataRequest = $this->datacall
    ->dataTopUpCall($sanitized['amount'], $sanitized['network'], $sanitized['phone']);
    //send final response
    return $this->resp->SendResponse(200, false, '', false, $purchaseDataRequest);
  }

  
  /**
   * To call this endpoint by sending a GET request to .../data/data_offer/{network}
   * This Endpoint will return available data offers for the specified network. 
   * 
   * 
   * 
   * @param mixed $network - mtn, airtel, glo
   * 
   * @return [type]
   */

  public function data_offersAction($network){
    if(!$this->input->isGet())return $this->resp->SendResponse(
      403, false, POST_MSG);
    if(!$this->indexMiddleware->isUser())return $this->resp->SendResponse(
      401, false, ACL_MSG);
    //api call 
    $availableData = $this->datacall->dataOptionsCall($network);
    // send response
    return $this->resp->SendResponse(200,true, '', false, $availableData);
  }


}