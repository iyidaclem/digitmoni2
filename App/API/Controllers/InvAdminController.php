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

use function PHPUnit\Framework\returnSelf;

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
    return $this->response->SendResponse(
      200, true, 'Investment package successfully created.');
  }
/*
===================================================
This next action when called suspends the investment package so 
users can no longer enrol for it
*/ 

  public function suspendAction(){

  }
/*
===================================================
This nex action will return a list of invest packages we offer
*/ 
  public function allAction(){
    //making sure it is a get request 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );
    //making sure the action is being performed by Inv Admin or superAdmin
    if($this->middleware->isInvAdmin() && !$this->middleware->isSuperAdmin())
    return $this->response->SendResponse(
      401, false, ACL_MSG
    );

    //Now query the database and fetch all packages and return;
    $allPackage = $this->model->find();
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