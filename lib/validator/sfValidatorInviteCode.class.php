<?php

class sfValidatorInviteCode extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('invalid_code', 'This is not a valid invite code');
    $this->addMessage('used_code', 'This code has already been used');
  }
  
  protected function doClean($value)
  {
    $invite = Doctrine::getTable('Invite')->findOneByCode($value);
    
    if (!$invite){
      throw new sfValidatorError($this, 'invalid_code');
    }
    
    if ($invite->getStatus() == 'used'){
      throw new sfValidatorError($this, 'used_code');
    }
    
    return $value;
  }
}