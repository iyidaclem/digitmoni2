<?php 
namespace core\jobs;
use core\Model;
use API\Model\Users;

class InvestmentPayout{
  private $_duration;
  private $_rollover;
  private $_rate;
  private $_earned;
  private $_principal;
  private $_username;
  private $_userID;
  private $indexMiddleware;

  public function __construct(){
    
  }

  public function getDueInvestors(){
    $today = DATE();
    $model = new Model('user_investment');
    $dueInvestors = $model->find([]);
  }
  
  public function payDueInvestors(){
    
  }


}