<?php
namespace core\Call;
use core\Model;
use core\Encrypt;
/**
 * This class and associated method when called get's 
 * the token key saved in database. 
 * --- Ikechukwu Vincent[Principal Backend Dev] 21-21-07
 */
class CallKeyFromDB{

  private $model;

  public function __construct(){
    $this->model = new Model('cryp_tb');
  }

  public static function getAPIkeyFromDB(){
    $model = new Model('cryp_tb');
    $details = $model->findFirst(['conditions' => 'id = ?','bind' => [1]]);
    if(!$details) //HIGH PRIORITY ERROR LOG
     return false;
     $credentials = [];
     $credentials['key'] = Encrypt::__decrypt($details->enc_key);
     $credentials['ourID'] = $details->ourID;
    return $credentials;
  }

  public function getOurID(){
    $ourID = $this->model->findFirst(['conditions' => 'id = ?','bind' => [1]]);
  }
}