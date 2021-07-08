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


  public function referenceAction(){
    
  }

  public function check_promoAction(){
    //check request type 
    if(!$this->input->isPost()) return $this->response->SendResponse(
      403, false, POST_MSG
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
}