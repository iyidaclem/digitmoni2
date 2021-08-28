<?php 
namespace core\http\Middleware;
use core\Model;
use core\Response;

/**
 * This class is an interesting one. Only one instance of it is created in the entire app.
 * This instance is stored in the super global variable $_GLOBALS and accessed on-demand 
 * anywhere in the app to get detail of the logged in user or to determine the user's ACL- 
 * ACCESS LEVEL or ACCESS CONTROL LOOP.
 * 
 * All the methods starting with "is" followed by specific admin are used just to tell if the logged in
 * user has the specified admin access. They return true if the user has the access or false if the user doesnt.
 */
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

    $this->response = new Response();
    
  }

  /**
   * @param string $token this is the access the token of the incoming request.
   * 
   * This function takes the access token of the incoming request as the variable. Then use it to fetch
   * the user's details like user ACL, username, userID, user-email. In case of user acl which is serialized,
   * the function unserialize it back to an array.
   * 
   * POSSIBLE RESPONSES
   * 
   * 1. A json response with http code of 401 "You are not logged in" message. This happens when the 
   * user is not logged in or the session have expired.
   * 
   * 2. Returns absolutely nothing. Rather hands over over the fetched user detail to $sessionData private variable
   * as array.
   * 
   * 
   * @return [type]
   */
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
    // var_dump($session);die();
    $this->sessionData = $session;
  }

  /**
   * @return $username this method when called through an instance of the middleware returns the 
   * username of the logged in user.
   */
  public function loggedUser(){
    //return $this->sessionData['loggedUser'];
    return $this->sessionData['loggedUser'];
  }

  /**
   * @return $userID this method when called returns the unique user id generated 
   * MySQL auto incrementing id column.
   */
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