<?php

class SendInviteForm extends BaseForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'  => new sfWidgetFormInputText(),
      'email' => new sfWidgetFormInputText()
    ));
    
    $this->setValidators(array(
      'email' => new sfValidatorEmail(array('required' => true)),
      'name'  => new sfValidatorString(array('required' => true)),
    ));
    
    $this->widgetSchema->setNameFormat('send_invite[%s]');
    $this->widgetSchema->setFormFormatterName('list');
    
    parent::setup();
  }
}