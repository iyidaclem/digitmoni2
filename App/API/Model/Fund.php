<?php 
namespace API\Model;
use database\DataBase;
use core\Model;
use core\Response;

class Fund extends Model{
  private $res;

  public function __construct($table){
      parent::__construct($table);
      $this->res = new Response();
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
    return $accBal;
  }

  public function UserAcc($targetUser){
    return $UserAccDetails = $this->findFirst([
      'conditions' => 'username = ?','bind' => [$targetUser]
    ]);
  }

  public function updateUserAccountBalance($targetUser, $addedAmt, $userID){
    //get user's accout balance
    $accBal = intval($this->UserAaccBalance($targetUser));
    //add it to the amount funded
    $newBal = $accBal + $addedAmt;
    //update back the user bal
    $newBalFields=[
      'balance'=>$newBal
    ];
    if(!$this->update($userID, $newBalFields)) //LOG ERROR ACTION
    return false;
    return true;
  }
  


}