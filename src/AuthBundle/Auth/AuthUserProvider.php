<?php

namespace Cerad\Bundle\AuthBundle\Auth;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class AuthUserProvider implements UserProviderInterface
{
  protected $users;
  
  public function __construct($users)
  {
    $this->users = $users;
  }
  // Not sure if I really need this, Use this from auth controller?
  public function getUserForAuthToken($authUserInfo)
  {
    $username = $authUserInfo['username'];
    $roles    = $authUserInfo['roles'];
    
    if (isset($this->users[$username]))
    {
      return new User($username,null,$this->users[$username]['roles']);
    }
    return new User($username,null,$roles);
  }
  public function loadUserByUsername($username)
  {
    throw new UnsupportedUserException();
  }
  public function refreshUser(UserInterface $user)
  {
    // this is used for storing authentication in the session
    // but in this example, the token is sent in each request,
    // so authentication can be stateless. Throwing this exception
    // is proper to make things stateless
    throw new UnsupportedUserException();
  }
  public function supportsClass($class)
  {
    return 'Symfony\Component\Security\Core\User\User' === $class;
  }
}