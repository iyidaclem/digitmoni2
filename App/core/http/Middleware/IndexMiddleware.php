<?php 
namespace core\http\Middleware;
use core\Model;
use core\Response;

class IndexMiddleware{
  private $response;
 
  private $memberRoutes=[
    ROOT 
  ];

  protected $partnerRoutes = [

  ];

  protected $investmentAdmin = [

  ];

  protected $utilityAdminRoutes=[

  ];

  protected $superAdminRoutes=[

  ];

  protected $subPartnerRoutes=[

  ];

  private $acl;

  private $loggedinUser;

  private $superAdmin;

  private $partner;

  private $invAdmin;

  private $uAdmin;

  private $sessionData;

  public function __construct(){
    //setting the routes to varous acls
    //$this->setRoutes($member, $partner, $subPartner, $invAdmin, $uAdmin, $superAdmin);
    //getting username and acl
    //$usernameAndACL= $this->getACL_Username($this->server_auth);  
    //getting the acl array
    //$this->acl = $usernameAndACL['user_acl'];
    //getting the logged in username
    //$this->loggedinUser = $usernameAndACL['loggedUser'];

    $this->response = new Response();
    
  }

  public function getACL_Username($token){
    $model = new Model('session_tb');
    $sessionData = $model->findByToken('session_tb', $token);
    if(!$sessionData){
      $this->response->SendResponse(401, false, 'You need to log in first.');
    }
    //Now use the username in session data retrivedd to get acl from user table
    $userdata = $model->findByUsername('users', $sessionData->username);
    $userdata->acl;

    $aclArry = unserialize($userdata->acl);
    $session = [];
    $session['user_acl'] = $aclArry;
    $session['loggedUser'] = $sessionData->username;
    $session['loggedUserID'] = $userdata->id;
    
    //return $session;
    //var_dump($session);die();
    $this->sessionData = $session;
  }

  public function setRoutes($member, $partner, $subPartner, $invAdmin, $uAdmin, $superAdmin){
    $this->memberRoutes = $member;
    $this->partnerRoutes = $partner;
    $this->investmentAdmin = $invAdmin;
    $this->utilityAdminRoutes = $uAdmin;
    $this->superAdminRoutes = $superAdmin;
    $this->subPartnerRoutes = $subPartner;
  }

  public function dump(){
    $session = $this->sessionData;
    var_dump($session);die();
  }
  public function loggedUser(){
    //return $this->sessionData['loggedUser'];
    return $this->sessionData['loggedUser'];
  }

  public function loggedUserID(){
    return $this->sessionData['loggedUserID'];
  }

  public function ACLRoutes(){

  }

  public function isUser(){
    if(!in_array('member', $this->sessionData['user_acl'])) return false;
    return true;
  }

  public function isSuperAdmin(){
    // var_dump($this->sessionData['user_acl']);
    if(!in_array('superadmin', $this->sessionData['user_acl'])) return false;
    return true;
  }

  public function isPartner(){
    // var_dump($this->sessionData['user_acl']);
    if(!in_array('partner', $this->sessionData['user_acl'])) return false;
    return true;
  }


  public function isUAdmin(){
    if(!in_array('uAdmin', $this->sessionData['user_acl'])) return false;
    return true;
  }

  public function isInvAdmin(){
   // var_dump($this->sessionData['user_acl']);
    if(!in_array('invAdmin', $this->sessionData['user_acl'])) return false;
    return true;
  }

  public function isBlogAdmin(){
    var_dump($this->sessionData['user_acl']);
    if(!in_array('blogAdmin', $this->sessionData['user_acl'])) return false;
    return true;
  }



}