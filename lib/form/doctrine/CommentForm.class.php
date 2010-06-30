<?php

/**
 * Comment form.
 *
 * @package    frostty
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CommentForm extends BaseCommentForm
{
  public function configure()
  {
    $this->useFields(array(
      'content'
    ));
    
    $this->setValidator('content', new sfValidatorAnd(array(
      new sfValidatorString(array('required' => true)),
      new sfValidatorCallback(array('callback' => array($this, 'commentValidator')))
    )));
    
    $this->widgetSchema->setLabel('content', false);
  }
  
  public function commentValidator($validator, $value)
  {
    if ($value == sfConfig::get('app_comment_default'))
    {
      throw new sfValidatorError($validator, 'Please enter a comment');
    }
    
    return $value;
  }
}
