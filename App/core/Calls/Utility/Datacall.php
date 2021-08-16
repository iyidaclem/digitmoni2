<?php 
namespace core\Call\Utility;

use core\Call\CallKeyFromDB;
use Egulias\EmailValidator\Result\Result;
use Requests;

class Datacall{

  private $httpRequest;
  private $theKey;
  private $ourID;
  public function __construct(){
    $callCredentials = new CallKeyFromDB();
    $cred = $callCredentials->getUtilitykeyFromDB();
    $this->theKey = $cred[0];
    $this->ourID = $cred[1];
  }

  public function dataTopUpCall($amount, $network, $phone){
    $response = Requests::get("https://mobileairtimeng.com/httpapi/datatopup.php?userid=$this->ourID&pass=$this->theKey&network=$network&phone=$phone&amt=$amount");
    return $response;
  }

  public function dataOptionsCall($network){
    $response = Requests::get("https://mobileairtimeng.com/httpapi/get-items?userid=xxx&pass=xxx&service=$network");
    return $response;
  }

}