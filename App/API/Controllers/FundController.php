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


  public function getcardAction(){

  }

  public function add_acc_noAction(){
    
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