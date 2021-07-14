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
use core\helper\Help;
use API\Model\Fund;
use API\Model\UserCard;

class FundController extends Controller{
  private $input, $model, $db, $middleware, $indexMiddleware, $response, $help;
  
  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('user_fund');
    $this->db = new DataBase();
    $this->middleware = new Middleware();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->response = new CoreResponse();
    $this->help = new Help();
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
    return $this->response->SendResponse(200, true, null, false, $balance);
  }
/**
 * 
 * 
 * 
 */
  public function fundAction(){
    //make sure it is post request 
    if(!$this->input->isPost()) return $this->response->SendResponse(
      401, false, POST_MSG
    );
    //check acl
    if(!$this->indexMiddleware->isUser())return $this->response->SendResponse(
      401, false, ACL_MSG
    );
    //getting username of logged user
    $loggedInUser = $this->indexMiddleware->loggedInUser();

    //Get users card details
    $fundModel = new Fund('card_det');
    $getCardDetail = $fundModel->findByUsername('card_det', $loggedInUser);
    $ourVendorCardID = $getCardDetail->vendorcardID;
    $cardNo = $getCardDetail->card_no;
    $name = $getCardDetail->name;
    $reference = $this->help->Unique_Id_Gen('R', 16);
  
    //initiate funding
    $amount = FH::sanitize($_REQUEST['amount']);
    $fundModel = new Fund('userfund_rec');
    $fields=[
      'username'=>$loggedInUser,
      'reference'=>$reference,
      'purpose'=>'funding',
      'amount'=>$amount,
      'status'=>'initiated'
    ];
    if(!$fundModel->insert($fields)) //ERROR LOG
    return $this->response->SendResponse(
      500, false, "Please bear with us. There is a problem and we are on it."
    );

    //initiate a call to paystack or any other payment facilitator to send us the money
    $sendUsFundAPI_call = '';

    //if the transaction is unsuccessful, send message 
    if($sendUsFundAPI_call ===false)return $this->response->SendResponse(
      503, false, 'Failed transaction. Please try again later.');
    

    //if the transaction is successfull, set status to "completed" update  
    if($sendUsFundAPI_call === true)
    $fields=[
      'status'=>'completed'
    ];
    //if failure to update transaction status
    if(!$fundModel->update($this->indexMiddleware->loggedUserID(), $fields))
    //ERROR LOG ACTION
    return $this->response->SendResponse(
      500, false, 'Funds recieve. It will reflect in your wallet shortly. There is a problem and we are working to resolve it.');
    //on successful trx_status update, user account balance
    $fundModel = new Fund('user_fund');
    $userID = $this->indexMiddleware->loggedUserID();
    if(!$fundModel->updateUserAccountBalance($loggedInUser, $amount, $userID))
    return $this->response->SendResponse(500, false, 'Account funded. It will reflect on your balance shortly.');
    return $this->response->SendResponse(200, true, 'Account successfully funded.');
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
    $reference = $this->help->Unique_Id_Gen('R',12);
    
    //get logged in user
    $loggedUsername = $this->indexMiddleware->loggedUser();
    $loggedUserID = $this->indexMiddleware->loggedUserID();
    //instantiate transaction in database
    $fundModel = new Fund('userfund_rec'); 
    $fields=[
      'username'=>$loggedUsername,
      'reference'=>$reference,
      'purpose'=>'funding',
      'amount'=>$amount,
      'status'=>'initiated'
    ];
    if(!$fundModel->insert($fields)) return $this->response->SendResponse(
      500, false, 'Our bad! This service is currently down, we are working on it.'
    );
    //getting the last inserted ID
    $lastInsertID = $fundModel->lastIDinserted();
    //now call our payment facilitator API 
    $transferFunds = '';

    //in case of failed transfer 
    if(!$transferFunds) return $this->response->SendResponse(
      503, false, "Sorry failed transaction. Please try again later."
    );
    //in case of success, update database 
    if($transferFunds)
    $completedFields =[
      'status'=>'completed'
    ];
    if(!$fundModel->update($lastInsertID, $completedFields)) //ERR LOG ACTION- fund recieved, failed to register as completed
    return $this->response->SendResponse(
      400, false, "Funds recieved. It will reflect on your dashboard shortly."
    );
    //send full success message
    if(!$fundModel->updateUserAccountBalance($loggedUsername, $amount, $loggedUserID))return 
      $this->response->SendResponse(200, false, 'Fund recieved.');
    return $this->response->SendResponse(200, false, 'Fund recieved.');
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


  public function getcardAction(){
    //request method check
    if($this->input->isPost()) return $this->response->SendResponse(
      405, false, POST_MSG
    );
    //check acl
    if(!$this->indexMiddleware->isUser()) return $this->response->SendResponse(
      403, false, ACL_MSG
    );
    //determine who the logged in user is
    $_username = $this->indexMiddleware->loggedUser();
    //query the database with this username to get card details
    $userCard = new UserCard('usercard');
    $lastFour = $userCard->cardLastFour($_username);
    
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
    $sanitized = FH::arraySanitize($data);
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
    //check incoming request 
    if(!$this->input->isPost()) return $this->response->SendResponse(
      405, false, POST_MSG);
    //acl check
    if(!$this->indexMiddleware->isUser()) return $this->response->SendResponse(
      401, false, ACL_MSG);
    //get logged-in user
    $loggedInUser = $this->middleware->loggedUser();
    /*check if the user have up to the amount and if not send decline response*/
    $fundModel = new Fund('user_fund');
    $userAcc = $fundModel->UserAaccBalance($loggedInUser);
    $userAccBalance = $userAcc->balance;
    if($amount>$userAccBalance)return $this->response->SendResponse(
      402, false, 'Insufficient balance!');
    
    //get user account details 
    $fundModel = new Fund('user_acc_det');
    $account = $fundModel->UserAcc($loggedInUser);

    //instantiate withdrawal in the database
    $fundModel = new Fund('withdraw_history');
    
   $reference = $this->help->Unique_Id_Gen('R', 12);
    $withdrawField=[
      'username'=>$loggedInUser,
      'reference'=>$reference,
      'amount'=>$amount,
      'bank_det'=>$account->name .' '.$account->bank_name,
      'trx_status'=>'initiated'
    ];
    if(!$fundModel->insert($withdrawField)) return $this->response->SendResponse(
      503, false, 'Sorry something went wrong. We are on it.'
    );
    $lastInsertID = $fundModel->lastIDinserted();
    //send transfer request to paystack. 
    $withDrawRequest = '';
    //if it fails, send fail response
    if(!$withDrawRequest) return $this->response->SendResponse(
      503, false, 'Service is currently unavaialable.');
    //if it succeeds, updtate the database
    if($withDrawRequest) $withdrawFieldUpdate = [
      "status"=>'completed',
    ];
    $withdrawalUpdate = $fundModel->update($lastInsertID, $withdrawFieldUpdate);
    if(!$withdrawalUpdate) //WITHDRAW ERROR LOG ACTION
    return $this->response->SendResponse(200, true,'Your withdrawal is successful');
    return $this->response->SendResponse(200, false, 'Your withdrawal is successful.');
  }


}