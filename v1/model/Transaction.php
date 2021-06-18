<?php 

class Transactions{
 //declaring Transaction Model variables
  private $_trxID;
  private $_userID;
  private $_username;
  private $_email;
  private $_amount;
  private $_phone;
  private $_reference;
  private $_purpose;
  private $_trans_date;

  //declaring database variables.
  private $_readDB;
  private $_writeDB;

  public function __construct($writeDB, $readDB){
    $this->_readDB = $readDB;
    $this->_writeDB = $readDB;
  }

  //writing the setter methods
  public function setTrxID(int $trxID){
    $this->_trxID = $trxID;
  }

  public function setTrxUserID(int $userID){
    $this->_userID = $userID;
  }

  public function setTrxUsername(string $username){
    $this->_username = $username;
  }

  public function setTrxEmail(string $email){
    $this->_email = $email;
  }

  public function setTrxAmount(float $amount){
    $this->_amount = $amount;
  }

  public function setTrxPhone(string $phone){
    $this->_phone = $phone;
  }

  public function setTrxReference(string $reference){
    $this->_reference = $reference;
  }

  public function setTrxPurpose(string $reference){
    $this->_purpose = $reference;
  }

  public function setTrxDateTime(string $dateTime){
    $this->_trans_date = $dateTime;
  }


  //writing the getter functions 

  public function getTrxID(){
    return $this->_trxID;
  }

  public function getTrxUserID(){
    return $this->_userID;
  }

  public function getTrxUsername(){
    return $this->_username;
  }

  public function getTrxEmail(){
    return $this->_email;
  }

  public function getTrxPhone(){
    return $this->_phone;
  }

  public function getTrxAmount(){
    return $this->_amount;
  }

  public function getTrxReference(){
    return $this->_reference;
  }

  public function getTrxPurpose(){
    return $this->_purpose;
  }

  public function getTrxDateTime(){
    return $this->_trans_date;
  }

  //Now writing actual methods

  public function inserTrx(){
    //everybody features
  }

  public function viewTrx(){
    //evrybody features
  }

  public function listTrx(){
    //everybody features
  }


}