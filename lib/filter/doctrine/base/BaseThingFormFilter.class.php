<?php

/**
 * Thing filter form base class.
 *
 * @package    socialhub
 * @subpackage filter
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseThingFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hot'          => new sfWidgetFormFilterInput(),
      'ups'          => new sfWidgetFormFilterInput(),
      'downs'        => new sfWidgetFormFilterInput(),
      'score'        => new sfWidgetFormFilterInput(),
      'is_published' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'hot'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'ups'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'downs'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'score'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_published' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('thing_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Thing';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'hot'          => 'Number',
      'ups'          => 'Number',
      'downs'        => 'Number',
      'score'        => 'Number',
      'is_published' => 'Boolean',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
