<?php
namespace core\logger;

use core\Model;
use core\Response;

class Logger{
  private $who, $did, $what, $at, $page, $priority, $user_agent, $table='logger', $model;

  public function __construct(){
    $this->model = new Model($this->table);
  }

  public function log($who, $did, $what, $page, $priority,$user_agent){
    $logFields =[
      'who'=>$who,
      'did'=>$did,
      'what'=>$what,
      'page'=>$page, 
      'priority'=>$priority,
      'user_agent'=>$user_agent
    ];
    if(!$this->model->insert($logFields)) return false;
    return true;    
  }

  public function retrieveLog($username){
    $getLog = $this->model->find([]);
    if(!$getLog) return false;
    return $getLog;
  }

  
}