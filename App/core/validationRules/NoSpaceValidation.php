<?php 
namespace core\validationRule;
use _interface\ValidationRuleInterface;

class ValidateNoEmptySpace implements ValidationRuleInterface{
  
  function validationRule($value){
    
    if(strpos($value, ' ') === false){
      return true;
    }

    return false;
  }

  function getErrorMessage(){
    return "No empty spaces allowed.";
  }
}