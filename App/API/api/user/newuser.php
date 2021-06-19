<?php

require_once '../../bootstrap.php';

//instantiating some validation clasess
$emailValidate = new EmailValidation();
$maxValidation = new ValidateMaximum(10);
$minValidation = new ValidateMinimum(6);
$specialChar = new ValidateSpecialChars();
$noSpaceValidate = new ValidateNoEmptySpace();


//making sure that the request method is POST
if($_SERVER['REQUEST_METHOD'] !== 'POST'){

  $response = new Response();
  $response->SendResponse(404, false, 'Page not found.');
}

//Making sure the content type is json
content_type();

//getting the POSTed data
$rawPostData = file_get_contents('php://input');
$jsonData = checkValidJson($rawPostData);

//checking if the POSTed data contain the right fields

if(!isset($jsonData->firstname) || !isset($jsonData->lastname) || !isset($jsonData->username) || 
  !isset($jsonData->email) || !isset($jsonData->phone) || !isset($jsonData->address) || !isset($jsonData->state)|| !isset($jsonData->acl)){
    
    $msg = array();
  (!isset($jsonData->firstname) ? $msg['firstname'] = 'First name was not supplied.': false);
  (!isset($jsonData->lastname) ? $msg['lastname'] = 'Lastname name was not supplied.': false);
  (!isset($jsonData->username) ? $msg['username'] = 'Usernname was not supplied.': false);
  (!isset($jsonData->email) ? $msg['email'] = 'Email was not supplied.': false);
  (!isset($jsonData->phone) ? $msg['phone'] = 'Phone number was not supplied.': false);
  (!isset($jsonData->Address) ? $msg['Address'] = 'Address was not supplied.': false);
  (!isset($jsonData->state) ? $msg['state'] = 'State was not supplied.': false);
    
  $response = new Response();
  $response->SendResponse(400, false, $msg);
}

//checking if any of the supplied fields is empty
if(strlen($jsonData->firstname)<1 || strlen($jsonData->lastname) <1 || strlen($jsonData->username)<1 || 
  strlen($jsonData->email)<1 || strlen($jsonData->phone)<1 || strlen($jsonData->address)<1 || strlen($jsonData->state)<1){
  
    $msg = array();
    strlen($jsonData->firstname) <1?$msg['firstname'] = 'First name field is empty':null;
    strlen($jsonData->lastname) <1?$msg['lastname'] = 'Last name field is empty':null;
    strlen($jsonData->username) <1?$msg['username'] = 'Username field is empty':null;
    strlen($jsonData->email) <1?$msg['email'] = 'Email field is empty':null;
    strlen($jsonData->password) <1?$msg['password'] = 'Password field is empty':null;
    strlen($jsonData->address) <1?$msg['address'] = 'Address field is empty':null;
    strlen($jsonData->state) <1?$msg['state'] = 'State field is empty':null;
    $response = new Response();
    $response->SendResponse(400, false, $msg);
}

$validEmail = $emailValidate->validationRule($jsonData->email);
$minUsername = $minValidation->validationRule($jsonData->username);
$NoSpaceUsername = $noSpaceValidate->validationRule($jsonData->username);

if($validEmail===false || $minUsername === false|| $NoSpaceUsername ===false){
  $msg= array();
  ($validEmail===false)?$msg['email'] = 'Invalide Email!':null;
  ($noSpaceValidate===false)?$msg['username_space'] = 'Username cannot contain spaces.':null;
  ($minUsername===false)?$msg['Username_min'] = 'Username cannot be less than six digits.':null;

  $response = new Response();
  $response->SendResponse(400, false, $msg);
}

//declaring and validation
$firstname = trim($jsonData->firstname);
$lastname = trim($jsonData->lastname);
$username = trim($jsonData->username);
$email = trim($jsonData->email);
$address = trim($jsonData->address);
$state = trim($jsonData->state);
$acc_type = 'user';
$created_at = date_time();

//now creating new user
$user = new User();
$user->setUsers($firstname, $lastname, $username, $email, $password,$created_at, 
$state,$address,$acl,$phone,$entrycode, $refcode,$acc_type);
/*
  SEND OTP HERE 
*/
$newUser = $user->createUser();

if($newUser !== true){
  $response = new Response();
  $response->SendResponse(400, false, 'There is a problem creating user account.');
}

$response = new Response();
$response->SendResponse(200, false, 'Thank you for creating account with us');