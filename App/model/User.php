<?php
namespace model;
use database\DataBase;
use core\Model;
use \PDO;
use \PDOException;
// require_once 'Model.php';
class User{
  private $_userID, $_firstname, $_lastname,$_username,$_email, $_password,$_created_at,$_state, $_activity
  , $_address, $_phone, $_acl = NULL,$_entrycode,$_ref_code, $_accType, $_createdBy, $_suspendedBy, $_suspendedAt;


  private $_table = 'users';
  //private $_db;
  private $model;
  
  public function __construct(){
    $this->model = new Model($this->_table);
  }
  public function _db(){
    return $_db = DataBase::getInstance(); 
  }



  public function setID(int $id){
    $this->_userID = $id;
  }

  public function setFirstName(string $firstname){
    $this->_firstname = $firstname;
  }

  public function setLastName(string $lastname){
    $this->_lastname = $lastname;
  }

  public function setUsername(string $username){
    $this->_username = $username;
  }

  public function setEmail(string $email){
    $this->_email = $email;
  }

  public function setPassword(string $password){
    $this->_password = $password;
  }

  public function setDateTime(string $created_at){
    $this->_created_at = $created_at;
  }

  public function setState(string $state){
    $this->_state = $state;
  }

  public function setAddress(string $address){
    $this->_address = $address;
  }

  public function setActivity(string $activity){
    $this->_activity = $activity;
  }

  public function setPhone(string $phone){
    $this->_phone = $phone;
  }

  public function setAcl($acl){
    $this->_acl = $acl;
  }

  public function setAccType($accType){
    $this->_accType =$accType;
  }

  public function setEntryCode(int $entryCode){
    $this->_entrycode = $entryCode;
  }

  public function setReferalCode(int $refcode){
    $this->_ref_code = $refcode;
  }

  public function setSuspendedBy(string $adminName){
    $this->_suspendedBy = $adminName;
  }

  public function setSuspendedAt(string $suspensionTime){
    $this->_suspendedAt = $suspensionTime;
  }

  public function getUserID(){
    return $this->_userID;
  }

  public function getFirstName(){
    return $this->_firstname;
  }

  public function getLastname(){
    return $this->_lastname;
  }

  public function getUserName(){
    return $this->_username;
  }

  public function getEmail(){
    return $this->_email;
  }

  public function getPassword(){
    return $this->_password;
  }

  public function getDateTime(){
    return $this->_created_at;
  }

  public function getState(){
    return $this->_state;
  } 

  public function getAddress(){
    return $this->_address;
  }
   
  public function getActivity(){
    return $this->_activity;
  }

  public function getPhone(){
    return $this->_phone;
  }

  public function getAcl(){
    return $this->_acl;
  }


  public function getEntryCode(){
    return $this->_entrycode;
  }

  public function getRefcode(){
    return $this->_ref_code;
  }

  public function getAccType(){
    return $this->_accType;
  }

  public function getAccCreator(){
    return $this->_createdBy;
  }

  public function getSuspender(){
    return $this->_suspendedBy;
  }

  public function getSuspensionTime(){
    return $this->_suspendedAt;
  }


  public function setUsers($firstname, $lastname, $username, $email, $password, $created_at, $state, $address, $acl, $phone, $entryCode, $refcode, $accType){
    //print($created_at);
    $this->setFirstName($firstname);
    $this->setLastName($lastname);
    $this->setUserName($username);
    $this->setEmail($email);
    $this->setPassword($password);
    $this->setDateTime($created_at);
    $this->setState($state);
    $this->setAccType($accType);
    $this->setAddress($address);
    $this->setAcl($acl);
    $this->setPhone($phone);
    $this->setEntryCode($entryCode);
    $this->setReferalCode($refcode); 
  }

  public function returnUserAsArray(){
    $userArray = [];
    $userArray['firstname'] = $this->getFirstName();
    $userArray['lastname'] = $this->getLastname();
    $userArray['username'] = $this->getUserName();
    $userArray['pword'] = $this->getPassword();
    $userArray['created_at'] = $this->getDateTime();
    $userArray['state'] = $this->getState();
    $userArray['address'] = $this->getPhone();
    $userArray['entry_code'] = $this->getEntryCode();
    $userArray['refcode'] = $this->getRefcode();
    $userArray['acc_type'] = $this->getAccType();

    return $userArray;
  }

  public function createUser(){
   //dnd("Ike");
    $fields = [
      //'idusers' => $this->getUserID,
      'first_name'=> $this->getFirstName(),
      'lastname'=>$this->getLastname(),
      'username'=>$this->getEmail(),
      'pword'=>$this->getPassword(),
      'created_at'=>$this->_created_at,	
      'state'=>$this->getState(), 	
      'addres'=>$this->getAddress(), 	
      'phone'=>	$this->getPhone(),	
      'acl'=>$this->getAcl(),
      'entry_code' =>$this->getEntryCode(),	
      'ref_code'=>$this->getRefcode(),	
      'acc_type'=>$this->getAccType()
    ];
    // var_dump($fields);
    // die();
    $this->_db()->query("INSERT INTO users WHERE first_name = ? 
    and lastname=? and username=? and  pword=? and created_at=?
     and `state`=? and addres= ? and phone =? and acl=? and entry_code =? and ref_code =? 
     and acc_type =?", $fields);
    
    $this->_db()->insert('users', $fields);
  }

  public function viewUser($userID){
    $details = $this->_db()->findFirst($this->_table,['conditions'=>'id = ?', 'bind'=>[$userID]]);
    return $details;
  }


  public function alterUserState(int $userID, $activity, $admin=null){
   //if admin is the one alterning the state, do this
    if($admin!==null){
      //register under admin history

    }
    //then continue to alter state as specified
    $boolValue = '';
    $fields=['activity'=>$activity];
    $this->model->update($userID, $fields)==true?$boolValue =true:$boolValue = false;
    return $boolValue;
  }

  
  public function editUser(int $userID,array $fields, $admin=null){
    //if admin is the one editing the user, do this
    if($admin!==null){
      //register under admin history

    }
    //then continue to edit user as specified
    $boolValue = '';
    $this->model->update($userID, $fields)==true?$boolValue =true:$boolValue=false;
    return $boolValue;
  }


}