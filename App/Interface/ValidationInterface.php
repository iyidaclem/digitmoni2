<?php 
namespace _interface;

interface ValidationRuleInterface{
  public function validationRule($value);
  public function getErrorMessage();
}