<?php
namespace test;
use core\http\Middleware;
use core\http\Middleware\IndexMiddleware;

class MiddlewareTest{
  public function __construct($server)
  {
    $indexMiddleware = new IndexMiddleware();

    $indexMiddleware->getACL_Username($server);

  }
}

