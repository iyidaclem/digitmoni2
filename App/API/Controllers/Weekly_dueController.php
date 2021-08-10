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
use core\http\Middleware\IndexMiddleware;
use core\Helper;
use core\Helper\Help;
use API\Model\UserInvestment;



class Weeky_due extends Controller{
  private $db,$help, $model,$resp, $middleware, $input,$indexMiddleware,$fh, $userInvModel;
  
  public function __construct($controller, $action){
    parent::__construct($controller, $action);
    $this->db = new DataBase();
    $this->model = new coreModel($this->tabl);
    $this->middleware = new Middleware();
    $this->fh = new FH();
    $this->input = new Input();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->resp = new Response();
    $this->help = new Help();
    $this->userInvModel = new UserInvestment('user_investments');
  }

  /**
   * This action can be called thus .../weekly_due/compunders/{rollover:yes or no}
   * Use "yes" whe you you want get all due investments in which rollover is active. 
   * So compund interest will be applied.
   * 
   * Use no to get all investments where simple interest applies because rollover is not 
   * active.
   * 
   * This call returns investment amount, maturity date, interest rate majorly.
   */


  public function compoundersAction($rollover){
    if(!$this->input->isGet()) return $this->resp->SendResponse(
      401, false, GET_MSG);
    if(!$this->indexMiddleware->isSuperAdmin() && !$this->indexMiddleware->isInvAdmin())
    return $this->resp->SendResponse(401, false, ACL_MSG);

    $dueInvestment = $this->userInvModel->investmentsDueIn7Days($rollover);

    return $this->resp->SendResponse(200, true, '',false, $dueInvestment);
  }


}