<?php

/**
 * invite actions.
 *
 * @package    socialhub
 * @subpackage invite
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class inviteActions extends ApplicationActions
{
  /**
   * Send out an invite code to guests
   * 
   * @param sfWebRequest $request
   */
  public function executeSend(sfWebRequest $request)
  {
    $invite_request = Doctrine::getTable('InviteRequest')->find($request->getParameter('id'));
    $this->forward404Unless($invite_request, "Invite request not found");

    $user = Doctrine::getTable('User')->findOneByUsername('sanjeevan');
    
    if ($user){
      try {
        $invite_request->sendInvitation($user, $this);
        $this->getUser()->setFlash('notice', 'Invitation has been sent to user');
        $invite_request->delete();
      } catch (Exception $e){
        AppLog::addException($e);
        $this->getUser()->setFlash('notice', 'There was a problem sending the invitation'); 
      }
    }
    
    $this->redirect($request->getReferer());
  }
  
  /**
   * List invite requests sent by guests
   * 
   * @param sfWebRequest $request
   */
  public function executeListRequests(sfWebRequest $request)
  {
    $q = Doctrine_Query::create()
      ->select('ir.*')
      ->from('InviteRequest ir')
      ->orderBy('ir.created_at desc');
      
    $this->requests = $q->execute();
  }
  
  /**
   * Allows guest to signup for an invite request
   * 
   * @param sfWebRequest $request
   */
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
    
  /**
   * Hide message about this site being invite only
   * 
   */
  public function executeHidePromo()
  {
    $this->getUser()->setAttribute('showpromo', false, 'promo');
    $this->renderText('ok');
    return sfView::NONE;
  }
}
