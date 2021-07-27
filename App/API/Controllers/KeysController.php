<?php
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\Middleware;
use core\http\Middleware\IndexMiddleware;
//use core\Response;
use core\Response;
use API\ModeL\Keys;
use core\logger\Logger;
use database\DataBase;

class KeysController extends Controller{
  private $input;
  private $keys;
  private $logger;
  private $db;
  private $middleware;
  private $indexMiddleware;
  private $resp;

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->keys = new Keys('keys');
    $this->db = new DataBase();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->resp = new Response();
    $this->logger = new Logger();
  }

  public function retrieve_keyAction($whichKey){
    if(!$this->input->isGet())return $this->resp->SendResponse(401, false, GET_MSG, false, []);
    if(!$this->indexMiddleware->isSuperAdmin()) return $this->resp->SendResponse(
      403, false, ACL_MSG,false, []);
    // QUERY DATABASE TO GET KEY
    $getKeyDetails = $this->keys->findFirst(['conditions' => 'key_name = ?','bind' => [$whichKey]]);
    $msgWord = '';
    ($whichKey==='utility')?$msgWord='Utility key ':$msgWord='Investment key ';
    if(!$getKeyDetails) return $this->resp->SendResponse(404, false, "$msgWord key not found.", false, []);
    return $this->resp->SendResponse(200, true, '', false, $getKeyDetails);
  }

  public function update_keyAction($whichKey){
    if(!$this->input->isGet())return $this->resp->SendResponse(401, false, GET_MSG, false, []);
    if(!$this->indexMiddleware->isSuperAdmin()) return $this->resp->SendResponse(
      403, false, ACL_MSG,false, []);
    
    //handle inputs 
    $jsonData = file_get_contents('input://php');
    $data = json_decode($jsonData);
    $sanitized = FH::arraySanitize($data);
    $keyFields = [
      'key_token'=>$sanitized['key_token'],
      'description'=>$sanitized['description']
    ];
    //deciding which key to update via ID
    $keyID = 0;
    ($whichKey==='utility')?$keyID=1:$keyID =2;   
    //update key
    $updateKeyDetails = $this->keys->update($keyID, $keyFields);
    if(!$updateKeyDetails) return $this->resp->SendResponse(
      500, false, 'Failed to update key. Old key still active.', false, []);
    
    //log 
    $currentUser = $this->indexMiddleware->loggedUser();
    $this->logger->log($currentUser,'Changed', "$whichKey key", 'page', 'good', 'user_agent');
    return $this->resp->SendResponse(200, true, 'Key successfully changed.', false, []);
  }

}