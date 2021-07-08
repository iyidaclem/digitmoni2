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

class UadminController extends Controller{
  private $input, $model, $db, $user, $response, $middleware, $indexMiddleware;


  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('users');
    $this->db = new DataBase();
    $this->user = new Users();
    $this->response = new Response();
    $this->middleware = new Middleware();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
  }

  public function new_acc_ruleAction(){
    //authenticating request
    if(!$this->input->isPost()) return $this->response->SendResponse(
      403, false, POST_MSG
    );
    //checking acl 
    if(!$this->indexMiddleware->isUAdmin() && !$this->indexMiddleware->isSuperAdmin())
    return $this->response->SendResponse(403, false, ACL_MSG);
    //process incoming new account rule. 
    $jsonData = file_get_contents('input://input');
    $data = json_decode($jsonData);
    //sanitize
    $sanitized = FH::arraySanitize($data);
    //set the fields array and insert into the database;
    $fields= [
      'acc_name'=>$sanitized['acc_name'],	
      'referral_interest'=>$sanitized['referral_interest'],	
      'purchase_disc'=>$sanitized['purchase_disc'],	
      'state'=>$sanitized['state']
    ];
    $model = new CoreModel('acc_type_rule');
    $newRule = $model->insert($fields);
    if(!$newRule) 
    //LOG ACTION- SERVER ERROR, FAILED INSERT BY U-ADMIN

    return $this->response->SendResponse(500, false, 'Failed to create new pricing rule.');
    //fetch the las insert ID and return success message
    $lastInsertedID = intval($model->lastIDinserted());
    //fetch the rule detail 
    $newRule = $model->find([
      'conditions' => 'id = ?','bind' => [$lastInsertedID]
    ]);
    //send message 
    return $this->response->SendResponse(
      200, false, '',false, $newRule);
  }

  public function update_ruleAction($ruleID){
    //check request 
    if(!$this->input->isPost()) 
    return $this->response->SendResponse(403, false, POST_MSG);
    //check acl 
    if(!$this->indexMiddleware->isUAdmin() && !$this->indexMiddleware->isSuperAdmin())
    return $this->response->SendResponse(403, false, ACL_MSG);
    //prepare inputs and set fields
    $jsonData = file_get_contents('input://php');
    $data = json_decode($jsonData);
    $sanitized = FH::arraySanitize($data);
    $fields = [
      'acc_name'=>$sanitized['acc_name'],	
      'referral_interest'=>$sanitized['referral_interest'],	
      'purchase_disc'=>$sanitized['purchase_disc'],	
      'state'=>$sanitized['state']
    ];
    //update the database 
    $model = new CoreModel('acc_type_rule');
    $ruleUpdate = $model->update($ruleID, $fields);

    if(!$ruleUpdate)
    //LOG ACTION- FAILED TO UPDATE RULE IN U-ADMIN
    return $this->response->SendResponse(
      500, false, "Failed to update pricing rule."
    );

    //LOG ACTION-- PRICE/DISCOUNT RULE UPDATED BY
    
    //send success get, bro 
    $ruleUpdated = $model->findFirst([
      'conditions' => 'id = ?','bind' => [$ruleID]
    ]);
    return $this->response->SendResponse(
      200, true, 'You successfully updated discount and price rule', false, $ruleUpdated
    );
  }


  public function promoAction($ruleID,$state){
    //check request type 
    if(!$this->input->isPost()) return $this->response->SendResponse(
      403, false, POST_MSG
    );
    //check acl 
    if(!$this->indexMiddleware->isUAdmin() && !$this->indexMiddleware->isSuperAdmin())
    return $this->response->SendResponse(403, false, ACL_MSG);
    //update database
    $fields =[
      'state'=>$state
    ];
    $model = new CoreModel('acc_type_rule');
    if(!$model->update($ruleID, $fields)) 
    //LOG ACTION- FAILED TO RESET U-ADMIN PROMO STATUS
  
    return $this->response->SendResponse(
      500, false, 'Failed to update reset promo status.'
    );
    //send success message
    return $this->response->SendResponse(200, true, 'Promo successfully updated.');
  }


  public function user_utility_recordAction(){
    //check request 
       //check request type 
       if(!$this->input->isPost()) return $this->response->SendResponse(
        403, false, POST_MSG
      );
      //check acl 
      if(!$this->indexMiddleware->isUAdmin() && !$this->indexMiddleware->isSuperAdmin())
      return $this->response->SendResponse(403, false, ACL_MSG);
    //fetch 

  }

  
}