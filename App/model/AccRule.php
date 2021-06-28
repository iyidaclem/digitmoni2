<?php
namespace model;
use database\DataBase;
use model\Model;

class AccountRules{

  private $_id, $_accName, $_refInterest, $_purDiscount;

  private $_table='acc_type_rule', $_db, $_model;
 
  public function __construct(){
    $this->_db = DataBase::getInstance();
    $this->_model = new Model($this->_table);
  }

  public function setID($id){
    $this->_id = $id;
  }

  public function setAccName($accName){
    $this->_accName = $accName;
  }

  public function setAccRefInterest($refInterest){
    $this->_refInterest = $refInterest;
  }

  public function setPurchaseDiscount($purchaseDiscount){
    $this->_purDiscount = $purchaseDiscount;
  }

  //the getters

  public function getID(){
    return $this->_id;
  }

  public function getAccName(){
    return $this->_accName;
  }

  public function getAccRefInterest(){
    return $this->_refInterest;
  }

  public function getPurchaseDiscount(){
    return $this->_purDiscount;
  }

  public function setAccTypeRules($id=null, $accName, $refInterest, $purchaseDiscount){
    $this->setID($id);
    $this->setAccName($accName);
    $this->setAccRefInterest($refInterest);
    $this->setPurchaseDiscount($purchaseDiscount);
  }

  public function returnAccRuleAsArray(){
    //id	acc_name	referral_interest	purchase_disc
    $accTypeRule = [];
    $accTypeRule['id'] = $this->getID();
    $accTypeRule['acc_name'] = $this->getAccName();
    $accTypeRule['referral_interest'] = $this->getAccRefInterest();
    $accTypeRule['purchase_disc'] = $this->getPurchaseDiscount();

    return $accTypeRule;
  }

  public function NewAccTypeAndRules(){
      $fields = [
        'acc_name'=>$this->getAccName(),
        'referral_interest'=>$this->getAccRefInterest(),
        'purchase_disc'=>$this->getPurchaseDiscount()
      ];

      $boolVal = '';
      $this->_model->insert($fields) ==true?$boolVal=true:$boolVal=false;
      return $boolVal;
  }

  public function editAccTypeRules($ID){
    $fields = [
      'acc_name'=>$this->getAccName(),
      'referral_interest'=>$this->getAccRefInterest(),
      'purchase_disc'=>$this->getPurchaseDiscount()
    ];

      $boolVal = '';
      $this->_model->update($ID, $fields) ==true?$boolVal=true:$boolVal=false;
      return $boolVal;
  }

  public function veiwAccTypeRule($accTypeID){
    $details = $this->_db->findFirst($this->_table, ['conditions'=>'id = ?', 'bind'=>[$accTypeID]]);
    return $details;
  }



}