<?php

/**
 * User form base class.
 *
 * @method User getObject() Returns the current form's model object
 *
 * @package    socialhub
 * @subpackage form
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'username'   => new sfWidgetFormInputText(),
      'email'      => new sfWidgetFormInputText(),
      'firstname'  => new sfWidgetFormInputText(),
      'lastname'   => new sfWidgetFormInputText(),
      'password'   => new sfWidgetFormInputText(),
      'salt'       => new sfWidgetFormInputText(),
      'is_admin'   => new sfWidgetFormInputCheckbox(),
      'last_login' => new sfWidgetFormDateTime(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'username'   => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'email'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'firstname'  => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'lastname'   => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'password'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'salt'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_admin'   => new sfValidatorBoolean(array('required' => false)),
      'last_login' => new sfValidatorDateTime(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'User';
  }

}
