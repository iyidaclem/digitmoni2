<?php
namespace model;

use database\DataBase;
use model\Model;

class UserDetail{
 // id	userID	suspended_by	suspended_at	agreed_terms	
  private $_id, $_userID, $_suspendedBy, $_suspendedAt, $_agree2Terms;
  private $_table = 'user_detail', $_model, $_db;
  
  public function __construct(){
    $this->_db = DataBase::getInstance();
    $this->_model = new Model($this->_table);
  }

  public function setID($id){
    $this->_id = $id;
  }

  public function setUserID($userID){
    $this->_userID = $userID;
  }

  public function setSuspender($suspender){
    $this->_suspendedBy = $suspender;
  }

  public function setSuspensionTime($suspensionTime){
    $this->_suspendedAt = $suspensionTime;
  }

  public function setAgree2Terms($agree2Terms){
    $this->_agree2Terms = $agree2Terms;
  }

  public function setUserDetails($id=null, $userID, $suspendedBy, $suspendedAt, $agree2Terms){
    $this->setID($id);
    $this->setUserID($userID);
    $this->setSuspender($suspendedBy);
    $this->setSuspensionTime($suspendedAt);
    $this->setAgree2Terms($agree2Terms);
  }

  public function returnUserDetail(){
     // id	userID	suspended_by	suspended_at	agreed_terms	
    $userDetailArr = [];
    $userDetailArr['id'] = $this->getID();
    $userDetailArr['userID'] = $this->getUserID();
    $userDetailArr['suspendedBy'] = $this->getSuspender();
    $userDetailArr['suspendedAt'] = $this->getSuspensionTime();
    $userDetailArr['agree'] = $this->getAgree2Terms();
    return $userDetailArr;
  }
  //the getters 

  public function getID(){
    return $this->_id;
  }

  public function getUserID(){
    return $this->_userID;
  }

  public function getSuspender(){
    return $this->_suspendedBy;
  }

  public function getSuspensionTime(){
    return $this->_suspendedAt;
  }

  public function getAgree2Terms(){
    return $this->_agree2Terms;
  }
  //

  public function neUserDetail(){
    $fields = [
      'id'=>$this->getID(),	
      'userID'=>$this->getUserID(),	
      'suspended_by'=>$this->getSuspender(),	
      'suspended_at'=>$this->getSuspensionTime(),	
      'agreed_terms'=>$this->getAgree2Terms()
    ];

    $boolbval = '';
    $this->_model->insert($fields)==true?$boolbval=true:$boolbval=false;
    return $boolbval;
  }

  public function addSuspender($userID){
    $fields=[
      'suspended_by'=>$this->getSuspender(),
    ];
    $boolbval = '';
    $this->_model->update($userID, $fields)==true?$boolbval=true:$boolbval=false;
    return $boolbval;
  }

  public function addSuspensionTime($userID){
    $fields=[
      'suspended_At'=>$this->getSuspensionTime(),
    ];
    $boolbval = '';
    $this->_model->update($userID, $fields)==true?$boolbval=true:$boolbval=false;
    return $boolbval;
  }

  public function addAgree2Terms($userID){
    $fields=[
      'agreed_terms'=>$this->getAgree2Terms(),
    ];
    $boolbval = '';
    $this->_model->update($userID, $fields)==true?$boolbval=true:$boolbval=false;
    return $boolbval;
  }





}