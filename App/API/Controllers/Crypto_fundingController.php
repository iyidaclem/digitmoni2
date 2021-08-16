<?php
namespace API\Controllers;

use API\Model\Fund;
use core\Controller;
use core\Input;
use core\FH;
use core\http\Middleware\Middleware;
use API\Model\DataModel;
use core\Response;
use core\http\Middleware\IndexMiddleware;
use core\Call\Utility\Datacall;
use core\Helper\Help;

class Crypto_fundingController extends Controller{
  private $help, $dataModel,$resp,$middleware,$datacall, $input,$indexMiddleware, $TVuser,$fh;
  
  public function __construct($controller, $action){
    parent::__construct($controller, $action);
    $this->middleware = new Middleware();
    $this->fh = new FH();
    $this->input = new Input();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->help = new Help(); 
    $this->resp = new Response();
    $this->dataModel = new DataModel('');
    //$this-> 
  }

  public function fundAction(){

  }

  public function withdrawAction(){

  }

}