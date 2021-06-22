<?php
namespace model;

use database\DataBase;
use model\Model;
use model\Transactions;

class TV extends Transactions{
  //declaring model variables
  private $_TVID;
  private $_userID;
  private $_bouquet;
  private $_cardNo;
  private $_reference;
  
  //declaring database variables
  private $_table;


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
  public function newTVtrxtn(){

  }

  public function viewTVtrxtn(){
    
  }


}