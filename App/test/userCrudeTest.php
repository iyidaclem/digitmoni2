<?php 

require_once '../database/DataBase.php';
require_once '../core/Response.php';
require_once '../model/user.php';
//require_once '../helpers/helper.php';


$user = new User();


$user->setUsers('Ikechukwu', 'Vincent', 'Ikechukwu',
 'mr.ikunegu@gmail.com', '199418','2021-06-19','Anambre', 'No. 7 Ankys', null, '08064133376','495894','59840', 'member');

// $user->createUser();
$userID = [1];
$userValues = $user->viewUser(1);

$user->setActivity('blocked');

if($user->deactivateUser(1)){
  echo 'success';
}else{
  echo 'fail';
}
var_dump($userValues);