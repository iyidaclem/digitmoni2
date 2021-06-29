<?php 
namespace core\validationRule;
use _interface\ValidationRuleInterface;

class ValidateMaximum implements ValidationRuleInterface{

  private $_maximum;

  public function __construct($maximum){
    $this->_maximum = $maximum;
  }

  function validationRule($value){
    if(strlen($value) > $this->_maximum){
      return false;
    }
    return true;
  }

  function getErrorMessage(){
    return "Maximum value is over ".$this->_maximum;
  }
}