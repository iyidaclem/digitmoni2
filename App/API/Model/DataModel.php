<?php 
namespace API\Model;
use database\DataBase;
use core\Model;
use core\Response;

class DataModel extends Model{
  private $res;

  public function __construct($table){
      parent::__construct($table);
      $this->res = new Response();
  }

  
}