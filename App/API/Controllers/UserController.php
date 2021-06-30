<?php 
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;

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
    $userID==null?$userID = User::currentUser():
  }
}