<?php 
namespace Core;

use core\Application;

class Controller extends Application{
  protected $_controller, $_action;
  public $view, $request;
  
  public function __construct($controller, $action){
    //call the parent Application
    $this->_controller = $controller;
    $this->_action = $action;
    $this->request = new Input();
    $this->view = new View();
  }

  protected function laod_model($model){
    $modelPath = 'App\Model\\' . $model;
    if(class_exists($model)){
      $this->{$model.'Model'} = new $modelPath();
    }
  }

  public function jsonResponse($resp){
    header("Access-Control-Allow-Origin:*");
    header("Content-Type:application/json; charset=UTF-8");
    http_response_code(200);
    echo json_encode($resp);
    exit;
  }
}