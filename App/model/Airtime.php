<?php

class Airtime{
  //model variables
  private $_idAirtime;
  private $_username;
  private $_reference;
  private $_network;

  //database variables
  private $_writeDB;
  private $_readDB;

  //writing the setter methods
  public function setAirtimeID(int $AirTimeID){
    $this->_idAirtime = $AirTimeID;
  }

  public function setAirtimeUsername(string $username){
    $this->_username = $username;
  }

  public function setAirtimeRef(string $reference){
    $this->_reference = $reference;
  }

  public function setAirtimeNetwork(string $network){
    $this->_network = $network;
  }

  //writing 
  public function getAirtimeID(){
    return $this->_idAirtime;
  }

  public function getAirtimeUsername(){
    return $this->_username;
  }

  public function getAirtimeNetwork(){
    return $this->_network;
  }

  public function getAirtimeRef(){
    return $this->_reference;
  }

  public function newAirtime(){

  }

  
}