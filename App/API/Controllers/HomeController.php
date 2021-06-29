<?php
namespace API\Controllers;
use core\Controller;

class HomeController extends Controller{

  
  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
  }

  public function indexAction(){
    echo "digitmoni app home";
  }
}