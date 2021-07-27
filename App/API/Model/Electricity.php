<?php 
namespace API\Model;

use core\Model;
use core\Response;

class Electricity extends Model{
  private $model, $resp;
  public function __construct($table){
    parent::__construct($table);
    $this->res = new Response();
}


}