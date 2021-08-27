<?php
namespace API\Model;
use model\User;
use core\Model;
use database\DB;
use database\DataBase;
/**
 * [Description Users] The user class is a model extending 
 * the main model class. It serve as a model for the user table. 
 */
class Users extends User{
  private $model, $writeDB, $readDB, $db;
  public function __construct(){
    $this->model = new Model('users');
    $this->readDB = DB::connectReadDB();
    $this->writeDB = DB::connectWriteDB();
    $this->db = new DataBase();
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

  public function referralChecker($refcode){
    $referrer = $this->db->findFirst('users',['conditions'=>"ref_code = ?", 'bind' => [$refcode]]);
    if(!$referrer) return false;
    return true;    
  }

  public function getReferrer($refcode){
    $referrer = $this->db->findFirst('users',['conditions'=>"ref_code = ?", 'bind' => [$refcode]]);
    $username = $referrer->username;
    return $username;
  }

}