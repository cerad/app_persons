<?php

namespace Cerad\Bundle\AuthBundle\Auth;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

use Symfony\Component\Security\Core\User\UserProviderInterface;


class AuthAuthenticator implements SimplePreAuthenticatorInterface
{
  protected $secret;

  public function __construct($secret)
  {
    $this->secret = $secret;
  }

  public function createToken(Request $request, $providerKey)
  {
    // Trick for exploring
    $authUserName = $request->query->get('auth');
    if ($authUserName)
    {
      $authUserInfo = ['username' => $authUserName, 'roles' => []];
      return new PreAuthenticatedToken('anon.',$authUserInfo,$providerKey);
    }
    // Or the header
    $authToken = $request->headers->get('Authorization');

    if (!$authToken) { return null; }
   
    // This will toss exception
    try
    {
      // Here or in authenticate token?
      $authUserInfo = (array)\JWT::decode($authToken,$this->secret);
    }
    catch (Exception $e)
    {
      throw new AuthenticationException('Invalid or Expired Auth Token');
    }
    return new PreAuthenticatedToken('anon.',$authUserInfo,$providerKey);
  }

  public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
  {
    $authToken = $token->getCredentials();
    
    $user = $userProvider->getUserForAuthToken($authToken);

    return new PreAuthenticatedToken
    (
      $user,null,$providerKey,$user->getRoles());
    }

  public function supportsToken(TokenInterface $token, $providerKey)
  {
    return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
  }
}