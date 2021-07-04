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

class UtilityController extends Controller{
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

  public function refSearchAction($ref){
    //making sure the request is from the super user or utility admin
    if(!$this->middleware->isSuperAdmin() && !$this->middleware->isUAdmin())
    return $this->response->SendResponse(401, false, ACL_MSG);
    //making sure it is a get 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );
    //now let us query the database for his search
    //WHAT I USED HERE IS FIND-BY QUERY INSTEAD OF A TYPICAL SEARCH

  }

  public function sumdataAction($duration=null){
    if(!$this->middleware->isSuperAdmin() && !$this->middleware->isUAdmin())
    return $this->response->SendResponse(401, false, ACL_MSG);
    //making sure it is a get 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );
  }

  public function sumAirtimeAction($duration=null){
    if(!$this->middleware->isSuperAdmin() && !$this->middleware->isUAdmin())
    return $this->response->SendResponse(401, false, ACL_MSG);
    //making sure it is a get 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );
  }

  public function sumtvAction($duration=null){
    if(!$this->middleware->isSuperAdmin() && !$this->middleware->isUAdmin())
    return $this->response->SendResponse(401, false, ACL_MSG);
    //making sure it is a get 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );
  }

  public function data_ruleAction(){
    if(!$this->middleware->isSuperAdmin() && !$this->middleware->isUAdmin())
    return $this->response->SendResponse(401, false, ACL_MSG);
    //making sure it is a get 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );
  }

  public function airtime_ruleAction(){
    if(!$this->middleware->isSuperAdmin() && !$this->middleware->isUAdmin())
    return $this->response->SendResponse(401, false, ACL_MSG);
    //making sure it is a get 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );
  }

  public function tv_ruleAction(){
    if(!$this->middleware->isSuperAdmin() && !$this->middleware->isUAdmin())
    return $this->response->SendResponse(401, false, ACL_MSG);
    //making sure it is a get 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );
  }



}