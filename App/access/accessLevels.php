<?php 

class Access{
  private $_db;
  private $_requestMethod;
  private $_accessToken;

  public function __construct($db, $requestMethod)
  {
    $this->_db = $db;
    $this->_requestMethod = $requestMethod;

    if($_SERVER['REQUEST_METHOD'] !== $requestMethod){
      $response = new Response();
      $response->SendResponse(404, false, 'Page not found.');
    }
    $this->_accessToken = $_SERVER['HTTP_AUTHORIZATION'];
  }

  
  //Begin Authentication
  public function userAuth(){
    try{
      //Handling Database query
      $query = $this->_db->prepare("SELECT first_name, lastname, username, acc_type, acl
      id from users, session_tb where users.username = session_tb.username 
      and access_token=:token");

      //Bind parameters
      $query->bindParam(':token', $this->_accessToken, PDO::PARAM_STR);
      $query->execute();

      //check if user exists and is logged in with given access token
      $rowCount = $query->rowCount();
      if($rowCount ===0){
        $response = new Response();
        $response->SendResponse(400, false, 'Invalid Access Token.');
      }

      $row = $query->fetch(PDO::FETCH_ASSOC);
      //Somewhere here the ACL needs to be processed into an array or something. 
      
      $returned_userID = $row['idusers'];
      $returned_Firstname = $row['first_name'];
      $returned_Lastname = $row['lastname'];
      $returned_ACL[] = $row['acl'];
      $returned_username = $row['username'];
      $returned_accType = $row['acc_type'];
      $returned_SessionID = $row['id'];

      return array($returned_userID, $returned_username,$returned_SessionID,$returned_Firstname, $returned_Lastname, $returned_accType);

    }catch(PDOException $err){
      $response = new Response();
      $response->SendResponse(500, false, 'There is a problem. ' .$err->getMessage());
    }
  }

  public function partnerAuth(){

  }

  public function HLAdminAuth(){

  }

  public function utilityAdminAuth(){

  }

  public function investmentAdminAuth(){

  }

}