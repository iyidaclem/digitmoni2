<?php 
namespace core\validationRule;
use _interface\ValidationRuleInterface;

class ValidateMinimium implements ValidationRuleInterface{

  private $_minimum;

  public function __construct($minimum){
    $this->_minimum = $minimum;
  }

  function validationRule($value){
    if(strlen($value)<$this->_minimum){
      return false;
    }
    return true;
  }

  function getErrorMessage(){
    return "Minimum value is under " .$this->_minimum ." characters";
  }
}