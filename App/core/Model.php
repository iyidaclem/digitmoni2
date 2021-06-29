<?php
namespace model;
use database\DataBase;
//REMEMBER TO TAKE CARE OF THE NAME SPACE

class Model{
 
  private $_table;
 

  public function __construct($table)
  {
    $this->_table = $table;
  }

  public function _db(){
    return $_db = DataBase::getInstance();
  }

  public function update($id, $fields) {
    //require_once 'DataBase.php';
    if(empty($fields) || $id == '') return false;
    return $this->_db()->update($this->_table, $id, $fields);
  }

  public function insert($fields) {
    if(empty($fields)) return false;
    if(array_key_exists('id', $fields)) unset($fields['id']);
    $insert = $this->_db()->insert($this->_table, $fields);
    return $insert;
  }

  public function query($sql, $bind=[]) {
    return $this->_db()->query($sql, $bind);
  }

  public function findByUserIdAndTargetID($username,$targetID,$params=[]){
    $conditions = [
      'conditions' => 'username = ? AND id = ?',
      'bind' => [$username, $targetID]
    ];
    $conditions = array_merge($conditions,$params);
    return $this->_db()->findFirst($this->_table,$conditions);
  }

}