<?php

/**
 * invite actions.
 *
 * @package    socialhub
 * @subpackage invite
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class inviteActions extends sfActions
{
  public function executeRequest(sfWebRequest $request)
  {
    $this->form = new InviteRequestForm();
    
    if ($request->isMethod('post')){
      $params = $request->getParameter($this->form->getName());
      $this->form->bind($params);
      
      if ($this->form->isValid()){
        $invite_request = $this->form->save();
        $this->getUser()->setFlash('notice', "Sit tight. We'll send you an invite as soon as one becomes available.", false);
      }
    }
  }
  
  public function executeHidePromo()
  {
    $this->getUser()->setAttribute('showpromo', false, 'promo');
    $this->renderText('ok');
    return sfView::NONE;
  }
}
