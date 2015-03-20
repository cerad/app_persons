<?php

namespace Cerad\Bundle\AuthBundle\OAuth;

use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpFoundation\Request;
//  Symfony\Component\HttpFoundation\Session\Session;

class ProviderManager
{
    const STORAGE_KEY_PROVIDER_NAME = 'cerad_user__oauth__provider_name';
    const STORAGE_KEY_STATE         = 'cerad_user__oauth__state';
    const STORAGE_KEY_REQUEST_TOKEN = 'cerad_user__oauth__request_token';
  //const STORAGE_KEY_ACCESS_TOKEN  = 'cerad_user__oauth__access_token';
    
    protected $map;
    protected $httpUtils;
    protected $redirectUriName = 'cerad_auth__oauth__redirect';
    
    public function __construct(HttpUtils $httpUtils,$providers)
    {
        $this->httpUtils = $httpUtils;
        
        foreach($providers as $provider) {
            $provider['instance'] = null;
            $this->map[$provider['name']] = $provider;
        }
    }
  public function getProviders() { return $this->map; }

  public function createFromName($name)
  {
    if (!isset($this->map[$name])) {
      throw new \Exception(sprintf("Cerad Oauth Provider not found: %s",$name));
    }
    if (isset( $this->map[$name]['instance'])) { 
        return $this->map[$name]['instance']; 
    }
    // The signed state token
    $state = $name . '.state';
    
    // Create it
    $info = $this->map[$name];
    $class = $info['class'];
        
    $instance = new $class(
      $info['name'],
      $info['client_id'],
      $info['client_secret'],
      $state,
      $this->httpUtils,
      $this->redirectUriName
    );
    $this->map[$name]['instance'] = $instance;
        
    // Maybe unset the other stuff?
    // $this->storage->set(self::STORAGE_KEY_PROVIDER_NAME,$name);

    return $instance;
  }
    // Process a redirection from the provider site
    public function createFromRequest(Request $request)
    {
        // OAuth1 will not have state, how to handle twitter?
        $requestState = $request->query->get('state');
        
      //if ($requestState && ($requestState != $storageState)) {
      //    throw new \Exception("OAuth State Mismatch");
      //}
        
        // Name was stored in session
        $name = explode('.',$requestState)[0];
        
        return $this->createFromName($name);
    }
    public function getRedirectUri(Request $request)
    {
        // http://local.oauth.zayso.org/oauth/callback
        return $this->httpUtils->generateUri($request,$this->redirectUriName);
    }
    public function generateState()
    {
        $state = md5(microtime(true).uniqid('', true));
        
      //$this->storage->set(self::STORAGE_KEY_STATE,$state);
        
        return $state;
    }
    public function getStorage() { return $this->storage; }
}