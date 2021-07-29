<?php 
namespace core\compensation;
use core\Model;
use core\http\Middleware\IndexMiddleware;
use API\Model\UserInvestment;

class Interest{
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
  
  public function getRules(){
    
  }

  public function compundInterest($_duration, $principal,$percentageRate){
    $rate = $percentageRate/100;
    $bracket = 1+$rate;
    $bracketPower3 = pow($bracket, $_duration);

    $earned = $principal* $bracketPower3;

    return $earned;
  }

  public function compundTable($percentageRate, $_duration, $_principal){
    $actualRate = $percentageRate/100;
    $compoundMonths = [];
  
    for ($i = 0; $i <= $_duration; $i++) {
      $compoundMonths[]= $i;
    }

    $principal = [];
    $earned = '';
    $activePrincipal = $_principal;
    $earnedInt[] =[];
    foreach($compoundMonths as $month){
      $earned += $activePrincipal * $actualRate;
      $activePrincipal +=$earned;
      $earnedInt[] = $earned;
      $principal[] = $activePrincipal;
    }

    $compoundTable = array($compoundMonths,$earnedInt, $principal,);
    return $compoundTable;
  }

  public function daysOfInvestment($initialDate, $cancelDate){
    $investedOn = strtotime($initialDate);
    $cancelDate = strtotime($cancelDate);

    $timeDiff = abs($investedOn - $cancelDate);

    $numberDays = $timeDiff/86400;
    return $numberDays;
  }

  public function interestOnCancelDate(){
    $investmentDaysOld = $this->daysOfInvestment($this->_initialDate, date("Y-n-j"));
    
  }

  public function interest(){
    $rate = $this->_rate/100;
    $earned = $this->_principal * $rate;
    return $earned;
  }


}