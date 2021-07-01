<?php
namespace API\Controllers;
use core\Controller;
use core\Input;
use Response;

class HomeController extends Controller{
  private $input;
  
  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
  }

  public function indexAction(){

    if(!$this->input->isPost()) return $this->jsonResponse([
      'status'=>'fail',
      'http'=>401,
      'message'=>'Only Post Requests are allowed.',
      'data'=>[]
    ]);
    $rawData = file_get_contents('php://input');
    var_dump($rawData);
    $request = $_REQUEST;
    var_dump($request);
    echo "digitmoni app home";
  }

  public function referenceAction(){
    
  }
}