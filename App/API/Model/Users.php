<?php
namespace API\Model;
use model\User;
use core\Model;

/**
 * [Description Users] The user class is a model extending 
 * the main model class. It serve as a model for the user table. 
 */
class Users extends User{
  private $model;
  public function __construct(){
    $this->model = new Model('users');
  }
  
  /**
   * @param mixed $username this is the logged in user's username. At the point of creating 
   * the users account, you need to create a record for them to store their fund value at any point or during 
   * transactions. That is where this function comes in. 
   * 
   * @return [type]
   */
  public function initializeAccount($username){
    $model = new Model('user_fund');
    $fields =[
      "username"=>$username
    ];
    if($model->insert($fields)) return true;
    return false;
  }


  
}