<?php

/**
 * File filter form base class.
 *
 * @package    socialhub
 * @subpackage filter
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseFileFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'filename'    => new sfWidgetFormFilterInput(),
      'filesize'    => new sfWidgetFormFilterInput(),
      'extension'   => new sfWidgetFormFilterInput(),
      'mimetype'    => new sfWidgetFormFilterInput(),
      'location'    => new sfWidgetFormFilterInput(),
      'meta_width'  => new sfWidgetFormFilterInput(),
      'meta_height' => new sfWidgetFormFilterInput(),
      'hash'        => new sfWidgetFormFilterInput(),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'filename'    => new sfValidatorPass(array('required' => false)),
      'filesize'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'extension'   => new sfValidatorPass(array('required' => false)),
      'mimetype'    => new sfValidatorPass(array('required' => false)),
      'location'    => new sfValidatorPass(array('required' => false)),
      'meta_width'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'meta_height' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hash'        => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('file_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'File';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'filename'    => 'Text',
      'filesize'    => 'Number',
      'extension'   => 'Text',
      'mimetype'    => 'Text',
      'location'    => 'Text',
      'meta_width'  => 'Number',
      'meta_height' => 'Number',
      'hash'        => 'Text',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
