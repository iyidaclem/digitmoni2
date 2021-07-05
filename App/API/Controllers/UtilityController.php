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
    $this->table = 'transactions';
    $refExists = $this->model->findFirst([ 'conditions' => 'reference = ?',
    'bind' => [$ref]]);
    if(!$refExists) return $this->response->SendResponse(
      400, false, NOT_FOUND_MSG
    );
    /*change the table the model uses and go again this time
    to search the appropriate table for the rest of transaction info
    */
    //'data','airtime','tv','electric','withdraw'
    switch ($refExists->purpose) {
      case 'data':
          $this->table = 'data';
          $fuldetail = $this->model->findFirst([ 'conditions' => 'reference = ?',
          'bind' => [$ref]]);
          break;
      case 'airtime':
          $this->table = 'airtimes';
          $fuldetail = $this->model->findFirst([ 'conditions' => 'reference = ?',
          'bind' => [$ref]]);
          break;
      case 'tv':
          $this->table = 'tv';
          $fuldetail = $this->model->findFirst([ 'conditions' => 'reference = ?',
          'bind' => [$ref]]);
          break;
      case 'electric':
          $this->table = 'electricity';
          $fuldetail = $this->model->findFirst([ 'conditions' => 'reference = ?',
          'bind' => [$ref]]);
          break;
      case 'withdraw':
          $this->table = 'acc_history';//this table has not been created.
          $fuldetail = $this->model->findFirst([ 'conditions' => 'reference = ?',
          'bind' => [$ref]]);
          break;
    }
    //unset duplicate columns, merge the result and send a response.
    
  }

  public function sumdataAction($duration=null){
    if(!$this->middleware->isSuperAdmin() && !$this->middleware->isUAdmin())
    return $this->response->SendResponse(401, false, ACL_MSG);
    //making sure it is a get 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );
    //Fetch all transactions where purpose==data for the given length of time. 
    $this->table = 'data';
    //POSSIBLE MEMORY LEAKS
    if($duration === null) $dataSales = $this->model->find();

    if($duration !== null) $dataSales = $this->model->find();
    //

  }

  public function sumAirtimeAction($duration=null){
    if(!$this->middleware->isSuperAdmin() && !$this->middleware->isUAdmin())
    return $this->response->SendResponse(401, false, ACL_MSG);
    //making sure it is a get 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );
    //fetch all airtime sold for the day or the given time duration
    $this->table = 'airtime';
    //POSSIBLE MEMORY LEAKS
    if($duration === null) $airTimeSales = $this->model->find();

    if($duration !== null) $airTimeSales = $this->model->find();
    //
  }

  public function sumtvAction($duration=null){
    if(!$this->middleware->isSuperAdmin() && !$this->middleware->isUAdmin())
    return $this->response->SendResponse(401, false, ACL_MSG);
    //making sure it is a get 
    if(!$this->input->isGet()) return $this->response->SendResponse(
      401, false, GET_MSG
    );
     //fetch all airtime sold for the day or the given time duration
     $this->table = 'tv';
     //POSSIBLE MEMORY LEAKS
     if($duration === null) $tvSubSales = $this->model->find();
 
     if($duration !== null) $tvSubSales = $this->model->find();
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