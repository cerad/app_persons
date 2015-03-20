<?php
namespace Cerad\Bundle\AuthBundle\OAuth;

use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpFoundation\Request;

class ProviderManager
{   
    protected $providers;
    protected $httpUtils;
    protected $redirectUriName;
    
    public function __construct(HttpUtils $httpUtils,$redirectUriName,$providers)
    {
        $this->httpUtils       = $httpUtils;
        $this->redirectUriName = $redirectUriName;
        
        foreach($providers as $provider) {
            $provider['instance'] = null;
            $this->providers[$provider['name']] = $provider;
        }
    }
  public function getProviders() { return $this->providers; }

  public function createFromName($name)
  {
    if (!isset($this->providers[$name])) {
      throw new \Exception(sprintf("Cerad Oauth Provider not found: %s",$name));
    }
    if (isset( $this->providers[$name]['instance'])) { 
        return $this->providers[$name]['instance']; 
    }
    // The signed state token
    $state = $name . '.state';
    
    // Create it
    $info = $this->providers[$name];
    $class = $info['class'];
        
    $instance = new $class(
      $info['name'],
      $info['client_id'],
      $info['client_secret'],
      $state,
      $this->httpUtils,
      $this->redirectUriName
    );
    $this->providers[$name]['instance'] = $instance;
    
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
}