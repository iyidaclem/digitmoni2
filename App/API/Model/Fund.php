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

  /**
   * This method is called when there is need to update the user account balance e.g 
   * during account funding process and during interest payout. So those two processes will
   * be using or calling this method.
   * 
   * @param mixed $targetUser - this is the username whose account balance needs to be increamented
   * 
   * @param mixed $addedAmt - this is the amount being added.
   * @param mixed $userID - this is the userID of the user which will be used by the update method.
   * 
   * @return bool - the method returns bool value of "true" when the account bal update is successful 
   * and returns false when it false
   */
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