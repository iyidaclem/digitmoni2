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
use PDO;
use PDOException;
use core\compensation\Interest;

class HomeController extends Controller{
  private $input, $model, $db, $response, $indexMiddleware, $middleware;
  

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('users');
    $this->db = new DataBase();
    $this->response = new Response();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
  }

  public function interest_testingAction(){
    $interest = new Interest();
    $interest->compundInterest(4,1000,8);
    return $this->resp->SendResponse(200, true, 'interest result', false,$interest);
  }
  
  public function test2Action(){
    $interest = new Interest();
    $interest->compundTable(4,1000,8);
    return $this->resp->SendResponse(200, true, 'interest result', false,$interest);
  }
  

  public function referenceAction(){
    
  }

  public function createAction(){
    if(!$this->input->isPost()) $this->response->SendResponse(
      401, false, POST_MSG
    );

    $putData = file_get_contents('php://input');
    $data = json_decode($putData);
    //making sure that all inputs are supplied
    //  CODE 

    //Sanitizing all the input values
    $sanitized = [];
    $msg =[];
    foreach($data as $k => $v){
      if($k!='acl'){
        $pureVals = FH::sanitize($v);
      $sanitized[$k] = $pureVals;
      }
    }
  
    //var_dump(array_keys($sanitized)); die();
    $inputKeys=['first_name','lastname','username','email','pword','created_at', 'state', 'addres','phone','entry_code', 'ref_code','acc_type','activity' ];

    if(FH::inputIsset($sanitized, $inputKeys));

    $acl = serialize($data->acl);
    
    //creating input fields array
    $fields=[
      'first_name'=>$sanitized['first_name'],
      'lastname'=>$sanitized['lastname'],
      'username'=>$sanitized['username'],
      'email'=>$sanitized['email'],
      'pword'=>$sanitized['pword'],
      'created_at'=>$sanitized['created_at'],
      'state'=>$sanitized['state'],
      'addres'=>$sanitized['addres'],
      'phone'=>$sanitized['phone'], 
      'acl'=>$acl,
      'entry_code'=>$sanitized['entry_code'],
      'ref_code'=>$sanitized['ref_code'],
      'acc_type'=>$sanitized['acc_type'],
      'activity'=>$sanitized['activity']
      ];
    
      //check if user already exists in database
      $userExist = $this->model->findByUsername('users', $sanitized['username']);
      $emailExist = $this->model->findByEmail('users', $sanitized['email']);
      $msg = [];
      if(!empty($userExist) || !empty($emailExist)){
        (!empty($userExist))?array_push($msg, 'Username already exists. Use another username please.'):null;
        (!empty($emailExist))?array_push($msg, 'Email already exists. Use another email please.'):null;
        return $this->response->SendResponse(
          401, false, $msg
        );
      }

      //Create new account in the database and if it is successful, iniatialize it in fund_user table
      if($this->model->insert($fields)) $this->user->initializeAccount($sanitized['username']);
      
      //PROCESS REF CODE
      
      
      //Now send response 
      return $this->jsonResponse([
        "http_status_code"=>200,
        "status"=>true, 
        "message"=>'Your account has been created',
        "data"=>[]
      ]);

      return $this->jsonResponse([
        "http_status_code"=>500,
        "status"=>false, 
        "message"=>"Something went wrong. We are working on it.",
        "data"=>[]
      ]);

  }


  public function loginAction(){   
    if(!$this->input->isPost()) return $this->response->SendResponse(
      401, false, POST_MSG
    );
 
    $midware = new Middleware();
    $token = $midware->token();
    //inputs will be form data 
    $request = $_REQUEST;
    $username = FH::sanitize($request['username']);
    $password  = FH::sanitize($request['password']);
    $user_agent = trim($request['user_agent']);
    
    //checking if there is a user with the given username and password

    $checkUser = $this->model->findByUsernamePassword($username, $password);
    
    if(empty($checkUser)) return $this->response->SendResponse(
      401, false, "Username or password incorrect."
    );

    //preparing fields
    $fields = [
      "username"=>$username,
      "access_token"=>$token,
      "user_agent"=>$user_agent,
      "token_exp"=>604800,
    ];
    
    $model= new CoreModel('session_tb');
    $userLogged = $model->findByUsername('session_tb', $username);
    if($userLogged){
      $this->db->delete('session_tb',$userLogged->id);
    }
    
    //create a new session
    $status = '';
   $model->insert($fields)===true?$status=true:$status=false;
   if($status)return $this->response->SendResponse(
     200, false, ["username"=>$username,"access_token"=>$token,"token_exp"=>$fields['token_exp']]);

   
   if(!$status)return $this->response->SendResponse(
     500, false, "Create Account First"
   );
  }



  public function check_utility_promoAction(){
    //check request type 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      403, false, GET_MSG
    );
    //check database for active promo
    try{
      $model = new CoreModel('acc_type_rule');
      $promoRunning = $model->find([
        'conditions' => 'promo_mode = ?','bind' => ['on']
      ]);
    }catch(PDOException $err){
      //ERRO-LOG-ACTION
    }
    //send no-promo message
     if(!$promoRunning) return $this->response->SendResponse(
      200, false, 'No promo running at this point.'       
     );    

     //check who owns the promo
     $promoOwnerArr = [];
    
  }

  public function our_offerAction(){
    //making sure is is get request 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      403, false, GET_MSG
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
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );

    //querying the database to view package detail
    $model = new CoreModel('investments');
    $packageDet = $model->find([
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
}