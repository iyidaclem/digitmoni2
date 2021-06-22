<?php
namespace model;

use database\DataBase;
use model\Model;
use model\Transactions;

class TV{
  //declaring model variables
  private $_TVID, $_userID, $_bouquet, $_cardNo, $_reference;
  
  //declaring database variables
  private $_table, $_db, $_model;


  //contructor method
  public function __construct(string $table){
    $this->_table = $table;
    $this->_model = new Model($this->_table);
    $this->_db = DataBase::getInstance();
  }

  //the setters
  public function setTvID(string $tvID){
    $this->_TVID = $tvID;
  }

  public function setTvUserID(string $userID){
    $this->_userID = $userID;
  }

  public function setBouquet(string $bouquet){
    $this->_bouquet = $bouquet;
  }

  public function setReference(string $reference){
    $this->_reference = $reference;
  }

  public function setTvCardNo(string $cardNo){
    $this->_cardNo = $cardNo;
  }

  //writing the getters

  public function getTvID(){
    return $this->_TVID;
  }

  public function getTvUserID(){
    return $this->_userID;
  }

  public function getTvBouquet(){
    return $this->_bouquet;
  }

  public function getTvReference(){
    return $this->_reference;
  }

  public function getTvCardNo(){
    return $this->_cardNo;
  }

  //main model methods
  public function newTV(){
    $field = [
      'username'=>$this->getTvUserID(),
      'bouquet'=>$this->getTvBouquet(),	
      'card_no'=>$this->getTvCardNo(),	
      'reference'=>$this->getTvReference()	
    ];
    $value = '';
    $this->_model->insert($field)==true?$value=true:$value=false;
    return $value;
  }

  public function setStatus($reference, $trxStatus){
    $boolVal = '';
    $fields = ['status'=>$trxStatus];
    $this->_model->update($reference, $fields)==true?$boolVal=true:$boolVal=false;
    return $boolVal;
  }

  public function viewTVtrxtn(){
    
  }


}