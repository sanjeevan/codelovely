<?php

class PersonalSettingsForm extends BaseForm
{  
  public function setup()
  {
    $this->setWidgets(array(
      'firstname'   => new sfWidgetFormInputText(),
      'lastname'    => new sfWidgetFormInputText(),
      'twitter'     => new sfWidgetFormInputText(),
      'website_url' => new sfWidgetFormInputText(),
      'skills'      => new sfWidgetFormSelect(array('choices' => $this->getSkillsChoices()))
    ));
    
    $this->setValidators(array(
      'firstname'   => new sfValidatorString(array('required' => false)),
      'lastname'    => new sfValidatorString(array('required' => false)),
      'twitter'     => new sfValidatorString(array('required' => false)),
      'website_url' => new sfValidatorUrl(array('required' => false)),
      'skills'      => new sfValidatorChoice(array('choices' => $this->getSkillsChoices(true)))
    ));
    
    $this->widgetSchema->setLabels(array(
      'firstname' => 'Firstname',
      'lastname'  => 'Lastname'
    ));
    
    $this->widgetSchema->setNameFormat('personal_settings[%s]');
    $this->widgetSchema->setFormFormatterName('list');
  }
  
  public function getSkillsChoices($validation = false)
  {
    $skills = array(
      'designer' => 'Designer',
      'developer' => 'Developer',
      'designer_developer' => 'Designer &amp; Developer'
    );
    
    return $validation ? array_keys($skills) : $skills;
  }
}