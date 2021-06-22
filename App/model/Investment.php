<?php 
namespace model;

use database\DataBase;
use model\Model;

class Investment{
  private $_investmentID, $_option_name, $_state, $_flex_int, $_fixed_int, $_minAmount, $_maxAmount,$_minDuration, $_cancel_cost;
  // column not created yet $_createdAt; $_createdBy;

  private $_table = 'investments';
  private $_model;
  private $_db;


  public function __construct(){
    $this->_model = new Model($this->_table);
    $this->_db = DataBase::getInstance();
  }

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

  public function getInvestmentID(){
    return $this->_investmentID;
  }

  public function getInvestmentName(){
    return $this->_option_name;
  }

  public function getState(){
    return $this->_state;
  }

  public function getFlexInt(){
    return $this->_flex_int;
  }

  public function getFixedInt(){
    return $this->_fixed_int;
  }

  public function getMinAmount(){
    return $this->_minAmount;
  }

  public function getMaxAmount(){
    return $this->_maxAmount;
  }

  public function getMinDuration(){
    return $this->_minDuration;
  }
  
  public function getCancelCost(){
    return $this->_cancel_cost;
  }

  public function createPackage(){
    //Investment and super Admin feature
    $fields =[
      'option_name'=>$this->getInvestmentName(),	
      'state'=>$this->getState(),	
      'flex_int'=>$this->getFlexInt(),
      'fixed_int'=>$this->getFixedInt(),	
      'min_amt'=>$this->getMinAmount(),	
      'max_amt'=>$this->getMaxAmount(),	
      'min_duration'=>$this->getMinDuration(),	
      'cancel_cost'=>$this->getCancelCost()
    ];
    $boolValue = '';
    $this->_model->insert($fields) ==true?$boolValue=true:$boolValue=false;
    return $boolValue;
  } 
  
  public function editPackage($invesmentID, $admin =null){
    //Investment and super Admin feature
     //Investment and super Admin feature
     $fields =[
      'option_name'=>$this->getInvestmentName(),	
      'state'=>$this->getState(),	
      'flex_int'=>$this->getFlexInt(),
      'fixed_int'=>$this->getFixedInt(),	
      'min_amt'=>$this->getMinAmount(),	
      'max_amt'=>$this->getMaxAmount(),	
      'min_duration'=>$this->getMinDuration(),	
      'cancel_cost'=>$this->getCancelCost()
    ];
    $boolValue = '';
    $this->_model->update($invesmentID, $fields)==true?$boolValue=true:$boolValue=false;
    return $boolValue;
  }

  public function stopPackage($invesmentID, $admin=null){
    //Investment and super Admin feature
    if($admin !== null){
      //add action to admin history
    }
    $boolValue = '';
    $fields = ['state'=>'disable'];
    $this->_model->update($invesmentID, $fields)==true?$boolValue=true:$boolValue=false;
    return $boolValue;
  }

  public function viewPackage($invesmentID){
    //User, Investment and super Admin feature
    $details = $this->_db->findFirst($this->_table, ['conditions'=>'id = ?', 'bind'=>[$invesmentID]]);
    return $details;
  }

  public function packageInterestOverTime($months){
    //user features
    
  }

}
