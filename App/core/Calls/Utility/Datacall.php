<?php 
namespace core\Call\Utility;
use core\Call\CallKeyFromDB;

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

}