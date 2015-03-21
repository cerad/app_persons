<?php

namespace Cerad\Bundle\AuthBundle\OAuth;

//  Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class OAuthController
{
  private $secret;
  private $providerManager;
  
  public function __construct($providerManager,$secret)
  {
    $this->secret = $secret;
    $this->providerManager = $providerManager;
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
  // /oauth/tokens?provider=providerName
  public function tokensAction(Request $request)
  {
    $providerName = $request->query->get('provider');
    
    $provider = $this->providerManager->createFromName($providerName);
    
    $authorizationUrl = $provider->getAuthorizationUrl($request);
    
  //return new Response('OAuth Tokens ' . $providerName . ' ' . $authorizationUrl);
    
    return new RedirectResponse($authorizationUrl);
  }
}
