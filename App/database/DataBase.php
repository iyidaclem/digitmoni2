<?php 
namespace database;
use \PDO;
use \PDOException;

class DataBase{
  private static $_instance = null;
  private  $_query, $_error = false, $_result, $_count = 0, $_lastInsertID = null;
  public $_pdo;
  public function __construct() {
    try {
      $this->_pdo = new PDO('mysql:host=127.0.0.1;dbname=digitmoni_app;charset=utf8', 'root', '');
      $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch(PDOException $e) {
      die($e->getMessage());
    }
  }


  public static function getInstance(){
    if(!isset(self::$_instance)){
      self::$_instance = new Self();
    }
    return self::$_instance;
  }

  public function query($sql, $params = [],$class = false) {
    $this->_error = false;
    if($this->_query = $this->_pdo->prepare($sql)) {
      $x = 1;
      if(count($params)) {
        foreach($params as $param) {
          $this->_query->bindValue($x, $param);
          $x++;
        }
      }
      if($this->_query->execute()) {
        if($class){
          $this->_result = $this->_query->fetchAll(PDO::FETCH_CLASS,$class);
        } else {
          $this->_result = $this->_query->fetchALL(PDO::FETCH_OBJ);
        }
        $this->_count = $this->_query->rowCount();
        $this->_lastInsertID = $this->_pdo->lastInsertId();
      } else {
        $this->_error = true;
      }
    }
    return $this;
  }

  public function insert($table, $fields = []) {
    $fieldString = '';
    $valueString = '';
    $values = [];

    foreach($fields as $field => $value) {
      $fieldString .= '`' . $field . '`,';
      $valueString .= '?,';
      $values[] = $value;
    }
    $fieldString = rtrim($fieldString, ',');
    $valueString = rtrim($valueString, ',');
    $sql = "INSERT INTO {$table} ({$fieldString}) VALUES ({$valueString})";
    if(!$this->query($sql, $values)->error()) {
      return true;
    }
    return false;
  }


  protected function _read($table, $params=[],$class) {
    $conditionString = '';
    $bind = [];
    $order = '';
    $limit = '';

    // conditions
    if(isset($params['conditions'])) {
      if(is_array($params['conditions'])) {
        foreach($params['conditions'] as $condition) {
          $conditionString .= ' ' . $condition . ' AND';
        }
        $conditionString = trim($conditionString);
        $conditionString = rtrim($conditionString, ' AND');
      } else {
        $conditionString = $params['conditions'];
      }
      if($conditionString != '') {
        $conditionString = ' Where ' . $conditionString;
      }
    }

    // bind
    if(array_key_exists('bind', $params)) {
      $bind = $params['bind'];
    }

    // order
    if(array_key_exists('order', $params)) {
      $order = ' ORDER BY ' . $params['order'];
    }

    // limit
    if(array_key_exists('limit', $params)) {
      $limit = ' LIMIT ' . $params['limit'];
    }
    $sql = "SELECT * FROM {$table}{$conditionString}{$order}{$limit}";
    if($this->query($sql, $bind,$class)) {
      if(!count($this->_result)) return false;
      return true;
    }
    return false;
  }

  public function find($table, $params=[], $class=false){
    if($this->_read($table, $params, $class)){
      return $this->results();
    }
    return false;
  }

  public function findFirst($table, $params=[], $class=false){
    if($this->_read($table, $params,$class)) {
      return $this->first();
    }
    return false;
  }

  public function findByUserIdAndPassword($username,$hashedPassword,$params=[]){
    $conditions = [
      'conditions' => 'username = ? AND password = ?',
      'bind' => [$username, $hashedPassword]
    ];
    $conditions = array_merge($conditions,$params);
    return $this->findFirst($conditions);
  }

  public function update($table, $id, $fields = []) {
    $fieldString = '';
    $values = [];
    foreach($fields as $field => $value) {
      $fieldString .= ' ' . $field . ' = ?,';
      $values[] = $value;
    }
    $fieldString = trim($fieldString);
    $fieldString = rtrim($fieldString, ',');
    $sql = "UPDATE {$table} SET {$fieldString} WHERE id = {$id}";
    if(!$this->query($sql, $values)->error()) {
      return true;
    }
    return false;
  }

  public function delete($table, $id) {
    $sql = "DELETE FROM {$table} WHERE id = {$id}";
    if(!$this->query($sql)->error()) {
      return true;
    }
    return false;
  }

  public function deleteByUser($table, $username) {
    $sql = "DELETE FROM {$table} WHERE username = {$username}";
    if(!$this->query($sql)->error()) {
      return true;
    }
    return false;
  }



  public function first() {
    return (!empty($this->_result))? $this->_result[0] : [];
  }

  public function results(){
    return $this->_result;
  }

  public function count(){
    return $this->_count;
  }

  public function error(){
    return $this->_error;
  }

  public function get_columns($table) {
    return $this->query("SHOW COLUMNS FROM {$table}")->results();
  }

  public function findById($id) {
    return $this->findFirst(['conditions'=>"id = ?", 'bind' => [$id]]);
  }

  public function findByUsername($table, $username) {
    return $this->findFirst($table,['conditions'=>"username = ?", 'bind' => [$username]]);
  } 
  

}