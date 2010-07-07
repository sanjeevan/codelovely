<?php

/**
 * thing actions.
 *
 * @package    codelovely
 * @subpackage thing
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class thingActions extends sfActions
{
  /**
  * Up vote a thing
  *
  */
  public function executeAjaxUpVote(sfWebRequest $request)
  {
    $thing_id = $request->getParameter('thingid');
    $thing = Doctrine::getTable('Thing')->find($thing_id);
    
    if (!$thing)
    {
      return $this->renderText('Thing not found!');
    }
    
    $vote = $thing->voteUp($this->getUser()->getModel());
    $thing->save();
    
    $msg = array();
    $msg['score'] = $thing->score;
    
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
            
    if ($vote == null)
    {
      $msg['action'] = 'removed';
      return $this->renderText(json_encode($msg));
    }
    
    $msg['action'] = $vote->type;
    
    return $this->renderText(json_encode($msg));
  }
  
  /**
  * Down vote a thing
  *
  */
  public function executeAjaxDownVote(sfWebRequest $request)
  {
    $thing_id = $request->getParameter('thingid');
    $thing = Doctrine::getTable('Thing')->find($thing_id);
    
    if (!$thing)
    {
      return $this->renderText('Thing not found!');
    }
    
    $vote = $thing->voteDown($this->getUser()->getModel());
    $thing->save();
    
    $msg = array();
    $msg['score'] = $thing->score;
    
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
        
    if ($vote == null)
    {
      $msg['action'] = 'removed';
      return $this->renderText(json_encode($msg));
    }
    
    $msg['action'] = $vote->type;
    
    return $this->renderText(json_encode($msg));
  }
}
