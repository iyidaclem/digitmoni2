<?php 
use database\DataBase;
require_once '../database/DataBase.php';
require_once '../core/Response.php';
require_once '../model/user.php';






function insert(){
  $db = DataBase::getInstance();
  $username = 'Emeka';
  $access_token = 'akjfka9r0392rfnvafilaefola';
  $user_agent = 'Browser, firefox- HP Probook';
  $token_exp = '2021-09-09';
  $fields = ['username'=>$username,	'access_token'=>$access_token, 	'user_agent'=>$user_agent,	'token_exp'=>$token_exp];
  
  $db->query("INSERT INTO session_tb WHERE username=? 
  and access_token=? and user_agent=? and token_exp=?", $fields);
  $db->insert('session_tb', $fields);
}

insert();