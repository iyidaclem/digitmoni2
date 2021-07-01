<?php
namespace core;
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
    $bool = '';
    $this->_db()->insert($this->_table, $fields)==true?$bool=true:$bool=false;
    return $bool;
  }

  public function deleteByUsername($table, $username){
    // $this->_db()->deleteByUsername($table, $username);

    $sql = "DELETE FROM {$table} WHERE username = {$username}";
    if(!$this->_db()->query($sql)->error()) {
      return true;
    }
    return false;
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

  public function findByUsernamePassword($username,$password,$params=[]){
    $conditions = [
      'conditions' => 'username = ? AND password = ?',
      'bind' => [$username, $password]
    ];
    $conditions = array_merge($conditions,$params);
    return $this->_db()->findFirst($this->_table,$conditions);
  }

  public function findByUsernameEmail($username,$email,$params=[]){
    $conditions = [
      'conditions' => 'username = ? AND email = ?',
      'bind' => [$username, $email]
    ];
    $conditions = array_merge($conditions,$params);
    return $this->_db()->findFirst($this->_table,$conditions);
  }

  public function findByUsername($table, $username) {
    return $this->_db()->findFirst($table,['conditions'=>"username = ?", 'bind' => [$username]]);
  } 

  public function findByEmail($table, $email) {
    return $this->_db()->findFirst($table,['conditions'=>"email = ?", 'bind' => [$email]]);
  } 
}