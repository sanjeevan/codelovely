<?php

/**
 * Thing form base class.
 *
 * @method Thing getObject() Returns the current form's model object
 *
 * @package    socialhub
 * @subpackage form
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseThingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'hot'          => new sfWidgetFormInputText(),
      'ups'          => new sfWidgetFormInputText(),
      'downs'        => new sfWidgetFormInputText(),
      'score'        => new sfWidgetFormInputText(),
      'is_published' => new sfWidgetFormInputCheckbox(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'hot'          => new sfValidatorNumber(array('required' => false)),
      'ups'          => new sfValidatorInteger(array('required' => false)),
      'downs'        => new sfValidatorInteger(array('required' => false)),
      'score'        => new sfValidatorInteger(array('required' => false)),
      'is_published' => new sfValidatorBoolean(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('thing[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Thing';
  }

}
