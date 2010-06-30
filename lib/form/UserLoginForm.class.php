<?php 

class UserLoginForm extends BaseForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'username'  => new sfWidgetFormInputText(),
      'password'  => new sfWidgetFormInputPassword()
    ));
    
    $this->setValidators(array(
      'username'  => new sfValidatorString(array('required' => true)),
      'password'  => new sfValidatorString(array('required' => true)),
    ));
    
    $this->widgetSchema->setNameFormat('login[%s]');
  }
  
  public function configure()
  {
    $this->widgetSchema->setFormFormatterName('list');
  }
}