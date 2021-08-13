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
use core\Call\Utility\Datacall;

class DataController extends Controller{
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
    $this->datacall = new Datacall();   
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

  
  /**
   * To get a list 
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
    
  }


}