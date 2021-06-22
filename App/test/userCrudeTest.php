<?php 

require_once '../database/DataBase.php';
require_once '../core/Response.php';
require_once '../model/user.php';
//require_once '../helpers/helper.php';


$user = new User();


$user->setUsers('Ikechukwu', 'Ikechukwu', 'Ikechukwu',
 'mr.ikunegu@gmail.com', '199416','2021-06-19','Anambra State', 'No. 7 Ankys', null, '08064133376','495894','59840', 'member');

// $user->createUser();
$userID = [1];
$userValues = $user->viewUser(1);

//$user->setActivity('blocked');

$editUser =$user->editUser(1);
echo $editUser;
// if($user->alterUserState(1, 'blocked') ===true){
//   echo 'success';
// }else{
//   echo 'fail';
// }
var_dump($userValues);