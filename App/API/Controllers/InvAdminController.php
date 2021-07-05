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
use core\logger\logger;

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
      400, false, ACL_MSG
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
    //LOG ACTION

    //send a message
    return $this->response->SendResponse(
      200, true, 'Investment package successfully created.');
  }
/*
===================================================
This next action when called suspends the investment package so 
users can no longer enrol for it
*/ 

  public function package_statusAction($packageID, $newStatus){
    //making sure the incoming request is a POST request
    if(!$this->input->isPost()) return $this->response->SendResponse(
      401, false, POST_MSG
    );
    //making sure the request is either from super admin or inv admin
    if(!$this->middleware->isSuperAdmin() && !$this->middleware->isInvAdmin())
    return $this->response->SendResponse(
      400, false, ACL_MSG
    );
    //Now supspend a pakackage with the given ID or namae. 
    $fields= [
      'status'=>$newStatus
    ];
    //update package in Database
    $this->table = 'investments';
    if($updateStatus = $this->model->update($packageID, $fields = false)) 
    return $this->response->SendResponse(
      401, false, 'Failed to change Investment package status.'
    );

    //send return success message 
    return $this->response->SendResponse(
      200, true, 'Investment package status successfully updated.'
    );

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
    $this->table = 'investments';
    $allPackageRunning = $this->model->find([
      'conditions' => 'state = ?','bind' => ['running']]);
    //Now query to get all disabled package
    $allPackageDisable = $this->model->find([
      'conditions' => 'state = ?','bind' => ['disabled']]);
    $allPackage = [];
    $allPackage['running'] = $allPackageRunning;
    $allPackage['disabled'] = $allPackageDisable;
    //sending back response
      return $this->response->SendResponse(200, true, '', true, $allPackage);
  }
/*
===================================================
*/ 
  public function searchAction(){

  }

 
/*
===================================================
*/ 



}