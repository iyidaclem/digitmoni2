<?php
namespace API\Model;
use database\DataBase;
use core\Model;


class UserInvestment extends Model{
  private $model;
  public function __construct($table){
    parent::__construct($table);
    $this->model = new Model('user_investment');
}
  public function getUserInvestmentPackageRule($user, $ID){
    $userSingleInvestment = $this->model->findFirst([
      'conditions' => 'username = ? AND id = ? AND state = ?',
      'bind' => [$user, $ID, 'active']
    ]);

    if(!$userSingleInvestment) return false;

    return $userSingleInvestment;
  }

  public function getAllUserInvestments($user){
    $allUserActiveInvestment = $this->model->find([
      'conditions' => 'username = ? AND state = ? AND status = ?',
      'bind' => [$user, 'active', 'true']
    ]);

    if(!$allUserActiveInvestment) return false;

    return $allUserActiveInvestment;
  }
 

}