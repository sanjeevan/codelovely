<?php

class AdminFilter extends sfFilter
{
  protected $secure_modules = array(
    'blog' => array('*'),
  );
  
  protected $secure_url = 'default/secure';
  
  public function execute($chain)
  {
    if ($this->isFirstCall()){
      $run_check = false;
      
      if (!$this->getContext()->getUser()->isAuthenticated()){
        $chain->execute();
        return;
      }
      
      $user   = $this->getContext()->getUser();
      $action = $this->getContext()->getActionName();
      $module = $this->getContext()->getModuleName();
      
      if (array_key_exists($module, $this->secure_modules)){
        $secure_actions = $this->secure_modules[$module];
        if (in_array('*', $secure_actions) || in_array($action, $secure_actions)){
          $run_check = true;
        }
      }
      
      if ($run_check && !$user->isAdmin()){
        $this->getContext()->getController()->redirect($this->secure_url);
      }
    }
    
    $chain->execute();
  }
}