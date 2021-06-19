<?php 
namespace model;

use database\DataBase;

class UserInvestment{
  //declaring model variables
  private $_ID;
  private $_userID;
  //this column isnt in database yet
  private $_username;
  private $_investmentID;
  private $_matures;
  private $_reference;
  private $_createdAt;
  private $_status;
  private $_rollover;

  //declaring database variables 
  private $_writeDB;
  private $_readDB;

  private $_table;

  public function __construct($table)
  {
    $this->_table = $table;
  }

  public function _db(){
    return $_db = DataBase::getInstance(); 
  }
  //writing setter methods
  public function setID(int $ID){

    $this->_ID = $ID;
  }

  public function setUserID(int $userID){
    $this->_userID = $userID;
  }

  public function setUsername(string $username){
    $this->_username = $username;
  }

  public function setInvestmentID(int $investmentID){
    $this->_investmentID = $investmentID;
  }

  public function setDateTime($dateTime){
    $this->_createdAt = $dateTime;
  }

  public function setMaturity(int $matures){
    $this->_matures = $matures;
  }

  public function setInvReference(string $reference){
    $this->_reference = $reference;
  }

  public function setStatus(string $status){
    $this->_status = $status;
  }

  public function setRollover(string $rollover){
    $this->_rollover = $rollover;
  }

  //writing the getter methods

  public function getID(){
    return $this->_ID;
  }

  public function getUserID(){
    return $this->_userID;
  }

  public function getInvestmentID(){
    return $this->_investmentID;
  }

  public function getUsername(){
    return $this->_username;
  }

  public function getDateTime(){
    return $this->_createdAt;
  }

  public function getReference(){
    return $this->_reference;
  }

  public function getMaturity(){
    return $this->_matures;
  }

  public function getStatus(){
    return $this->_status;
  }

  public function getRollover(){
    return $this->_rollover;
  }

  //Now writing the models core methods

  public function setUSerInvestment($ID, $userID,$username, $investmentID, $matures, $createdAt, $status, $reference){
    $this->setUsername($username);
    $this->setUserID($userID);
    $this->setInvestmentID($investmentID);
    $this->setMaturity($matures);
    $this->setDateTime($createdAt);
    $this->setStatus($status);
    $this->setInvReference($reference);
  }


  //method when called and supplied with all needed arguement creates new investment for the user
  public function newUserInvestment(){
    $fields = [
      	// investmendID	userID	created_at	matures_at	inv_reference	status	rollover	
      //'id' => $this->getID(),
      'investmendID' => $this->getInvestmentID(),
      //this column isnt in database yet
       'username' => $this->getUserID(),
       'created_at' => $this->getDateTime(),
       'matures_at' => $this->getMaturity(),
       'inv_reference'=>$this->getReference(),
       'status'=>$this->getStatus(),
       'rollover'=>$this->getRollover()
    ];
    // var_dump($fields);
    // die();
    $this->_db()->query("INSERT INTO users WHERE investmendID = ? 
    and username=? and created_at=? and  matures_at=? and inv_reference=?
     and `status`=? and rollover= ?", $fields);
    
    $this->_db()->insert('users', $fields);
  }

  public function viewUserInvestment($userID){
    $details = $this->_db()->findFirst($this->_table,['conditions'=>'id = ?', 'bind'=>[$userID]]);
    return $details;
  }

  public function cancelUserInvestment(){
    require_once 'Model.php';
    $fields=['status'=>'deactivated'];
    $model = new Model($this->_table);
    $model->update($userID, $fields);
  }

  public function editUserInvestment($userID){
    $fields = $this->fields;
    $this->_db()->query("UPDATE users SET investmendID = ? 
    and username=? and created_at=? and  matures_at=? and inv_reference=?
     and `status`=? and rollover= ?", $fields);

    $this->_db()->update($this->_table,$userID,$fields);
  }

  public function runningUserInvestments(){

  }

  

}