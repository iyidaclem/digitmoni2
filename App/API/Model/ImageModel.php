<?php
namespace API\Model;

use core\Model;

class ImageModel extends Model{
  public function __construct($table){
    parent::__construct($table);
    
  }

  public function uploadImage($uploadFields){
    $upload = $this->insert($uploadFields);
    return $upload;
  }

  public function getImage(){

  }

  public function updateImage(){

  }

}