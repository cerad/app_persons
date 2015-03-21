<?php

namespace Cerad\Bundle\AuthBundle\Auth;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController
{
  private $secret;
  
  public function __construct($secret)
  {
    $this->secret = $secret;
  }
  public function callbackAction(Request $request)
  {
    $provider = $this->providerManager->createFromRequest($request);

    $accessTokenData = $provider->getAccessToken($request);
        
    $userInfo = $provider->getUserInfo($accessTokenData);
    
    $oauthToken = \JWT::encode($userInfo, $this->secret);
    
    $html = include dirname(__FILE__) . '/oauth-callback.html.php';
    
    return new Response($html);
  }
  public function tokensPostAction(Request $request)
  {
    $requestData = json_decode($request->getContent(),true);
    $oauthToken = $requestData['oauthToken'];
    $payload = \JWT::decode($oauthToken, $this->secret);
    
    $responseData = [
      'iat' => time(),
      'userId' => 1, 
      'email'  => $payload->email,
      'roles'  => ['ROLE_USER','ROLE_SRA']
    ];
    $authToken = \JWT::encode($responseData, $this->secret);
    
    $responseData['authToken'] = $authToken;
    
    return new JsonResponse($responseData,202);
    
  }
}
