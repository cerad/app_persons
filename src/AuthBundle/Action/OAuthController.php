<?php

namespace Cerad\Bundle\AuthBundle\Action;

//  Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class OAuthController
{
  private $providerManager;
  
  public function __construct($providerManager)
  {
    $this->providerManager = $providerManager;
  }
  public function callbackAction(Request $request)
  {
    $provider = $this->providerManager->createFromRequest($request);

    $accessTokenData = $provider->getAccessToken($request);
        
    $accessToken = $accessTokenData['access_token'];
        
    $userInfo = $provider->getUserInfo($accessTokenData);
    
    $encodingOptions = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;
    
    $json = json_encode($userInfo);
    
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
