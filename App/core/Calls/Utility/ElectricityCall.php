<?php
namespace core\Call\Utility;
use Requests;
use Requests_Auth_Basic;
use core\Call\CallKeyFromDB;


class ElectricityCall{
  private $httpRequest;
  private $theKey;
  private $ourID;
  public function __construct(){
    $callCredentials = new CallKeyFromDB();
    $cred = $callCredentials->getAPIkeyFromDB();
    $this->theKey = $cred[0];
    $this->ourID = $cred[1];
  }

  public function getAvailableDisco(){
    $response = Requests::get("http://mobileairtimeng.com/httpapi/power-lists?userid=$this->ourID&pass=$this->theKey");
    return $response;
  }

  public function validateMeterCall($service, $meterno){
    $response = Requests::post("http://mobileairtimeng.com/httpapi/power-validate?userid=$this->ourID&pass=$this->theKey&service=$service&meterno=$meterno&jsn=json");
    return $response;
  }

  public function payCall($ref, $service, $type, $meterno, $amount){
    $response = Requests::post("http://mobileairtimeng.com/httpapi/power-pay?userid=$this->ourID&pass=$this->theKey&user_ref=$ref&service=$service&meterno=$meterno&mtype=$type&amt=$amount&jsn=json");
    return $response;
  }



}