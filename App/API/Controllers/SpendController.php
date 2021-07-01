<?php
namespace API\Controllers;
use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\Middleware;
use core\Model as CoreModel;
use Response;
use database\DataBase;

class SpendController extends Controller {
  private $db;
  private $model;
  private $middleware;
  private $input;
  private $table = '';
  private $fh;
  
  public function __construct($controller, $action){
    parent::__construct($controller, $action);
    $this->db = new DataBase();
    $this->model = new coreModel($this->tabl);
    $this->middleware = new Middleware();
    $this->fh = new FH();
  }


  public function dstvAction(){

  }

  public function startimeAction(){

  }

  public function gotvAction(){

  }

  public function airtimeAction(){

  }

  public function electricityAction(){

  }

  public function dataAction(){

  }

  public function insuranceAction(){
    
  }
}