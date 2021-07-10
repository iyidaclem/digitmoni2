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
use PDOException;

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
/**
 * 
 * 
 * 
 */
  public function fundAction($cardExists='yes',$uniqueCardID=null){
    //make sure it is post request 
    if(!$this->input->isPost()) return $this->response->SendResponse(
      401, false, POST_MSG
    );
    //check acl
    if(!$this->indexMiddleware->isUser())return $this->response->SendResponse(
      401, false, ACL_MSG
    );
    //if $cardExists = yes, then we need to get the card details from inside here using the chosen cardID
    if($cardExists==='yes' && $uniqueCardID!==null)
    $model = new CoreModel('card_det');
    $getCardDetail = $model->findFirst($uniqueCardID);
    $ourVendorCardID = $getCardDetail->vendorcardID;
    $username = $getCardDetail->username;
    $cardNo = $getCardDetail->card_no;
    $name = $getCardDetail->name;
    $reference = referenceGen();

    //initiate funding
    $model = new CoreModel('fund_history');
    $fields=[
      'username'=>$username,
      'reference'=>$reference,
      'cardno'=>$uniqueCardID,
      'trx_status'=>'initiated'
    ];
    if(!$model->insert($fields)) //ERROR LOG
    return $this->response->SendResponse(
      500, false, "Please bear with us. There is a problem and we are on it."
    );

    //initiate a call to paystack or any other payment facilitator to send us the money
    $amount = FH::sanitize($_REQUEST['amount']);
    $sendUsFundAPI_call = '';

    //if the transaction is unsuccessful, send message 
    if($sendUsFundAPI_call ===false)return $this->response->SendResponse(
      503, false, 'Failed transaction. Please try again later.');
    

    //if the transaction is successfull, set status to "completed" update  
    if($sendUsFundAPI_call === true)
    $model = new CoreModel('fund_history');
    $fields=[
      'trx_status'=>'completed'
    ];
    //if failure to update transaction status
    if(!$model->update($this->indexMiddleware->loggedUserID(), $fields))
    //ERROR LOG ACTION
    return $this->response->SendResponse(
      500, false, 'Funds recieve. It will reflect in your wallet shortly. There is a problem and we are working to resolve it.');
    //on successful trx_status update, user account balance
      $model = new CoreModel('user_fund');
    $fields = ['balance'=>$amount];
    if(!$model->update($this->indexMiddleware->loggedUserID, $fields))
    return $this->response->SendResponse(503,false, 'Fund recieved. Will reflect on your wallet shortly.');
    //GENERAL SUCCESS MESSAGE
    return $this->response->SendResponse(200, true, 'Wallet funded!');

  }


  public function fund_newcardAction(){
    if(!$this->input->isPost()) return $this->response->SendResponse(
      400, false, GET_MSG
    );
    if(!$this->indexMiddleware->isUser()) return $this->response->SendResponse(
      401, false, ACL_MSG
    );
    //take in card details and begin transaction
    $cardName = FH::sanitize($_REQUEST['card_name']);
    $cardNo = FH::sanitize($_REQUEST['card_no']);
    $cardDate = FH::sanitize($_REQUEST['card_date']);
    $amount = FH::sanitize($_REQUEST['amount']);
    $card3digit =FH::sanitize($_REQUEST['card_three']);
    

  }


  public function card_existsAction(){
    //check requst method 
    if(!$this->input->isPost()) return $this->response->SendResponse(
      401, false, POST_MSG
    );
    //check acl
    if(!$this->indexMiddleware->isUser())return $this->response->SendResponse(
      401, false, ACL_MSG
    );
    //decide who the logged in user is 
    $loggedUsername = $this->indexMiddleware->loggedUser();
    $model = new CoreModel('card_det');
    //query database to check if 
    $findUserCard = $model->find([ 'conditions' => 'username = ?','bind' => [$loggedUsername]]);
    if(empty($findUserCard))return $this->response->SendResponse(
      404, false
    );
    foreach($findUserCard as $cardUnique){
      $cardID[] = $cardUnique->cardID;
    }
    return $this->response->SendResponse(200, true, null, false, $cardID);
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