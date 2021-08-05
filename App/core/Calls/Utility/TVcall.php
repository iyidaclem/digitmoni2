<?php 
namespace core\Call\Utility;
use Requests;
use core\Call\CallKeyFromDB;

class TVcalls{

  
  private $httpRequest;
  private $theKey;
  private $ourID;
  public function __construct(){
    $callCredentials = new CallKeyFromDB();
    $cred = $callCredentials->getAPIkeyFromDB();
    $this->theKey = $cred[0];
    $this->ourID = $cred[1];
  }


  public function validateTVUser($bill, $cardNo){
    $response = Requests::get("https://mobileairtimeng.com/httpapi/customercheck?userid=$this->ourID&pass=$this->theKey&bill=$bill&smartno=$cardNo");
    return $response;
  }
  
 public function gotv_dstvRecharge($phone, $smartcardNo,$amount, $customer, $invoice, $billtype, $customerNumber){
   $response = Requests::get("https://mobileairtimeng.com/httpapi/multichoice?userid=$this->ourID&pass=$this->theKey&phone=$phone&amt=$amount&smartno=$smartcardNo&customer=$customer&invoice=$invoice&billtype=$billtype&customernumber=$customerNumber");
   return $response;
 }

 public function starTimeRecharge($ourID, $pass, $amount, $phone, $smartcardNo, $ref){
  $response = Requests::get("https://mobileairtimeng.com/httpapi/startimes?$ourID=xxx&$pass=xxx&phone=$phone&amt=$amount&smartno=$smartcardNo&user_ref=$ref");
  return $response;
 }
}