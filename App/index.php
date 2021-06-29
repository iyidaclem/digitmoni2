<?php 
use core\Router;

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

Router::route($url);