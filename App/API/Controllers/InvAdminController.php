<?php
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\Middleware;
use core\http\Middleware\IndexMiddleware;
use core\Model as CoreModel;
//use core\Response;
use core\Response;
use database\DataBase;

class InvAdminControl extends Controller{
  private $input;
  private $model;
  private $db;
  private $middleware;
  private $response;
  private $table;

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel($this->table);
    $this->db = new DataBase();
    $this->middleware = new IndexMiddleware();
    $this->response = new Response();
  }
/*
===================================================
*/ 
  public function newpackageAction(){
    if(!$this->input->isPost())return $this->response->SendResponse(
      400, false, 'only POST request is allowed'
    );
    if(!$this->middleware->isSuperAdmin() && !$this->middleware->isInvAdmin())
    return $this->response->SendResponse(
      400, false, 'You dont have access to to perform this action.'
    );

    //Now take the input and sanitize them
    $putData = file_get_contents('php://input');
    $data = json_decode($putData);
  
    $sanitized = [];
    foreach($data as $k => $v){
      if($k!='acl'){
        $pureVals = FH::sanitize($v);
      $sanitized[$k] = $pureVals;
      }
    }
    //make assoc array with column names as keys and inputs as values
    $fields=[
      'option_name'=>$sanitized['option_name'],
      'state'=>$sanitized['state'],
      'flex_int'=>$sanitized['flex_int'],
      'fixed_int'=>$sanitized['fixed_int'],
      'min_amt'=>$sanitized['min_amt'],
      'max_amt'=>$sanitized['max_amt'],
      'min_duration'=>$sanitized['min_duration'],
      'cancel_cost'=>$sanitized['cancel_cost']       
    ];
    $this->table = 'investments';
    if(!$this->model->insert($fields)) return $this->response->SendResponse(
      400, false, 'Failed to create a damn investment package.'
    );
  }
/*
===================================================
*/ 

  public function suspendAction(){

  }
/*
===================================================
*/ 
  public function seeAction(){

  }
/*
===================================================
*/ 
  public function searchAction(){

  }

  public function cancelAction(){

  }
/*
===================================================
*/ 



}