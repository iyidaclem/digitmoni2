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



}