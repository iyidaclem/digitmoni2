<?php
namespace test;
use core\http\Middleware;
use core\http\Middleware\IndexMiddleware;

class MiddlewareTest{
  public function __construct()
  {
    $indexMiddleware = new IndexMiddleware();

    $indexMiddleware->getACL_Username('8NEEkw@fmgiAaYuk$MbNA5u2oYOdv@A2YIH2Wdar@b57d7kEMv');

     print($indexMiddleware->isPartner());
  }
}

