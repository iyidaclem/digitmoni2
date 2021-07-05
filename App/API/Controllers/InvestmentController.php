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

class InvestmentController extends Controller{

  

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('users');
    $this->db = new DataBase();
  }

  public function our_offerAction(){

  }

  public function viewpackageAction($packagename){

  }

  public function investAction(){
         
  }

  public function cancelAction($user,$packageName){
    //making sure it is a GET request 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );
    //making sure the user is either the supeer admin or the investment admin
    if(!$this->middleware->isSuperAdmin() && !$this->middleware->isInvAdmin()) 
    return $this->response->SendResponse(400, false, ACL_MSG);

    //now query the database with the package name provided
    $this->table = 'user_investments';
    $package = $this->model->findFirst([
      'conditions' => 'id = ? AND option_name = ?',
      'bind' => [$packageID, $packageName]
    ]);

    //here set package state to disable.

  }
}