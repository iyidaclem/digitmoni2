<?php
namespace model;
use database\DataBase;
use model\Model;

class AccountRules{

  private $_id, $_accName, $ref_interest, $_purDiscount;

  private $_table='acc_type_rule', $_db, $_model;
 
  public function __construct(){
    $this->_db = DataBase::getInstance();
    $this->_model = new Model($this->_table);
  }

  
}