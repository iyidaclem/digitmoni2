<?php
namespace model;
use database\DataBase;


class Model{
 
  private $_table;
 

  public function __construct($table)
  {
    $this->_table = $table;
  }

  public function _db(){
    return $_db = new DataBase();
  }

  public function update($id, $fields) {
    require_once 'DataBase.php';
    if(empty($fields) || $id == '') return false;
    return $this->_db()->update($this->_table, $id, $fields);
  }

  public function insert($fields) {
    if(empty($fields)) return false;
    if(array_key_exists('id', $fields)) unset($fields['id']);
    return $this->_db->insert($this->_table, $fields);
  }

  public function query($sql, $bind=[]) {
    return $this->_db->query($sql, $bind);
  }

}