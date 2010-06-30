<?php

/**
 * File form base class.
 *
 * @method File getObject() Returns the current form's model object
 *
 * @package    socialhub
 * @subpackage form
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseFileForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'filename'    => new sfWidgetFormInputText(),
      'filesize'    => new sfWidgetFormInputText(),
      'extension'   => new sfWidgetFormInputText(),
      'mimetype'    => new sfWidgetFormInputText(),
      'location'    => new sfWidgetFormInputText(),
      'meta_width'  => new sfWidgetFormInputText(),
      'meta_height' => new sfWidgetFormInputText(),
      'hash'        => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'filename'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'filesize'    => new sfValidatorInteger(array('required' => false)),
      'extension'   => new sfValidatorString(array('max_length' => 25, 'required' => false)),
      'mimetype'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'location'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'meta_width'  => new sfValidatorInteger(array('required' => false)),
      'meta_height' => new sfValidatorInteger(array('required' => false)),
      'hash'        => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('file[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'File';
  }

}
