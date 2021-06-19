<?php 

require_once '../database/DataBase.php';
require_once '../core/Response.php';
require_once '../model/user.php';



$user = new User();

$user->setUsers('Ikechukwu', 'Vincent', 'Ikechukwu',
 'mr.ikunegu@gmail.com', '199418', '2021-06-19','Anambre', 'No. 7 Ankys', 'admin superadmin user', '08064133376','495894','59840', 'user');

$user->createUser();