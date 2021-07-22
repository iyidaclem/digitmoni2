<?php
namespace core\Call;
use core\Model;
use core\Encrypt;
/**
 * This class and associated method when called get's 
 * the token key saved in database. 
 * --- Ikechukwu Vincent[Principal Backend Dev] 21-21-07
 */
class CallKey{
  public static function getAPIkeyFromDB(){
    $model = new Model('cryp');
    $encryptedKey =$model->find(['conditions' => 'id = ?','bind' => [1]]);
    if(!$encryptedKey) //HIGH PRIORITY ERROR LOG
     return false;
    return Encrypt::__decrypt($encryptedKey);
  }
}