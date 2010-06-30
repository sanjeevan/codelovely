<?php

class ApplicationActions extends sfActions
{
  public function preExecute()
  {
    if ($this->getUser()->isAuthenticated()){
      $messaging = new sfMessaging($this->getUser()->getModel());
      $mail_checked = $this->getUser()->getAttribute('mailChecked', false, 'mail');
      if ($mail_checked === false){
        $this->getUser()->setAttribute('hasNewMail', $messaging->hasNewMail(), 'mail');
      }

      if ($this->getUser()->isAdmin()){
        //sfWebResponse::
        $this->getResponse()->addStylesheet('admin.css', sfWebResponse::LAST);
      }
    }
  }
}