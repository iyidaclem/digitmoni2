<?php 
class Investment{
  private $_investmentID;
  private $_option_name;
  private $_state;
  private $_flex_int;
  private $_fixed_int;
  private $_minAmount;
  private $_maxAmount;
  private $_cancel_cost;
  // column not created yet
  private $_createdAt;
  private $_createdBy;

  public function setInvestmentID(int $InvID){
    $this->_investmentID = $InvID;
  }

  public function setInvestmentName(string $optionName){
    $this->_option_name = $optionName;
  }

  public function setSate(string $state){
    $this->_state = $state;
  }

  public function setFlexInterest(int $flexInterest){
    $this->_flex_int = $flexInterest;
  }

  public function setFixedInterest(int $fixedInterest){
    $this->_fixed_int = $fixedInterest;
  }

  public function setMinAmount(float $minAmount){
    $this->_minAmount = $minAmount;
  }

  public function setMaxAmount(float $maxAmount){
    $this->_maxAmount = $maxAmount;
  }

  public function SetcancelCost(float $cancelCost){
    $this->_cancel_cost = $cancelCost;
  }

  public function setCreator(string $AdminName){
    $this->_createdBy = $AdminName;
  }

  public function setDateTime(string $creationDate){
    $this->_createdAt = $creationDate;
  }

  public function createPackage(){
    //Investment and super Admin feature

  } 
  
  public function editPackage(){
    //Investment and super Admin feature
  }

  public function stopPackage(){
    //Investment and super Admin feature
  }

  public function viewPackage(){
    //User, Investment and super Admin feature
  }

  public function packageInterestOverTime(){
    //user features
  }

}
