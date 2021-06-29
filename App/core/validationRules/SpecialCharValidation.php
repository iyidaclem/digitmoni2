<?php 
namespace core\validationRule;
use _interface\ValidationRuleInterface;

class ValidateSpecialChars implements ValidationRuleInterface{

  private $_rule;

  public function __construct($rule = "/[^a-zA-Z0-9]+/"){
    $this->_rule = $rule;
  }

  function validationRule($value){
    if(!preg_match($this->_ruel, $value)){
      return false;
    }
    return true;
  }

  public function getErrorMessage(){
    return "Special characters not found.";
  }
}