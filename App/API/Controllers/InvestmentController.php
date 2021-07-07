<?php 
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\IndexMiddleware;
use core\http\Middleware\Middleware;
use core\Model as CoreModel;
use core\Response;
use database\DataBase;

class InvestmentController extends Controller{
  private $input, $model, $db, $response, $indexMiddleware, $middleware;
  

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('users');
    $this->db = new DataBase();
    $this->response = new Response();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
  }

  public function our_offerAction(){
    //making sure is is get request 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    ); 
    //guess anyone can call this end point
    $this->table = 'investments';
    $allRunningPackage = $this->model->findByState('investments', 'running');

    return $this->response->SendResponse(
      200, true, ALL_INV_MSG, true, $allRunningPackage
    );
  }

  public function viewpackageAction($packageID){
    //making sure it is the right request GET
    if(!$this->input->isGet) return $this->response->SendResponse(
      401, false, GET_MSG
    );

    //querying the database to view package detail
    $this->table = 'investments';
    $packageDet = $this->model->findFirst([
      'conditions' => 'id = ?','bind' => [$packageID]]);
    //send failure response
    if(!$packageDet) return $this->response->SendResponse(
      404, false, WNT_WRNG_MSG
    );
    //send success response
    return $this->response->SendResponse(
      200, true, null, true,$packageDet 
    );

  }

  public function investAction($user=null){
    //check incoming request 
    if(!$this->input->isPost()) return $this->response->SendResponse(
      401, false, POST_MSG
    );
    //check acl 
    if(!$this->indexMiddleware->isUser())return $this->response->SendResponse(
      401, false, ACL_MSG
    );
    //determin who the user is
    $user===null?$_user = $this->middleware->loggedUser():$_user = $user;
    //handle inputs
    $jsonData = file_get_contents('input://php');
    $data = json_decode($jsonData);
    if(!$data) return $this->response->SendResponse(
      400, false, JSON_MSG 
    );
    //check users account to see if have upto amount he wants to invest
    $model = new CoreModel('user_fund');
    $userBalance = $model->findByUsername('user_fund', $_user)->extractor();
    $bal = $userBalance->balance;
    //compare with amount user wants to invest
    if($data->amount < $bal) return $this->response->SendResponse(
      400, false, 'You need to fund your account first using your card.'
    );
    //purify inputs  
    $sanitized = [];
    $msg =[];
    foreach($data as $k => $v){
      if($k!='acl'){
        $pureVals = FH::sanitize($v);
      $sanitized[$k] = $pureVals;
      }
    }
    //set fields 
    $fields = [
      'investmendID'=>$sanitized['investmendID'],	
      'username'=>$sanitized['username'], 	
      'created_at'=>$sanitized['created_at'],	
      'matures_at'=>$sanitized['matures_at'],	
      'inv_reference'=>$sanitized['inv_reference'],		
      'rollover'=>$sanitized['rollover']	
    ];
    //initialized investment
    $initiateInvestment = $model->insert($fields); 
    if(!$initiateInvestment) return $this->response->SendResponse(
      500, false, SERVER_MSG
    );
    //LOG ACTION- SERVER ERROR IN INVESTMENT:ACTION

  }

/*this action will be called after calling investAction. The job of this function is to 
  update the transaction status with fail or success from our payment facilitator
*/
  public function invest_statusAction($status, $user=null){
    //check request type
    if(!$this->input->isGet()) return $this->response->SendResponse(
      405, false, GET_MSG
    );
    //check acl 
    if(!$this->indexMiddleware->isUser()) return $this->response->SendResponse(
      403, false, ACL_MSG
    );
    //deciding who the user is- admin performing action on a user or user performing on his account
    $user===null?$_user = $this->middleware->loggedUser():$_user = $user;
    //query database to set to set status where reference is as given
    $model = new CoreModel('user_investments');
    $investmentByRef = $model->find([
      'conditions' => 'id = ?','bind' => [$_user]
    ]);
    $fields =['status'=>$status];
    $setStatus = $model->update($investmentByRef->id, $fields);
    //send final message 
    
    if(($status ==true && $setStatus==true) || ($status ==true && $setStatus==false))
    //LOG ACTION
    return $this->response->SendResponse(200, false, 'Investment successful'); 

    if(($status ==false && $setStatus==false) ||($status==false && $setStatus==true))
    //LOG ACTION
    return $this->response->SendResponse(402, false, 'Failed failed transaction.');
  }



  public function cancelAction($user_investmentID){
    //making sure it is a GET request 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );
    //making sure the user is either the supeer admin or the investment admin
    if(!$this->middleware->isSuperAdmin() && !$this->middleware->isInvAdmin()
    && !$this->middleware->isUser()) return $this->response->SendResponse(400, false, ACL_MSG);

    //now query the database with the package name provided
    $this->table = 'user_investments';
    $fields =[
      'status'=>'deactivated'
    ];
    /*
    if fixed investment and not due, get the cancel cost percentage 
    get the cancel cost percentage and minus it from the amount invested
    */

    //cancel the investment
    $cancelMyInvestment = $this->model->update($user_investmentID, $fields);
    if(!$cancelMyInvestment) return $this->response->SendResponse(
      400, false, 'Failed to cancel investment. Try again later.'
    );

    //Fund his account with the right amount 
    
    
  }
}