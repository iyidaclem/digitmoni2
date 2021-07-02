<?php
namespace core;
use core\User;

class Router{
  private $_server;
  public static function route($url) {

    // $server= $_SERVER['HTTP_AUTHORIZATION'];
    // print($server);
    // die();
    //controller
    $controller = (isset($url[0]) && $url[0] != '') ? ucwords($url[0]).'Controller' : DEFAULT_CONTROLLER.'Controller';
    $controller_name = str_replace('Controller','',$controller);
    array_shift($url);

    //action
    $action = (isset($url[0]) && $url[0] != '') ? $url[0] . 'Action': 'indexAction';
    $action_name = (isset($url[0]) && $url[0] != '')? $url[0] : 'index';
    array_shift($url);

    //acl check
    $grantAccess = self::hasAccess($controller_name, $action_name);

    if(!$grantAccess) {
      $controller = ACCESS_RESTRICTED.'Controller';
      $controller_name = ACCESS_RESTRICTED;
      $action = 'indexAction';
    }

    //params
    $queryParams = $url;
    $controller = 'API\Controllers\\' . $controller;
    //var_dump($controller);die();
    $dispatch = new $controller($controller_name, $action);

    if(method_exists($controller, $action)) {
      call_user_func_array([$dispatch, $action], $queryParams);
    } else {
      //die('That method does not exist in the controller \"' . $controller_name . '\"');
      $render = new View();
      $UnkownPage = $render->render('error/index');
    }
  }


  public static function hasAccess($token, $controller_name, $action_name='index'){
    $acl_file = file_get_contents(ROOT . ds . 'API' . ds . 'acl.json');
    $acl = json_decode($acl_file, true);
    $current_user_acls = ['member'];
    $grantAccess = false;

    if(Session::exists($token)){
      $current_user_acl[] = "loggedin";
      foreach(User::currentUser()->acls() as $a){
        $current_user_acl[] =$a;
      }
    }

    //checking for where he has access 

    //checking for where access is to be denied

    return true;
  }


}