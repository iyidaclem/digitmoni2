<?php
namespace model;

use database\DataBase;
use model\Model;


class Airtime{
  //model variables
  private $_idAirtime, $_username, $_reference, $_network, $_model, $_db, $_table ='airtimes';

  //constructor function 
  public function __construct(){
    $this->_model = new Model($this->_table);
    $this->_db = DataBase::getInstance();
  }

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
    $fields= [ 
      'username'=>$this->getAirtimeUsername(),
      'reference'=>$this->getAirtimeRef(),	
      'network'	=>$this->getAirtimeNetwork()
    ];

    $boolVal = '';
    $this->_model->insert($fields)==true?$boolVal =true:$boolVal=false;
    return $boolVal;
  }


  
}