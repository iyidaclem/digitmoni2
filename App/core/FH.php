<?php
namespace core;

class FH{
 public static function sanitize($dirty){
  return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
 } 
}