<?php
namespace API\Controllers;


use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\IndexMiddleware;
use core\http\Middleware\Middleware;
use core\Model as CoreModel;
//use Response;
use database\DataBase;
use core\Response;
use test\MiddlewareTest;

class Inv_adminController extends Controller{
  private $input, $model, $db, $user, $response, $middleware, $table;
  private $indexMiddleware;

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel($this->table);
    $this->db = new DataBase();
    $this->user = new Users();
    $this->response = new Response();
    $this->middleware = new Middleware();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];

  }
/*
===================================================
*/ 
  public function newpackageAction(){
    if(!$this->input->isPost())return $this->response->SendResponse(
      400, false, 'only POST request is allowed'
    );
    if(!$this->indexMiddleware->isSuperAdmin() && !$this->indexMiddleware->isInvAdmin())
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
    //		

    $fields=[
      'option_name'=>$sanitized['option_name'],
      'state'=>$sanitized['state'],
      'flex_int'=>$sanitized['flex_int'],
      'fixed_int'=>$sanitized['fixed_int'],
      'min_amt'=>$sanitized['min_amt'],
      'max_amt'=>$sanitized['max_amt'],
      'min_duration'=>$sanitized['min_duration'],
      'cancel_cost'=>$sanitized['cancel_cost'],
      'description'=>'dummy description'      
    ];

    $model = new CoreModel('investments');
    if(!$model->insert($fields)) return $this->response->SendResponse(
      400, false, 'Failed to create a damn investment package.'
    );
    //LOG ACTION
    
    //get the newly created option
    $newInvestmentPack = $model->findFirst([
      'conditions' => 'option_name = ?','bind' => [$sanitized['option_name']]]);
    //send a message
    return $this->response->SendResponse(
      200, true, 'Investment package successfully created.', true, $newInvestmentPack);
  }
/*
===================================================s
This next action when called suspends the investment package so 
users can no longer enrol for it
*/ 

  public function package_statusAction($packageID, $newStatus){
    //making sure the incoming request is a POST request
    if(!$this->input->isPost()) return $this->response->SendResponse(
      401, false, POST_MSG
    );
    //making sure the request is either from super admin or inv admin
    if(!$this->indexMiddleware->isSuperAdmin() && !$this->indexMiddleware->isInvAdmin())
    return $this->response->SendResponse(
      400, false, ACL_MSG
    );
    //Now supspend a pakackage with the given ID or namae. 
    $fields= [
      'state'=>$newStatus
    ];

    //update package in Database
    $model = new CoreModel('investments');
    if(!$model->update($packageID, $fields)) 
    return $this->response->SendResponse(
      401, false, 'Failed to change Investment package status.'
    );
    $updated = $model->findFirst([
      'conditions' => 'id = ?','bind' => [$packageID]]);
  
    //send return success message 
    return $this->response->SendResponse(
      200, true, 'Investment package status successfully updated.', true, $updated
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
    if($this->indexMiddleware->isInvAdmin() && !$this->indexMiddleware->isSuperAdmin())
    return $this->response->SendResponse(
      401, false, ACL_MSG
    );
    
    //Now query the database and fetch all packages and return;
    $model = new CoreModel('investments');
    $allPackageRunning = $model->find([
      'conditions' => 'state = ?',
      'bind' => ['running']
    ]);
    
    //Now query to get all disabled package
    $allPackageDisable = $model->find([
      'conditions' => 'state = ?',
      'bind' => ['disable']
    ]);
   
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
public function edit_ruleAction($packageID){

}


}