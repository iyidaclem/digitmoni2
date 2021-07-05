<?php
namespace API\Controllers;

use core\Controller;
use core\Input;
use API\Model\Users;
use core\FH;
use core\http\Middleware\Middleware;
use core\http\Middleware\IndexMiddleware;
use core\Model as CoreModel;
//use core\Response;
use core\Response;
use database\DataBase;

class Superuser extends Controller{
  private $input;
  private $model;
  private $db;
  private $middleware;
  private $response;

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('users');
    $this->db = new DataBase();
    $this->middleware = new IndexMiddleware();
    $this->response = new Response();
  }

  public function appointAction($username,$role){
    //checking for request method.
    if($this->input->isPost()) return $this->response->SendResponse(
      401,false, 'Only POST requests are allowed'
    );
    //making sure that the user is the super admin
    if(!$this->middleware->isSuperAdmin()) return $this->response->SendResponse(
      401,false, 'You cant perform this action.'
    );
    //query the database to get detailse of the incoming admin using the username
    $incomingAdminData = $this->model->findByUsername('users', $username);
    print($incomingAdminData); die();
    //get his ACL as array 
    $incomingAdminACL = unserialize($incomingAdminData->acl);
    //push new access level into the array and serialize again 
    $updatedACL = array_push($incomingAdminACL, $role);
    $updatedACL = serialize($updatedACL);
    //update 
    $fields=[
      'acl'=>$updatedACL
    ];
    if(!$this->model->update($incomingAdminData->id, $fields)) 
    return $this->response->SendResponse(400, false, "Failed to make this dude an admin for some reasons.");
    //returning success message
    return $this->response->SendResponse(200, false, `{$incomingAdminData->username} is now ${$role}`);

  }

  public function adminListAction(){

  }

  public function revokeAction($username,$role){
    if($this->input->isPost()) return $this->response->SendResponse(
      401,false, 'Only POST requests are allowed'
    );
    //making sure that the user is the super admin
    if(!$this->middleware->isSuperAdmin()) return $this->response->SendResponse(
      401,false, 'You cant perform this action.'
    );
    //query the database to fetch the details of the outgoing admin
    $outgoingAdminData = $this->model->findByUsername('users', $username);
    //
    $outgoingAdminACL = unserialize($outgoingAdminData->acl);
    if (($key = array_search($role, $outgoingAdminACL)) !== false) unset($outgoingAdminACL[$key]);
    
    $updatedACL = array_values($outgoingAdminACL);

    //Now update the database

    $fields=[
      'acl'=>$updatedACL
    ];
    if(!$this->model->update($outgoingAdminData->id, $fields)) 
    return $this->response->SendResponse(400, false, "Failed to make this get this dude out as an admin for some reasons.");
    //returning success message
    return $this->response->SendResponse(200, false, `{$outgoingAdminData->username} is now ${$role}`);

  }
}