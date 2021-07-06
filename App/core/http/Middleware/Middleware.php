<?php 
namespace core\http\Middleware;
use core\Model;
use core\Response;

class Middleware{
  private $token="kjfakjakakjklafjlakla;fklakfal";
  private $char = '0123456789*&%$#@!~?abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  private  $response;
  private $incoming_token;

  public function __construct(){
    $this->response = new Response();
  }

  public function getACL_Username($token){
    $model = new Model('session_tb');
    $sessionData = $model->findByToken('session_tb', $token);
    if(!$sessionData){
      $this->response->SendResponse(401, false, 'You need to log in first.');
    }
    //Now use the username in session data retrivedd to get acl from user table
    $userdata = $model->findByUsername('users', $sessionData->username);
    $userdata->acl;
    // print($sessionData->username);die();
    $aclArry = unserialize($userdata->acl);
    $session = [];
    $session['user_acl'] = $aclArry;
    $session['loggedUser'] = $userdata->username;
    //print($userdata->username);die;
     var_dump($session);die();
  }

  public function rand6(){
    return mt_rand(100000,999999); 
  }


  public function token(){
    return $this->generateRandomString();
  }
  public function generateRandomString($length = 50) {
    $charactersLength = strlen($this->char);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $this->char[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  public function loggedUser(){
   // $this->getACL_Username();
  }
}