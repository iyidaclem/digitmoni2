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

class AdminController extends Controller{
  private $input;
  private $model;
  private $db;
  private $middleware;
  private $indexMiddleware;
  private $response;

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->input = new Input();
    $this->model = new CoreModel('users');
    $this->db = new DataBase();
    $this->indexMiddleware = $GLOBALS['indexMiddleware'];
    $this->response = new Response();
  }

  public function appointAction($username,$role){
    //checking for request method.
    if($this->input->isPost()) return $this->response->SendResponse(
      401,false, 'Only POST requests are allowed'
    );
    //making sure that the user is the super admin
    if(!$this->indexMiddleware->isSuperAdmin()) return $this->response->SendResponse(
      401,false, 'You cant perform this action.'
    );
    //query the database to get detailse of the incoming admin using the username
    $incomingAdminData = $this->model->findByUsername('users', $username);
    //get his ACL as array 
    $incomingAdminACL = unserialize($incomingAdminData->acl);

    array_push($incomingAdminACL, $role);;
   
    $updatedACL = serialize($incomingAdminACL);
    
    // //update 
    $fields=[
     'acl'=>$updatedACL
    ];
    $appoint = $this->model->update($incomingAdminData->id, $fields);
    if(!$appoint) 
    return $this->response->SendResponse(400, false, "Failed to make this dude an admin for some reasons.");
    //returning success message with new admin detail
    $appointed = $this->model->findByUsername('users', $username);
    $acl = unserialize($appointed->acl);
    $appointed->acl =$acl;
    return $this->response->SendResponse(200, false, 'New admin appointed.',false,$appointed);

  }

  public function adminListAction(){

  }

  public function revokeAction($username,$role){
    if($this->input->isPost()) return $this->response->SendResponse(
      401,false, 'Only POST requests are allowed'
    );
    //making sure that the user is the super admin
    if(!$this->indexMiddleware->isSuperAdmin()) return $this->response->SendResponse(
      401,false, 'You cant perform this action.'
    );
    //query the database to fetch the details of the outgoing admin
    $outgoingAdminData = $this->model->findByUsername('users', $username);
    //
    $outgoingAdminACL = unserialize($outgoingAdminData->acl);
    
    if (($key = array_search($role, $outgoingAdminACL)) !== false) unset($outgoingAdminACL[$key]);
    
    $updatedACL = serialize(array_values($outgoingAdminACL));

    //Now update the database

    $fields=[
      'acl'=>$updatedACL
    ];
    if(!$this->model->update($outgoingAdminData->id, $fields)) 
    return $this->response->SendResponse(
      400, false, "Failed to make this get this dude out as an admin for some reasons."
    );
    
    //fetch deposed admin
    $deposed = $this->model->findByUsername('users', $username);
    $acl = unserialize($deposed->acl);
    $deposed->acl =$acl;
    //returning success message
    return $this->response->SendResponse(200, false, "This dude's admin role has been revoked", true, $deposed);

  }
}