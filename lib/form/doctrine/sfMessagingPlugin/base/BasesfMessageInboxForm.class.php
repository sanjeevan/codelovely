<?php

/**
 * sfMessageInbox form base class.
 *
 * @method sfMessageInbox getObject() Returns the current form's model object
 *
 * @package    socialhub
 * @subpackage form
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasesfMessageInboxForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'user_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'from_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('FromUser'), 'add_empty' => true)),
      'to_str'        => new sfWidgetFormInputText(),
      'title'         => new sfWidgetFormInputText(),
      'message'       => new sfWidgetFormTextarea(),
      'is_read'       => new sfWidgetFormInputCheckbox(),
      'last_accessed' => new sfWidgetFormDateTime(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'user_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'from_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('FromUser'), 'required' => false)),
      'to_str'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'title'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'message'       => new sfValidatorString(array('required' => false)),
      'is_read'       => new sfValidatorBoolean(array('required' => false)),
      'last_accessed' => new sfValidatorDateTime(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('sf_message_inbox[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfMessageInbox';
  }

}
