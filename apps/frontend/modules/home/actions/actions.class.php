<?php

/**
 * home actions.
 *
 * @package    codelovely
 * @subpackage home
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class homeActions extends ApplicationActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    
  }
  
  public function executeFeedback(sfWebRequest $request)
  {
    $this->form = new FeedbackForm();
    
    if ($this->getUser()->isAuthenticated()){
      $this->form->setDefault('email', $this->getUser()->getModel()->getEmail());
    }
    
    if ($request->isMethod('post')){
      $params = $request->getParameter($this->form->getName());
      $this->form->bind($params);

      if ($this->form->isValid()){
        $queue = new RedisJobQueue(SendEmailWorker::QUEUE_NAME);
        
        $vars = array(
          'body'    => $this->form->getValue('body'),
          'type'    => $this->form->getValue('type'),
          'subject' => $this->form->getValue('subject'),
          'email'   => $this->form->getValue('email')
        );
        $message = $this->getPartial('mail/feedback', $vars);
        
        $job = array(
          'to' => 'sanjeevan.a+codelovely@gmail.com',
          'subject' => 'New feedback for codelovely.com',
          'message' => $message,
          'from' => 'noreply@' . $_SERVER['HTTP_HOST']
        );
        
        $queue = new RedisJobQueue(SendEmailWorker::QUEUE_NAME);
        $queue->addJob($job, false, false);
        
        $this->getUser()->setFlash('notice', 'Your feedback has been sent!', false);   
      }
    }
  }
}
