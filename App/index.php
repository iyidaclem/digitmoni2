<?php

use core\http\Middleware\Middleware;
use core\Router;
use core\http\Middleware\IndexMiddleware;
use test\MiddlewareTest;


define('ds', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

require_once(ROOT . ds . 'config' . ds . 'config.php');

function autoload($className){
  $classAry = explode('\\',$className);
  $class = array_pop($classAry);
  $subPath = strtolower(implode(ds,$classAry));
  $path = ROOT . ds . $subPath . ds . $class . '.php';
  if(file_exists($path)){
    require_once($path);
  }
}


spl_autoload_register('autoload');

$url = isset($_SERVER['PATH_INFO'])? explode('/', ltrim($_SERVER['PATH_INFO'], '/')):[];

// $test = new MiddlewareTest($_SERVER['HTTP_AUTHORIZATION']);
// die;
class index{

}
$GLOBALS['indexMiddleware'] =$middleware = new IndexMiddleware();

if(array_key_exists('HTTP_AUTHORIZATION',$_SERVER)){
  $aclUsername = $middleware->getACL_Username($_SERVER['HTTP_AUTHORIZATION']);
  //$GLOBALS['indexMiddleware']->dump();
}
//DEFINE ROUTES OR CONTROLLERS THAT DOESNT NEED AUTHENTICATION TOKEN
// var_dump($url);die();

Router::route($url);