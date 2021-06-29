<?php 
namespace core;

class View{
  protected $_head, $_footer, $_title = SITE_TITLE, $_outputBuffer, $_layout = DEFAULT_LAYOUT;

  public function __construct(){
    
  }

  public function render($viewName){
    $viewAry = explode('/', $viewName);
    $viewString = implode(ds, $viewAry);
    if(file_exists(ROOT . ds . 'API' . ds . 'views' . ds . $viewString . '.php')){
      include(ROOT . ds . 'API' . ds . 'views' . ds . $viewString . '.php');
      include(ROOT . ds . 'API' . ds . 'views' . ds . 'layouts' . ds . $this->_layout . '.php');
    }else{
      die('The view \"' . $viewName . '\" does not exist.');
    }
  }

  public function response(array $response){

  }

  public function content($type) {
    if($type == 'head') {
      return $this->_head;
    } elseif($type == 'body') {
      return $this->_body;
    }elseif($type == 'footer'){
      return $this->_footer;
    }
    return false;
  }

  public function start($type) {
    $this->_outputBuffer = $type;
    ob_start();
  }

  public function end() {
    if($this->_outputBuffer == 'head') {
      $this->_head = ob_get_clean();
    } elseif($this->_outputBuffer == 'body') {
      $this->_body = ob_get_clean();
    } else {
      die('You must first run the start method.');
    }
  }

  public function siteTitle() {
    return $this->_siteTitle;
  }

  public function setSiteTitle($title) {
    $this->_siteTitle = $title;
  }

  public function setLayout($path) {
    $this->_layout = $path;
  }

  public function insert($path){
    include ROOT . ds . 'app' . ds . 'views' . ds . $path . '.php';
   }

  public function partial($group, $partial){
    include ROOT . ds . 'app' . ds . 'views' . ds . $group . ds . 'partials' . ds . $partial . '.php';
  }

}