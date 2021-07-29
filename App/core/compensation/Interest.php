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

  public function compundInterestMonthly($_duration, $principal,$percentageRate){
    $rate = $percentageRate/100;
    $bracket = 1+$rate;
    $bracketPower3 = pow($bracket, $_duration);

    $earned = $principal* $bracketPower3;

    return $earned;
  }

  public function compoundInterestDaily($numberDays,$principal,$percentageRatePerMonth){
    $dailyRateInPercentage = $this->ratePerDay($percentageRatePerMonth, $numberDays);
    $compIntByDays = $this->compundInterestMonthly($numberDays, $principal, $dailyRateInPercentage);

    $compIntByDays = round($compIntByDays, 2);
    return $compIntByDays;
  }
  
  public function compundTable($percentageRate, $_duration, $_principal){
   
  }

  public function daysOfInvestment($initialDate, $cancelDate){
    $investedOn = strtotime($initialDate);
    $cancelDate = strtotime($cancelDate);
    $timeDiff = abs($investedOn - $cancelDate);

    $numberDays = $timeDiff/86400;
    return intval($numberDays);
  }

  public function ratePerDay($monthlyRate, $numberDays){
    $ratePerDay = $monthlyRate/$numberDays;
    $ratePerDay = round($ratePerDay, 2);
    return $ratePerDay;
  }

  public function interest($monthlyPercentageRage, $principal, $numOfMonths){
    $rate = $monthlyPercentageRage/100;
    $earned = $principal * $rate;
    $interestOverTime = round(($numOfMonths * $earned), 2);
    return $interestOverTime;
  }



}