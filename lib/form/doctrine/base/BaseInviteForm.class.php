<?php

/**
 * Invite form base class.
 *
 * @method Invite getObject() Returns the current form's model object
 *
 * @package    socialhub
 * @subpackage form
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseInviteForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'code'            => new sfWidgetFormInputText(),
      'user_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'status'          => new sfWidgetFormChoice(array('choices' => array('unused' => 'unused', 'sent' => 'sent', 'used' => 'used'))),
      'sent_at'         => new sfWidgetFormDateTime(),
      'sent_to'         => new sfWidgetFormInputText(),
      'invited_user_id' => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'code'            => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'user_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'status'          => new sfValidatorChoice(array('choices' => array(0 => 'unused', 1 => 'sent', 2 => 'used'), 'required' => false)),
      'sent_at'         => new sfValidatorDateTime(array('required' => false)),
      'sent_to'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'invited_user_id' => new sfValidatorInteger(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Invite', 'column' => array('code')))
    );

    $this->widgetSchema->setNameFormat('invite[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Invite';
  }

}
