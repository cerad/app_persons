<?php

namespace Cerad\Bundle\AuthBundle\Auth;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController
{
  private $users;
  private $secret;
  
  public function __construct($secret,$users)
  {
    $this->users  = $users;
    $this->secret = $secret;
  }
  public function tokensPostAction(Request $request)
  {
    $requestData = json_decode($request->getContent(),true);
    
    $oauthToken = $requestData['oauthToken'];
    
    $oauthPayload = (array)\JWT::decode($oauthToken, $this->secret);
    
    // Maybe user provider here?
    $identifier = $oauthPayload['identifier'];
    $userInfo = [];
    foreach($this->users as $username => $info)
    {
      if ($info['identifier'] == $identifier)
      {
        $userInfo = array_merge(['username' => $username], $info);
        break;
      }
    }
    $authPayload = array_merge(
      [
        'iat' => time(),
        'username' => 'Unknown',
        'roles' => [],
      ],
      $userInfo
    );
    $authToken = \JWT::encode($authPayload, $this->secret);
    
    $authPayload['authToken'] = $authToken;
    
    return new JsonResponse($authPayload,202);
    
  }
}
