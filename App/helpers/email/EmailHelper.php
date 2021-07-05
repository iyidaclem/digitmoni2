<?php
namespace helpers\email;
require 'vendor/autoload.php';
use phpmailer\phpmailer;

class EmailHelper{
  private $mail;
  public function __construct()
  {
    //$this->mail = new phpmailer(); 
  }
}