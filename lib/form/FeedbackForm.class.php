<?php

class FeedbackForm extends BaseForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'type'    => new sfWidgetFormSelect(array('choices' => $this->getFeedbackTypeOptions())),  
      'subject' => new sfWidgetFormInputText(),
      'email'   => new sfWidgetFormInputText(),
      'body'    => new sfWidgetFormTextarea()
    ));
    
    $this->setValidators(array(
      'subject' => new sfValidatorString(array('required' => true)),
      'email'   => new sfValidatorEmail(array('required' => true)),
      'body'    => new sfValidatorString(array('required' => true)),
      'type'    => new sfValidatorChoice(array('choices' => array_keys($this->getFeedbackTypeOptions())))
    ));
    
    $this->widgetSchema->setNameFormat('feedback[%s]');
    $this->widgetSchema->setFormFormatterName('list');
    
    parent::setup();
  }
  
  public function getFeedbackTypeOptions()
  {
    $options = array(
      'general'     => 'General',
      'bug'         => 'Bug',
      'question'    => 'Question',
      'feature'     => 'Feature request'
    );
    
    return $options;
  }
}