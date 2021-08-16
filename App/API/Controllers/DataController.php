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
use core\Call\Utility\
use core\http\Middleware\IndexMiddleware;
use core\Call\Utility\Datacall;
use core\Helper\Help;

class DataController extends Controller{
  private $db,$help, $model,$resp, $resp,$middleware,$airtime, $input,$indexMiddleware, $TVuser,$fh;
  
  public function __construct($controller, $action){
    parent::__construct($controller, $action);
    $this->db = new DataBase();
    $this->model = new coreModel($this->tabl);
    $this->middleware = new Middleware();
    $this->fh = new FH();
    $this->input = new Input();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->resp = new Response;
    $this->help = new Help();
    $this->datacall = new Datacall();   
    $this->resp = new Response();
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
      'phone'=>$sanitized['phone'],
      'amount'=>$sanitized['amount'],
      'ref'=>$ref,
      'status'=>'initiated'
    ];
    //
  }

  
  /**
   * To call this endpoint by sending a GET request to .../data/data_offer/{network}
   * This Endpoint will return available data offers for the specified network. 
   * 
   * 
   * 
   * @param mixed $network
   * 
   * @return [type]
   */

  public function data_offersAction($network){
    if(!$this->input->isGet())return $this->resp->SendResponse(
      403, false, POST_MSG);
    if(!$this->indexMiddleware->isUser())return $this->resp->SendResponse(
      401, false, ACL_MSG);
    //api call 
    $availableData = $this->datac
    //$this->resp->SendResponse();
  }


}