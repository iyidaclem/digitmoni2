<?php 
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\Middleware;
use core\Model as CoreModel;
use Response;
use database\DataBase;


class UserController extends Controller{
  private $input;
  private $model;
  private $db;
  private $user;

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('users');
    $this->db = new DataBase();
    $this->user = new Users();
  }

  //this index action will view the current users profile and or that of any supplied id
  public function profileAction($username=null){
    if(!$this->input->isGet()) return $this->jsonResponse([
      'status'=>'fail',
      'http'=>401,
      'message'=>'Only GET Requests are allowed.',
      'data'=>[]
    ]);
    //$userID==null?$_userID = Users::currentUser():$_userID=null;
    $details = $this->model->findByUsername('users', $username);

    return $this->jsonResponse([
      'status'=>'success',
      'http'=>200,
      'message'=>'',
      'data'=>$details
    ]);
  }



  public function updateAction($targetID=null){
    if(!$this->input->isPut()) return $this->jsonResponse([
      'status'=>'fail',
      'http'=>401,
      'message'=>'Only PUT Requests are allowed.',
      'data'=>[]
    ]);

    //handling the request data
    $putData = file_get_contents('php://input');
    $data = json_decode($putData);
  
    //Sanitizing all the input values
    $sanitized = [];
    $msg =[];
    foreach($data as $k => $v){
      $pureVals = FH::sanitize($v);
      $sanitized[$k] = $pureVals;
    }
   
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
      'acl'=>$sanitized['acl'],
      'entry_code'=>$sanitized['entry_code'],
      'ref_code'=>$sanitized['ref_code'],
      'acc_type'=>$sanitized['acc_type'],
      'activity'=>$sanitized['activity']
      ];
    
    $user = new Users;
    If(!$user->editUser($targetID, $fields)){
      return $this->jsonResponse([
        'http'=>500,
        'status'=>'false',
        'message'=>'Failed to update user account.'
      ]);
    };

    $details = $user->viewUser($targetID);

    return $this->jsonResponse([
      'http'=>200,
      'status'=>'true',
      'message'=>'',
      'data'=>$details
    ]);

  }

  /*
  =====================================================
  */ 

  public function createAction(){
    if(!$this->input->isPost()) return $this->jsonResponse([
      'status'=>'fail',
      'http'=>401,
      'message'=>'Only PUT Requests are allowed.',
      'data'=>[]
    ]);

    $putData = file_get_contents('php://input');
    $data = json_decode($putData);
  
    //Sanitizing all the input values
    $sanitized = [];
    $msg =[];
    foreach($data as $k => $v){
      if($k!='acl'){
        $pureVals = FH::sanitize($v);
      $sanitized[$k] = $pureVals;
      }
    }
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

        return $this->jsonResponse([
          "http_status_code"=>401,
          "status"=>false, 
          "message"=>$msg,
          "data"=>[]
        ]);
      }
      //var_dump($fields);
      //die();
      //Create new account in the database and if it is successful, iniatialize it in fund_user table
      if($this->model->insert($fields)) $this->user->initializeAccount($sanitized['username']);
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

/*
===========================================================================

===========================================================================
*/ 

  public function loginAction(){
    
    if(!$this->input->isPost()) return $this->jsonResponse([
      'status'=>'false',
      'http'=>401,
      'message'=>'Only POST Requests are allowed.',
      'data'=>[]
    ]);
    $midware = new Middleware();
    $token = $midware->token();
    //inputs will be form data 
    $request = $_REQUEST;
    $username = FH::sanitize($request['username']);
    $password  = FH::sanitize($request['password']);
    $user_agent = trim($request['user_agent']);
    
    //checking if there is a user with the given username and password

    $checkUser = $this->model->findByUsernamePassword($username, $password);
    
    if(empty($checkUser)) return $this->jsonResponse([
      'http_status_code'=>401,
      "status"=>false,
      "message"=>"Username or password incorrect.",
      'data'=>[]
    ]);

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
   if($status)return $this->jsonResponse([
     'http_status_code'=>200,
     "status"=>true,
     "datat"=>[
        "username"=>$username,
        "access_token"=>$token,
        "token_exp"=>$fields['token_exp']
     ]
   ]); 
   
   if(!$status)return $this->jsonResponse([
    'http_status_code'=>401,
    "status"=>false,
    "data"=>[]
  ]); 

  }

/*
===========================================================================

===========================================================================
*/ 


  public function logoutAction($username){
    if(!$this->input->isGet()) return $this->jsonResponse([
      'status'=>'fail',
      'http'=>401,
      'message'=>'Only GET Requests are allowed.',
      'data'=>[]
    ]);
    
    //checking if the user is logged in 
    $checkLogged = $this->model->findByUsername('session_tb', $username);
   // var_dump($checkLogged);die();
    if(!$checkLogged)return $this->jsonResponse([
      'http_status_code'=>401,
      "status"=>false,
      "message"=>"You are logged out",
      "data"=>[]
    ]);
    $db = new DataBase();
    $logOut = $db->delete('session_tb', $checkLogged->id);

    if($logOut) return $this->jsonResponse([
      'http_status_code'=>200,
      "status"=>true,
      "message"=>"You have successfully logged out.",
      "data"=>[]
    ]);
  }
}