<?php

use core\http\Middleware\Middleware;
use core\Router;
use core\http\Middleware\IndexMiddleware;
use test\MiddlewareTest;


define('ds', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

require_once(ROOT . ds . 'config' . ds . 'config.php');
require_once(ROOT . ds . 'core' . ds . 'Calls' . ds . 'investment' . ds . 'API_CONFIG.php');

function autoload($className){
  $classAry = explode('\\',$className);
  $class = array_pop($classAry);
  $subPath = strtolower(implode(ds,$classAry));
  $path = ROOT . ds . $subPath . ds . $class . '.php';
  //var_dump($path);die;
  if(file_exists($path)){
    require_once($path);
  }
}

spl_autoload_register('autoload');

$url = isset($_SERVER['PATH_INFO'])? explode('/', ltrim($_SERVER['PATH_INFO'], '/')):[];
//var_dump($_SERVER['HTTP_AUTHORIZATION']); die();
if(array_key_exists('HTTP_AUTHORIZATION',$_SERVER)){
 $indexMiddleware = new IndexMiddleware();
 $indexMiddleware->getACL_Username($_SERVER['HTTP_AUTHORIZATION']);
 $GLOBALS['indexMiddleware'] = $indexMiddleware;
}else{
  $userCred = null;
}


Router::route($url);