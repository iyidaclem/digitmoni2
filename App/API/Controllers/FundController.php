<?php 
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\Middleware;
use core\Model as CoreModel;
use core\Response as CoreResponse;
use database\DataBase;

class FundController extends Controller{
  private $input;
  private $model;
  private $db;
  private $middleware;
  private $indexMiddleware;
  private $response;
  
  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('user_fund');
    $this->db = new DataBase();
    $this->middleware = new Middleware();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->response = new CoreResponse();
  }

  public function checkbalAction($targetUser=null){
    //check request method 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );
    //check access level 
    if(!$this->indexMiddleware->isUser() && !$this->indexMiddleware->isSuperAdmin()) 
    return $this->response->SendResponse(
      401, false, ACL_MSG);
    //determing whether we are checking logged user's account or a third party account
    $targetUser ===null?$user =$this->indexMiddleware->loggedUser():$user=$targetUser;
      //finally check account
    $fund = $this->model->findByUsername($this->model->_table, $user);
    //if failure to retrieve account bal, send failure message
    if(!$fund) return $this->response->SendResponse(
      401, false, 'Failed to retrieve account balance. Please contact admin.'
    );
    //send success message with balance and other details
    $balance = $fund->balance;
    return $this->response->SendResponse(
      200, true, null, false, $balance);
  }

  public function fundAction($reference, $saveCard='no'){
    //make sure it is post request 
    if(!$this->input->isPost()) return $this->response->SendResponse(
      401, false, POST_MSG
    );
    //check acl
    if(!$this->indexMiddleware->isUser())return $this->response->SendResponse(
      401, false, ACL_MSG
    );
    //if $saveCard is YES, then save card details

    //call our transfer facilitator api with the card details provided

    //if transfer fail, send failure message
    
    //if successful transfer to us, update his balance after taking care of the charges

    //$saveCard == no, field will be 
    $fields = [
      //"balance"=>$amount,
    ];

    //$saveCard == yes, define fields with card details hashed
    $fundAccount = $this->model->update($this->model->_table, $fields);

  }


  public function change_acc_numAction(){
   
  }


  public function getcardAction($username=null){
    //request method check
    if($this->input->isPost()) return $this->response->SendResponse(
      405, false, POST_MSG
    );
    //check acl
    if(!$this->indexMiddleware->isUser()) return $this->response->SendResponse(
      403, false, ACL_MSG
    );
    //determine who the logged in user is
    ($username==null)?$_username = $this->indexMiddleware->loggedUser():$_username = $username;
    //query the database with this username to get card details
    $model = new CoreModel('user_acc_det');
    $userAccDetails = $this->model->find([
      'conditions' => 'username = ?','bind' => [$_username]
    ]);
    if(!$userAccDetails) return $this->response->SendResponse(404, false, 'Your card no. isnt saved.');
    //send success message with the card no showing only last four digits
   // $userAccDetails->
    //return $this->response->SendResponse(200, true, null,false, )
  }

  public function add_acc_noAction(){
     //check request method 
     if($this->input->isPost()) return $this->response->SendResponse(
      405, false, POST_MSG
    );
    //check acl
    if(!$this->indexMiddleware->isUser()) return $this->response->SendResponse(
      403, false, ACL_MSG
    );
    //get logged in user
    $loggedUsername = $this->indexMiddleware->loggedUser();
    //process input 
    $jsonData = file_get_contents('input://php');
    $data = json_decode($jsonData);
    //sanitize
    $sanitized = [];
    $msg =[];
    foreach($data as $k => $v){
      if($k!='acl'){
        $pureVals = FH::sanitize($v);
      $sanitized[$k] = $pureVals;
      }
    }
    //set up fields 
    $fields = [
      'account_no'=>$sanitized['account_no'],
      'account_name'=>$sanitized['account_name'],
      'bank'=>$sanitized['bank'],
      'bank_id'=>$sanitized['bank_id']
    ];
    //insert into the database
    $model = new CoreModel('user_bank_det');
    if(!$model->insert($fields)) 
    //LOG ACTION - DATABASE INSERT FAILURE FUND CONTROLLER
    
       //send error message
    return $this->response->SendResponse(
      500, false, 'Failed to save account number. There is a problem from our end. We are working it.'
    );

    //send success message
    return $this->response->SendResponse(
      200, true, 'Account number successfully saved.'
    );
    
  }

  public function fund_uniqueAction(){

  }

  public function withdrawAction($amount){
    $loggedInUser = $this->middleware->loggedUser();
    /*check if the user have up to the amount and if not send decline response*/

    //send transfer request to paystack. 

    //if it fails, send fail response

    //if it succeeds, updtate the database
    $fields = [
      "balance"=>$amount,
    ];
    $withrwal = $this->model->update($this->model->_table, $fields);
  }


}