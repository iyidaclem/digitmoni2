<?php

class User{
  private $_userID, $_firstname, $_lastname,$_username,$_email, $_password,$_created_at,$_state, $_activity
  , $_address, $_phone, $_acl = [],$_entrycode,$_ref_code, $_accType, $_createdBy, $_suspendedBy, $_suspendedAt;

  private $_db = DataBase::getInstance();
  private $_table = 'users';
  
  public    $fields = [
    //'idusers' => $this->getUserID,
    'first_name'=> $this->getFirstName(),
    'lastname'=> $this->getLastname(),
    'username'=> $this->getEmail(),
    'password'=>$this->getPassword(),
    'created_at'=>$this->getDateTime(),	
    'state'=>$this->getState(), 	
    'addres'=>$this->getAddress(), 	
    'phone'=>	$this->getAcl(),	
    'entry_code' =>$this->getEntryCode(),	
    'ref_code'=>$this->getRefcode(),	
    'acc_type'=>$this->getAccType()
  ];


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
    $this->_created = $created_at;
  }

  public function setState(string $state){
    $this->_state = $state;
  }

  public function setAddress(string $address){
    $this->_address = $address;
  }

  public function setPhone(string $phone){
    $this->_phone = $phone;
  }

  public function setAcl(array $acl){
    $this->_acl = $acl;
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
    $this->setFirstName($firstname);
    $this->setLastName($lastname);
    $this->setUserName($username);
    $this->setEmail($email);
    $this->setPassword($password);
    $this->setDateTime($created_at);
    $this->setState($state);
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
    $userArray['password'] = $this->getPassword();
    $userArray['created_at'] = $this->getDateTime();
    $userArray['state'] = $this->getState();
    $userArray['address'] = $this->getPhone();
    $userArray['entry_code'] = $this->getEntryCode();
    $userArray['refcode'] = $this->getRefcode();
    $userArray['acc_type'] = $this->getAccType();

    return $userArray;
  }

  public function createUser(){
    // declare variables
    // $firstname = $this->getFirstName();
    // $lastname = $this->getLastname();
    // $username = $this->getUserName();
    // $email = $this->getEmail();
    // $phone = $this->getPhone();
    // $state = $this->getState();
    // $address = $this->getAddress();
    // $created_at = $this->getDateTime();
    // $password = $this->getPassword();
    $fields = $this->field;

    $this->_db->query("INSERT INTO $this->_table WHERE first_name = ? 
    and lastname=? and username=? and  `password`=? and created_at=?
     and `state`=? and `addres`= ? and phone =? and entry_code =? and ref_code =? 
     and acc_type =?", $fields);
    
    $this->_db->insert($this->_table, $fields);
  }

  public function viewUser(int $userID){
    $this->_db->findFirst(['conditions'=>"id = ?", 'bind'=>[$userID]]);
  }

  public function deactivateUser(int $userID){
    $fields = [
      'activity'=>$this->_activity
    ];
    $this->_db->query("UPDATE users SET activity = ? WHERE id = ?", $fields);

    $this->_db->update($this->_table,$userID, $fields);
  }

  
  public function editUser(int $userID){
    $fields = $this->fields;
    $this->_db->query("UPDATE users SET first_name = ? 
    and lastname=? and username=? and  `password`=? and created_at=?
     and `state`=? and `addres`= ? and phone =? and entry_code =? and ref_code =? 
     and acc_type =? WHERE id = ?", $fields);

    $this->_db->update($this->_table,$userID,$fields);
  }


}