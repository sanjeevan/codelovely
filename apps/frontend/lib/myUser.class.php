<?php

class myUser extends sfBasicSecurityUser
{
  private $model = null;
    
  /**
   * get user model
   * 
   * @return User
   */
  public function getModel()
  {
    if ($this->model === null)
    {
      $this->model = Doctrine::getTable('User')->find($this->getAttribute('user_id'));
    }
    
    return $this->model;
  }

  public function isAdmin()
  {
    return $this->getModel()->getIsAdmin();
  }
  
  /**
  * Get the user ID
  * 
  * @return integer
  */
  public function getId()
  {
    return $this->getAttribute('user_id');
  }
  
  /**
  * Sign the user out
  */
  public function signOut()
  {
    $this->setAuthenticated(false);
    $this->clearCredentials();
    $this->getAttributeHolder()->clear();
  }
 
  /**
  * Sign in the user, and update the last login time
  */
  public function signIn(User $user)
  {
    $this->clearCredentials();
    $this->setAuthenticated(true);

    $this->setAttribute('user_id', $user->id);
    
    $dateFormat = new sfDateFormat();
    $user->last_login = $dateFormat->format(time(), 'I');
    $user->save();
    
    return true;
  }
}
