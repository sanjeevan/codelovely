<?php

/**
 * BlogEntry filter form base class.
 *
 * @package    socialhub
 * @subpackage filter
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseBlogEntryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'title'        => new sfWidgetFormFilterInput(),
      'summary'      => new sfWidgetFormFilterInput(),
      'summary_html' => new sfWidgetFormFilterInput(),
      'body'         => new sfWidgetFormFilterInput(),
      'body_html'    => new sfWidgetFormFilterInput(),
      'status'       => new sfWidgetFormFilterInput(),
      'published_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'slug'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'title'        => new sfValidatorPass(array('required' => false)),
      'summary'      => new sfValidatorPass(array('required' => false)),
      'summary_html' => new sfValidatorPass(array('required' => false)),
      'body'         => new sfValidatorPass(array('required' => false)),
      'body_html'    => new sfValidatorPass(array('required' => false)),
      'status'       => new sfValidatorPass(array('required' => false)),
      'published_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'slug'         => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('blog_entry_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'BlogEntry';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'user_id'      => 'ForeignKey',
      'title'        => 'Text',
      'summary'      => 'Text',
      'summary_html' => 'Text',
      'body'         => 'Text',
      'body_html'    => 'Text',
      'status'       => 'Text',
      'published_at' => 'Date',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
      'slug'         => 'Text',
    );
  }
}
