<?php

/**
 * message actions.
 *
 * @package    codelovely
 * @subpackage message
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class messageActions extends ApplicationActions
{
  public function executeSend(sfWebRequest $request)
  {
    $this->form = new sfMessageOutboxForm();
    $this->form->setDefaults(array('to_str' => $request->getParameter('to')));
    
    if ($request->isMethod('post')){
      $parameters = $request->getParameter('sf_message_outbox');
      $this->form->bind($parameters);

      if ($this->form->isValid()){
        // send the message to the recipients
        $messaging = new sfMessaging($this->getUser()->getModel());
        $messaging->send($parameters['to_str'], $parameters['title'], $parameters['message']);
        
        $this->getUser()->setFlash('notice', 'Message sent', false);
      } else {
        $this->getUser()->setFlash('error', 'There was an error sending the message', false);
      }
    }
  } 
  
  public function executeInbox(sfWebRequest $request)
  {
    $q = Doctrine_Query::create()->select('m.*')
        ->from('sfMessageInbox m')
        ->where('m.user_id = ?', $this->getUser()->getId())
        ->orderBy('m.created_at DESC');
    
    $this->messages = $q->execute();
  }
  
  public function executeOutbox(sfWebRequest $request)
  {
    $q = Doctrine_Query::create()->select('m.*')
        ->from('sfMessageOutbox m')
        ->where('m.user_id = ?', $this->getUser()->getId())
        ->orderBy('m.created_at DESC');
    
    $this->messages = $q->execute();
  }
  
  public function executeShow(sfWebRequest $request)
  {
    $id   = $request->getParameter('messageid');
    $type = $request->getParameter('type');
    
    switch ($type){
      case 'outbox':
        $this->message = Doctrine::getTable('sfMessageOutbox')->find($id);
        break;
      case 'inbox':
        $this->message = Doctrine::getTable('sfMessageInbox')->find($id);
        $this->message->markAsRead();
        break;
    }
    
    if ($this->message->getUserId() != $this->getUser()->getId()){
      $this->getUser()->setFlash('error', 'You cannot view this message');
      $this->redirect('@homepage');
    }
    
    if ($type == 'inbox'){
      $this->form = new sfMessageOutboxForm();
      $this->form->setDefaults(array(
        'to_str'  => $this->message->getFromUser()->getUsername(),
        'message' => "\n\n ---------- \n" . $this->message->getMessage(),
        'title'   => 'Re: ' . $this->message->getTitle()
      ));
    }
  }
}
