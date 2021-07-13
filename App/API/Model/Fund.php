<?php 
namespace API\Model;
use database\DataBase;
use core\Model;


class Fund extends Model{

  public function __construct($table){
      parent::__construct($table);
  }
  
  public function UserAccountNo($targetUser){
    $UserAccDetails = $this->findFirst([
      'conditions' => 'username = ?','bind' => [$targetUser]
    ]);
    $accNo = $UserAccDetails->acc_no;
    return $accNo;
  }

  public function UserAaccBalance($targetUser){
    $UserAccDetails = $this->findFirst([
      'conditions' => 'username = ?','bind' => [$targetUser]
    ]);
    $accBal = $UserAccDetails->balance;
  }

  public function UserAcc($targetUser){
    return $UserAccDetails = $this->findFirst([
      'conditions' => 'username = ?','bind' => [$targetUser]
    ]);
  }
  


}