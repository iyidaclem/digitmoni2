<?php 

function dnd($data){
  if(is_array($data)) {
    echo "<pre>";
  var_dump($data);
  echo "</pre>";
  die();
  }elseif(is_float($data) || is_numeric($data) || is_string($data)){
    print($data);
  }
}