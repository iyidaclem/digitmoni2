<?php 

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

  public function getUsername(){
    return $this->_username;
  }

  public function getDateTime(){
    return $this->_createdAt;
  }

  public function getReference(){
    return $this->_reference;
  }

  public function getStatus(){
    return $this->_status;
  }

  public function getRollover(){
    return $this->_rollover;
  }

  //Now writing the models core methods

  //method when called and supplied with all needed arguement creates new investment for the user
  public function newUserInvestment(){

  }

  public function viewUserInvestment(){

  }

  public function cancelUserInvestment(){

  }

  public function editUserInvestment(){

  }

  public function runningUserInvestments(){

  }

  

}