<?php
namespace Cerad\Bundle\AuthBundle\OAuth;

use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpFoundation\Request;

class ProviderManager
{   
    protected $providers;
    protected $httpUtils;
    protected $redirectUriName;
    protected $secret;
    
    public function __construct(HttpUtils $httpUtils,$redirectUriName,$providers,$secret)
    {
        $this->httpUtils       = $httpUtils;
        $this->redirectUriName = $redirectUriName;
        
        foreach($providers as $provider) {
            $provider['instance'] = null;
            $this->providers[$provider['name']] = $provider;
        }
        $this->secret = $secret;
    }
  public function getProviders() { return $this->providers; }

  public function createFromName($name,$state = null)
  {
    if (!isset($this->providers[$name])) {
      throw new \Exception(sprintf("Cerad Oauth Provider not found: %s",$name));
    }
    if (isset( $this->providers[$name]['instance'])) { 
        return $this->providers[$name]['instance']; 
    }
    // The signed state token
    if (!$state) $state = \JWT::encode(['name' => $name, 'random' => uniqid()], $this->secret);
    
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
    $state = $request->query->get('state');
        
    // Toss exception if tampered with
    $info = \JWT::decode($state,$this->secret);
        
    return $this->createFromName($info->name,$state);
  }
}