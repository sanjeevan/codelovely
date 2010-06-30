<?php

class PersonalSettingsForm extends BaseForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'firstname' => new sfWidgetFormInputText(),
      'lastname'  => new sfWidgetFormInputText()
    ));
    
    $this->setValidators(array(
      'firstname' => new sfValidatorString(array('required' => false)),
      'lastname' => new sfValidatorString(array('required' => false))
    ));
    
    $this->widgetSchema->setLabels(array(
      'firstname' => 'Firstname',
      'lastname'  => 'Lastname'
    ));
    
    $this->widgetSchema->setNameFormat('personal_settings[%s]');
    $this->widgetSchema->setFormFormatterName('list');
  }
}