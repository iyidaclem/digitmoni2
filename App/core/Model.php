<?php
namespace core;
use database\DataBase;
//REMEMBER TO TAKE CARE OF THE NAME SPACE

class Model{
 
  public $_table;
 

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

  public function find($params = []) {
   // $params = $this->_softDeleteParams($params);
    $resultsQuery = $this->_db()->find($this->_table, $params);
    if(!$resultsQuery) return [];
    return $resultsQuery;
  }

  public function findByUserIdAndTargetID($username,$targetID,$params=[]){
    $conditions = [
      'conditions' => 'username = ? AND id = ?',
      'bind' => [$username, $targetID]
    ];
    $conditions = array_merge($conditions,$params);
    return $this->_db()->findFirst($this->_table,$conditions);
  }

  public function findFirst($params = []) {
   // $params = $this->_softDeleteParams($params);
    $resultQuery = $this->_db()->findFirst($this->_table, $params);
    return $resultQuery;
  }

  public function findByUsernamePassword($username,$password,$params=[]){
    $conditions = [
      'conditions' => 'username = ? AND pword = ?',
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
  public function findByToken($table, $token) {
    return $this->_db()->findFirst($table,['conditions'=>"access_token = ?", 'bind' => [$token]]);
  } 

  public function findByEmail($table, $email) {
    return $this->_db()->findFirst($table,['conditions'=>"email = ?", 'bind' => [$email]]);
  } 

  public function findByState($table, $state) {
    return $this->_db()->findFirst($table,['conditions'=>"state = ?", 'bind' => [$state]]);
  } 

  
  public function findByMd5Password($table, $md5Password) {
    return $this->_db()->findFirst($table,['conditions'=>"password = ?", 'bind' => [$md5Password]]);
  } 
}