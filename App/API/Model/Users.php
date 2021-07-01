<?php
namespace API\Model;
use model\User;
use core\Model;

class Users extends User{
  
  
  public function initializeAccount($username){
    $model = new Model('user_fund');
    $fields =[
      "username"=>$username
    ];
    if($model->insert($fields)) return true;
    return false;
  }

  
}