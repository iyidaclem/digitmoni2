<?php 
namespace core\validationRule;
use _interface\ValidationRuleInterface;


class EmailValidation implements ValidationRuleInterface{

  function validationRule($value){
    if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
      return false;
    }
    return true;
  }

  function getErrorMessage(){
    return "Email format is not correct.";
  }
}