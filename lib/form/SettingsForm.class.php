<?php

/**
 * Account settings form
 * 
 * @author sanjeevan
 *
 */
class SettingsForm extends BaseForm
{
	public function setup()
	{
		$this->setWidgets(array(
		  'email'       => new sfWidgetFormInputText(),
		  'curpasswd'   => new sfWidgetFormInputPassword(),
		  'password1'   => new sfWidgetFormInputPassword(),
		  'password2'   => new sfWidgetFormInputPassword(),
		));
		
		$this->setValidators(array(
		  'email'       => new sfValidatorAnd(array(
		    new sfValidatorEmail(array('required' => true)),
		    new sfValidatorCallback(array('callback' => array($this, 'checkDuplicateEmail')))
		  )),
		  'curpasswd'   => new sfValidatorCallback(array('callback' => array($this, 'isCurrentPassword'))),
		  'password1'   => new sfValidatorString(array('min_length' => 6, 'required' => false)),
		  'password2'   => new sfValidatorString(array('min_length' => 6, 'required' => false)) 
		));
		
		$this->widgetSchema->setLabels(array(
		  'curpasswd' => 'Current password',
		  'email'     => 'Email',
		  'password1' => 'New password',
		  'password2' => 'Confirm password'
		));
		
		$this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
      new sfValidatorSchemaCompare('password1', '==', 'password2', array(), array('invalid' => 'Passwords do not match'))
    )));
    
    $this->widgetSchema->setNameFormat('usersettings[%s]');
    $this->widgetSchema->setFormFormatterName('list');
    
    parent::setup();
	}
	
	public function isCurrentPassword($validator, $value)
	{
	  $sf_user = sfContext::getInstance()->getUser()->getModel();
	  
	  if ($sf_user->isCorrectPassword($value)){
	    return $value;
	  } else {
	    throw new sfValidatorError($validator, 'Current password incorrect');
	  }
	}
	
	public function checkDuplicateEmail($validator, $value)
	{
		$sf_user = sfContext::getInstance()->getUser()->getModel();
		
		$q = Doctrine_Query::create()->select('u.*')
		    ->from('User u')
		    ->where('u.email = ? and u.username != ?', array($value, $sf_user->getUsername()))
		    ->limit(1);
		    
		$user = $q->fetchOne();
		
		if ($user){
			throw new sfValidatorError($validator, 'That email is already in use');
		}
		
		return $value;
	}
}