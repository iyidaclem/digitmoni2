<?php 
namespace core\http\Middleware;
use core\Model;

class IndexMiddleware{
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

  public function __construct($server_auth, $member, $partner, $subPartner, $invAdmin, $uAdmin, $superAdmin, $url){
    $this->setRoutes($member, $partner, $subPartner, $invAdmin, $uAdmin, $superAdmin);
    $usernameAndACL= $this->getACL_Username($server_auth);  
    $this->acl = $usernameAndACL['user_acl'];
    $this->loggedinUser = $usernameAndACL['loggedUser'];
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
    return $session;
  }

  public function setRoutes($member, $partner, $subPartner, $invAdmin, $uAdmin, $superAdmin){
    $this->memberRoutes = $member;
    $this->partnerRoutes = $partner;
    $this->investmentAdmin = $invAdmin;
    $this->utilityAdminRoutes = $uAdmin;
    $this->superAdminRoutes = $superAdmin;
    $this->subPartnerRoutes = $subPartner;
  }

  public function memberRoutes(array $PermittedRoutes, array $incomingRoute){
    $this->memberRoutes = $PermittedRoutes;

  }

  public function loggedUser(){
    return $this->loggedinUser;
   }

  public function ACLRoutes(){

  }

}