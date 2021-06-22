<?php
namespace model;
use database\DataBase;
use model\Model;

class Transactions{
 //declaring Transaction Model variables
  private $_trxID, $_userID, $_username, $_email, $_amount, $_phone, $_reference, $_purpose, $_trans_date;
  private $_table ='transactions', $_db, $_model;
  //declaring database variables.


  public function __construct(){
    $this->_model = new Model($this->_table);
    $this->_db = DataBase::getInstance();
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

  public function setTrx(){

  }

  public function inserTrx(){
    //everybody features
    $fields =[
      'username'=>$this->getTrxUserID(),	
      'email'=>$this->getTrxEmail(),	
      'amount'=>$this->getTrxAmount(),	
      'phone'=>$this->getTrxPhone(),	
      'reference'=>$this->getTrxReference(),	
      'purpose'	=>$this->getTrxPurpose(),
      'date_time'=>$this->getTrxDateTime()	
    ];

    $boolvalue = '';
    $this->_model->insert($fields)==true?$boolvalue=true:$boolvalue=false;
    return $boolvalue;
  }

  public function setStatus(){
    
  }

  public function viewTrx($userID, $trxID){
    //evrybody features
    // $details = $this->_db->findFirst($this->_table, ['conditions'=>'id = ?', 'bind'=>[$trxID]]);
    // return $details;
    $details = $this->_model->findByUserIdAndTargetID($userID, $trxID);
    return $details;
  }

  public function listTrx(){
    //everybody features
  }


}