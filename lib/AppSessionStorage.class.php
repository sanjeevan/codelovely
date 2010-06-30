<?php

class AppSessionStorage extends sfSessionStorage
{
  public function initialize($options = array())
  {
    $context = sfContext::getInstance();
    
    if ( $context->getRequest()->getParameter('SESSID') )
    {
      if ($value = $context->getRequest()->getParameter('SESSID'))
      {
        // set session name
        $name = $options['session_name'];
        session_name($name);
        session_id($value);
      }
    }

    parent::initialize($options);
  }
}
