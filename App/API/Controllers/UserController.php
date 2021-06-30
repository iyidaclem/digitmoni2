<?php 
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;


class UserController extends Controller{
  private $input;
  
  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
  }

  //this index action will view the current users profile and or that of any supplied id
  public function indexAction($userID=null){
    if(!$this->input->isPost()) return $this->jsonResponse([
      'status'=>'fail',
      'http'=>401,
      'message'=>'Only Post Requests are allowed.',
      'data'=>[]
    ]);
    $userID==null?$_userID = Users::currentUser():$_userID=null;
    $user = new Users();
    $details = $user->viewUser($_userID);

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

  public function createAction(){

  }

  public function loginAction(){
    
    if(!$this->input->isPost()) return $this->jsonResponse([
      'status'=>'false',
      'http'=>401,
      'message'=>'Only POST Requests are allowed.',
      'data'=>[]
    ]);
    //inputs will be form data 
    $request = $_REQUEST;
    $username = FH::sanitize($request['username']);
    $password  = FH::sanitize($request['password']);
    $fields = [
      ""=>"",
      ""=>"",
      ""=>"",
      ""=>"",
      ""=>"",
      ""=>""
    ];
    var_dump($request['username']);



  }

  public function logoutAction(){

  }
}