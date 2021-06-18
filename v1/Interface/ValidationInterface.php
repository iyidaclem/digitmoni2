<?php 

interface ValidationRuleInterface{
  public function validationRule($value);
  public function getErrorMessage();
}