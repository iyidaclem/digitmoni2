<?php 
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\Middleware;
use API\Model\UtilityAdmin;
use core\Response;
use database\DataBase;
use core\http\Middleware\IndexMiddleware;

class admintvController extends Controller{
  private $input;
  private $model;
  private $db;
  private $user;
  private $table;
  private $response;
  private $middleware;

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new UtilityAdmin($this->table);
    $this->db = new DataBase();
    $this->user = new Users();
    $this->response = new Response();
    $this->middleware = new IndexMiddleware();
  }

  public function testAction(){
    print('it works');
  }
}