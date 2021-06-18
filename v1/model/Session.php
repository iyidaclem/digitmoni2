<?php 
class Session{
  private $_sessionID;
  private $_userID;
  private $_username;
  private $_token;
  private $_tokenExp;
  private $_userAgent;

  private $_table = 'session_tb';
  private $_db = DataBase::getInstance();

  private $fields = [
    'username'=>$this->getSessionUsername(),
    'access_token'=>$this->getSessionToken(), 	
    'user_agent'=>$this->getSessionUserAgent(),	
    'token_exp'=>$this->getSessionTokenExp()
  ];

  private $_writeDB;
  private $_readDB;
  
  public function __construct($writeDB, $readDB){
    $this->_writeDB = $writeDB;
    $this->_readDB = $readDB;  
  }

  public function setSessionID(int $sessionID){
    $this->_sessionID = $sessionID;
  }

  public function setSessionUserID(int $userID){
    $this->_userID = $userID;
  }

  public function setSessionToken(string $token){
    $this->_token = $token;
  }

  public function setSessionTokenExpiry(string $expiry){
    $this->_expiry = $expiry;
  }

  public function setSessionUserAgent(string $userAgent){
    $this->_userAgent = $userAgent;
  } 


  public function getSessionID(){
    return $this->_sessionID;
  }

  public function getSessionUserID(){
    return $this->_userID;
  }

  public function getSessionUsername(){
    return $this->_username;
  }

  public function getSessionToken(){
    return $this->_token;
  }

  public function getSessionTokenExp(){
    return $this->_tokenExp;
  }

  public function getSessionUserAgent(){
    return $this->_userAgent;
  }



  public function loginUser($username, $password){
    //Fields to to be inserted
    $fields = $this->fields;
    //check if a user with this details
    $userExists = $this->_db->findByUsernameAndPassword($username, $password);
    if(!$userExists){
      $response = new Response();
      $response->SendResponse(400, false, 'There is no user with this username and password.');
    }
    //if user exists, login 
    $this->_db->query("INSERT INTER session_tb WHERE username=? 
    and access_token=? and user_agent=? and token_exp=?", $fields);
    $this->_db->insert('session_tb', $fields);
  }

  public function logoutUser($userID){
    $fields = $this->fields;
    $this->_db->delete('session_tb', $$userID);
  }
  
  public function currentUser($userID){
    $user = $this->_db->findById($userID);
    return $user['username'];
  }


}