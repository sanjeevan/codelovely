<?php

class NewUserForm extends BaseUserForm
{
  private $captcha_enabled = false;
  private $invite_enabled = false;
  
  const USERNAME_REGEX = "/^[-_a-z0-9]+$/i";
  
  public function configure()
  {
    
    unset($this['created_at']);
    unset($this['updated_at']);
    unset($this['last_login']);
    unset($this['salt']);
    unset($this['is_admin']);
            
    $this->widgetSchema['password']   = new sfWidgetFormInputPassword();
    $this->widgetSchema['password2']  = new sfWidgetFormInputPassword();
    
    // help
    $this->widgetSchema->setHelp('password', 'Minimum of 6 characters');
    $this->widgetSchema->setHelp('username', 'Allowed characters: A-Z, 0-9, - and _');
    
    $this->validatorSchema['username'] = new sfValidatorAnd(array(
       new sfValidatorString(array('max_length' => 30, 'required' => false)),
       new sfValidatorCallback(array('callback' => array($this, 'existingUsernameCheck'))),
       new sfValidatorRegex(array('pattern' => self::USERNAME_REGEX))
    ));
    
    $this->validatorSchema['email'] = new sfValidatorAnd(array(
      new sfValidatorEmail(array('required' => true)),
      new sfValidatorCallback(array('callback' => array($this, 'existingEmailCheck')))
    ));
    
    $this->validatorSchema['password']  = new sfValidatorString(array('min_length' => 6, 'required' => true));
    $this->validatorSchema['password2'] = new sfValidatorString(array('min_length' => 6, 'required' => true));
    $this->validatorSchema['firstname'] = new sfValidatorString(array('required' => true, 'max_length' => 50));
    $this->validatorSchema['lastname']  = new sfValidatorString(array('required' => true, 'max_length' => 50));
    
    $this->widgetSchema->setLabels(array(
      'password'  => 'Password',
      'firstname' => 'Firstname',
      'password2' => 'Confirm password'
    ));
        
    $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
      new sfValidatorSchemaCompare('password', '==', 'password2', array(), array('invalid' => 'Passwords do not match'))
    )));
  }
  
  /**
   * Enable the captcha field
   * 
   */
  public function enableCaptcha()
  {
    $public_key = sfConfig::get('app_recaptcha_public');
    $private_key = sfConfig::get('app_recaptcha_private');
    
    $this->widgetSchema['captcha'] = new sfWidgetFormReCaptcha(array('public_key' => $public_key));
    $this->validatorSchema['captcha'] = new sfValidatorReCaptcha(array('private_key' => $private_key));
    
    $this->captcha_enabled = true;
  }
  
  /**
   * Returns truen if the captcha field is enabled
   * 
   * @return boolean
   */
  public function isCaptchaEnabled()
  {
    return $this->captcha_enabled;
  }
  
  /**
   * Enable the invite field
   * 
   */
  public function enableInvites()
  {
    $this->widgetSchema['invite'] = new sfWidgetFormInputText();
    $this->widgetSchema->setHelp('invite', 'You need an invite code to sign up for the beta');
    $this->validatorSchema['invite'] = new sfValidatorInviteCode();
    $this->invite_enabled = true;
  }
    
  /**
   * Check if username already exists
   *
   * @param sfValidator $validator
   * @param mixed $value
   * @throws sfValidatorError
   */
  public function existingUsernameCheck($validator, $value)
  {
    $user = Doctrine::getTable('User')->findOneByUsername($value);

    if ($user){
      throw new sfValidatorError($validator, 'That username already exists');
    }

    return $value;
  }
  
  /**
   * Check if email address already exists
   * 
   * @param sfValidator $validator
   * @param mixed $value
   * @throws sfValidatorError
   */
  public function existingEmailCheck($validator, $value)
  {
    $user = Doctrine::getTable('User')->findOneByEmail($value);

    if ($user){
      throw new sfValidatorError($validator, 'That email address is already in use');
    }

    return $value;
  }
  
  /**
   * Called when object is saved, it sets the hashed password
   * 
   * (non-PHPdoc)
   * @see form/addon/sfFormObject::updateObject()
   */
  public function updateObject($values = null)
  {
    $object = parent::updateObject($values);
    $password = $this->taintedValues['password'];
    
    $object->setPlaintextPassword($password);
    
    return $object;
  }
  
  public function save($con = null)
  {
    $user = parent::save($con);
    
    if ($this->invite_enabled){
      $invite = Doctrine::getTable('Invite')->findOneByCode($this->getValue('invite'));
      if ($invite){
        $invite->setInvitedUserId($user->getId());
        $invite->setStatus('used');
        $invite->save();
      }
    }
    
    return $user;
  }
}