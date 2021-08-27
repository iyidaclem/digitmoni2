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
use API\Model\ReferralModel;
/**
 * [Description HomeController]
 * This class HomeController is the controller that will house all features or things user 
 * will be able to see without creating out or logging in.
 * 
 * Now lets dive in deep into the methods or endpoints. 
 */
class HomeController extends Controller{
  private $input, $model, $db, $response, $indexMiddleware,$user, $middleware, $referralModel;
  

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('users');
    $this->db = new DataBase();
    $this->response = new Response();
    $this->user = new Users();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->middleware = new Middleware();
    $this->referralModel = new ReferralModel('referrals');
  }

  public function referenceAction(){
    
  }

  /**
   * This endpoint (POST REQUEST) app/home/create is the endpoint to which you will submit 
   * the user account creation form data in json data format as follows: 
   * 
   * {
   *  "first_name":"first name",
   *  "lastname":"lastname",
   *  "username":"username"
   *  "email":"email",
   *  "pword":"passowrd",
   *  "address":"address",
   *  "phone":"phone",
   *  "acl":"[acl]", 
   *  "entry_code":"enter referal code here" 
   * }  
   * @return [type]
   * 
   * POSSIBLE RESPONSES 
   * 1. 400 with a message about a supplied "input not supplied"
   * 2. 400 with a message about "existing username" or "existing email"
   * 2. 
   */
  public function createAction(){
    echo "create"; die();
    if(!$this->input->isPost()) $this->response->SendResponse(
      401, false, POST_MSG
    );

    $putData = file_get_contents('php://input');
    $data = json_decode($putData);
   
    //Sanitizing all the input values
    $sanitized = FH::arraySanitize($data);
    //var_dump(array_keys($sanitized)); die();
    $inputKeys=['first_name','lastname','username','email','pword', 'addres','phone','entry_code' ];
    
    if(FH::inputIsset($sanitized, $inputKeys))

    $acl = serialize($data->acl); 
    $password = password_hash($sanitized['pword'],PASSWORD_DEFAULT);
    $refcode = $this->middleware->rand6();
    //creating input fields array
    $fields=[
      'first_name'=>$sanitized['first_name'],
      'lastname'=>$sanitized['lastname'],
      'username'=>$sanitized['username'],
      'email'=>$sanitized['email'],
      'pword'=>$password,
      'created_at'=>$sanitized['created_at'],
      'state'=>'active',
      'addres'=>$sanitized['addres'],
      'phone'=>$sanitized['phone'], 
      'acl'=>$acl,
      'entry_code'=>$sanitized['entry_code'],
      'ref_code'=>$refcode,
      'acc_type'=>'member',
      'activity'=>'active',
      'date_time'=>date('Y-m-d')
      ];
     
      //check if user already exists in database
      $userExist = $this->model->findByUsername('users', $sanitized['username']);
      $emailExist = $this->model->findByEmail('users', $sanitized['email']);
      $msg = [];
      if(!empty($userExist) || !empty($emailExist)){
        (!empty($userExist))?array_push($msg, 'Username already exists. Use another username please.'):null;
        (!empty($emailExist))?array_push($msg, 'Email already exists. Use another email please.'):null;
        return $this->response->SendResponse(
          400, false, $msg
        );
      }

      //Create new account in the database and if it is successful, iniatialize it in fund_user table
      if(!$this->model->insert($fields)) 
      //LOG ACCOUNT CREATION PROBLEM
     
      try{
        $this->user->initializeAccount($sanitized['username']);
      }catch(PDOException $err){

      }
     
      //PROCESS REF CODE
      if($sanitized['entry_code'] !== '')
      if($this->user->referralChecker($sanitized['entry_code']))
      $referrer = $this->user->getReferrer($sanitized['entry_code']);
      $refFields = [
        'referrer'=>$referrer,
        'referred'=>$sanitized['username']
      ];
      if(!$this->referralModel->insert($refFields)) {
        //Logg something
      }
      
      //Now send response 
      return $this->response->SendResponse(200, true, "Your account have been successfully created.");
  }


  /**
   * This endpoint (POST REQUEST) ...app/home/login 
   * when called will need parameters supplied as form data. Needed paramaters are:
   * username, password, user_agent. 
   * 
   * NB:The user_agent is user's device. You will need to get it via some javascript manipulations.
   * 
   * POSSIBLE RESPONSONSE
   * 1. Could return 401 with "incorrection username or password message"
   * 
   * 2. Could return 200 when user is successfully logged in with no message.
   * It this point, the endpoint also returns other details you need to manage state like:
   * 
   * username:principal to state management in this app.
   * access token
   * token expiry time 
   */
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
      401, false, "Username or password incorrect.");

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
  try{
    $status = '';
    $model->insert($fields)===true?$status=true:$status=false;
    if($status)return $this->response->SendResponse(
     200, false,'',false, ["username"=>$username,"access_token"=>$token,"token_exp"=>$fields['token_exp']]);
  }catch(PDOException $err){
    
   //LOG
  }
}



  /**
   * 
   * @return [type]
   */
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

  /**
   * This endpoint (GET REQUEST) ...app/home/our_offer 
   * when called returns all the investment offers available on the platform. Yours to beautifully
   * present in the UI.
   * 
   * POSSIBLE RESPONSES 
   * 1. Return 500 and system error message.
   * 
   * 2. Returns 200 and success message with list of all investment offers.
   * 
   * @return [type]
   */
  public function our_offerAction(){
    //making sure is is get request 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      403, false, GET_MSG
    ); 
    //guess anyone can call this end point
    try {
      $model = new CoreModel('investments');
      $allRunningPackage = $model->findByState('investments', 'running');
      return $this->response->SendResponse(200, true, ALL_INV_MSG, true, $allRunningPackage);
    } catch (\Throwable $th) {
      //get and log problem
      return $this->response->SendResponse(500,false, 'System error. We are on it.');
    }
  }

  /**
   * This end
   * @param mixed $packageID
   * 
   * @return [type]
   */
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