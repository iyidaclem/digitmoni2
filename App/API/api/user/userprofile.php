<?php
//loading models
require_once '../../bootstrap.php';

//VALIDATION NEED TO HAPPEN AROUND HERE

//check key 
$userID = 1;


try{

  $user = new User();
  $userData = $user->viewUser($userID);

  

}catch(PDOException $err){
  $response = new Response();
  $response->SendResponse(500, false, 'There seems to be a problem. We are on it. '.$err->getMessage());
}