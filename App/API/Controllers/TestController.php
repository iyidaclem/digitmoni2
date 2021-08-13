<?php
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\Model as CoreModel;
use core\Response;
use database\DataBase;
use PDO;
use PDOException;
use core\compensation\Interest;
//Test dependencies
use API\Model\UserInvestment;

class TestController extends Controller{
  private $input, $model, $db, $response, $indexMiddleware, $middleware;
  private $userInvModel;

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('users');
    $this->db = new DataBase();
    $this->response = new Response();
    // $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->userInvModel = new UserInvestment('user_investments');
  }

  /**
   * 
   * 
   * 
   * @return [type]
   */
  public function due_investment_testAction(){
    $data = $this->userInvModel->dueInvestment('yes');
    var_dump($data);die();
    return $this->response->SendResponse(200, true, '', false, $data);
  }

  



}