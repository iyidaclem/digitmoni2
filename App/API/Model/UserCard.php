<?php 
namespace API\Model;
use core\Encrypt;
use core\Model;
use core\Response;
use core\FH;

class UserCard extends Model{

 
  public function __construct($table){
    parent::__construct($table);
    $this->res = new Response();
  }

  
  public function getAndDecryptUserCardInfo($targetUser){
    //id	username	card_holder	card_no	threepin	date	card_issuer
    $encryptedCardInfo = $this->findFirst(['conditions' => 'username = ?','bind' => [$targetUser]]);
    if(!$encryptedCardInfo) return false;
    $decryptedCardInfo=[];
    $decryptedCardInfo['card_holder'] = Encrypt::__decrypt($encryptedCardInfo->card_holder);
    $decryptedCardInfo['card_no'] = Encrypt::__decrypt($encryptedCardInfo->card_holder);
    $decryptedCardInfo['threepin'] = Encrypt::__decrypt($encryptedCardInfo->threepin);
    $decryptedCardInfo['card_date'] = Encrypt::__decrypt($encryptedCardInfo->card_date);
    $decryptedCardInfo['card_issuer'] = Encrypt::__decrypt($encryptedCardInfo->card_issuer);
    return $decryptedCardInfo;
  }

  public function cardLastFour($targetUser){
    $decryptedCardInfo = $this->getAndDecryptUserCardInfo($targetUser);
    if(!$decryptedCardInfo) return false;
    $decryptedLast4 = substr($decryptedCardInfo[1], -4);
    return $decryptedLast4;
  }
  
}