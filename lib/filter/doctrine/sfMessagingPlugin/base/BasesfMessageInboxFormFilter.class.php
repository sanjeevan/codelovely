<?php

/**
 * sfMessageInbox filter form base class.
 *
 * @package    socialhub
 * @subpackage filter
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasesfMessageInboxFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'from_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('FromUser'), 'add_empty' => true)),
      'to_str'        => new sfWidgetFormFilterInput(),
      'title'         => new sfWidgetFormFilterInput(),
      'message'       => new sfWidgetFormFilterInput(),
      'is_read'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'last_accessed' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'user_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'from_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('FromUser'), 'column' => 'id')),
      'to_str'        => new sfValidatorPass(array('required' => false)),
      'title'         => new sfValidatorPass(array('required' => false)),
      'message'       => new sfValidatorPass(array('required' => false)),
      'is_read'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'last_accessed' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('sf_message_inbox_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfMessageInbox';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'user_id'       => 'ForeignKey',
      'from_id'       => 'ForeignKey',
      'to_str'        => 'Text',
      'title'         => 'Text',
      'message'       => 'Text',
      'is_read'       => 'Boolean',
      'last_accessed' => 'Date',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
