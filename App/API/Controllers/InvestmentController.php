<?php 
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\Middleware;
use core\Model as CoreModel;
use core\Response;
use database\DataBase;

class InvestmentController extends Controller{
  private $input, $model, $db, $response;
  

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('users');
    $this->db = new DataBase();
    $this->response = new Response();
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

  public function investAction(){

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