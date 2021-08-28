<?php
/**
 * This controller is incharge of all access key features. 
 * The access key and ID this app uses to communicate with 
 * all third party APIs and the features for updating them 
 * are handled in this controller.
 * 
 * The features here like changing the access keys can only be accessed by
 * use with highest level access- superadmin.
 * 
 */
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
use core\Encrypt;

class KeysController extends Controller{
  private $input;
  private $keys;
  private $logger;
  private $db;
  private $middleware;
  private $indexMiddleware;
  private $resp;
  /**
   * The constructor method loads all instances that the features in our controller needs to 
   * work. 
   */

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->keys = new Keys('cryp_tb');
    $this->db = new DataBase();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->resp = new Response();
    $this->logger = new Logger();
  }
  /**
   * .../retrieve_key/{ID:1or2}   GET REQUEST
   * Use 1 when you want to retrieve utility key and ID
   * Use 2 when you want to retrieve investment key and ID
   * 
   * Ofcourse with each is it's associated descriptions.
   */
  public function retrieve_keyAction($ID){

    if(!$this->input->isGet())return $this->resp->SendResponse(401, false, GET_MSG, false, []);
    if(!$this->indexMiddleware->isSuperAdmin()) return $this->resp->SendResponse(
      403, false, ACL_MSG,false, []);
    // QUERY DATABASE TO GET KEY
    $getKeyDetails = $this->keys->findFirst(['conditions' => 'id = ?','bind' => [$ID]]);
    
    if(!$getKeyDetails) return $this->resp->SendResponse(404, false, "Not found.", false, []);
    //decrypting
    $getKeyDetails->enc_key = Encrypt::__decrypt($getKeyDetails->enc_key);
    return $this->resp->SendResponse(200, true, '', false, $getKeyDetails);
  }

  /**
   * .../update_key/{$ID:1or2}  PUT REQUEST. 
   * 
   * use 1 when updating the utility key details.
   * use 2 when updating the investment key and its details.
   * The parameters to be supplied will be user input in json data format as follows 
   * {
   *  "key_token":"new key obtained from our vendor",
   *  "description":"Describe what the key is all about",
   *  "our_id":"our id with vendor where it applies",
   *  "ourID_desc":"description of our id with vendor"
   * }
   * 
   * @param int $ID is the 1 or two above which will be the last part of the url 
   * 
   * @return array $data[] This endpoint or action when called returns empty array, 
   * http status code of 200 and success message if the update was successfull. 
   * 
   * It also returns http status code of 500, and error message if it fails.
   */
  public function update_keyAction($ID){
    if(!$this->input->isPut())return $this->resp->SendResponse(401, false, GET_MSG, false, []);
    if(!$this->indexMiddleware->isSuperAdmin()) return $this->resp->SendResponse(
      403, false, ACL_MSG,false, []);
    
    //handle inputs 
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData);
    
    $sanitized = FH::arraySanitize($data);
    $enc_key = Encrypt::__encrypt($sanitized['key_token']);
    $ency_id = Encrypt::__encrypt($sanitized['our_id']);
    $keyFields = [
      'enc_key'=>$enc_key,
      'key_description'=>$sanitized['key_desc'],
      'ourID'=>$ency_id, 
      'ourID_description'=>$sanitized['ourID_desc']
    ];
   
    //update key
    $updateKeyDetails = $this->keys->update($ID, $keyFields);
    if(!$updateKeyDetails) return $this->resp->SendResponse(
      500, false, 'Failed to update key. Old key still active.', false, []);
    
    //log 
    $currentUser = $this->indexMiddleware->loggedUser();
    //$this->logger->log($currentUser,'Changed', "$ID properties", 'page', 'good', 'user_agent');
    return $this->resp->SendResponse(200, true, 'Key successfully changed.', false, []);
  }

}