<?php

/**
 * Comment filter form base class.
 *
 * @package    socialhub
 * @subpackage filter
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCommentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'thing_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Thing'), 'add_empty' => true)),
      'user_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'article_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Article'), 'add_empty' => true)),
      'reply_id'     => new sfWidgetFormFilterInput(),
      'reply_depth'  => new sfWidgetFormFilterInput(),
      'content'      => new sfWidgetFormFilterInput(),
      'content_html' => new sfWidgetFormFilterInput(),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'thing_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Thing'), 'column' => 'id')),
      'user_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'article_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Article'), 'column' => 'id')),
      'reply_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'reply_depth'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'content'      => new sfValidatorPass(array('required' => false)),
      'content_html' => new sfValidatorPass(array('required' => false)),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('comment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Comment';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'thing_id'     => 'ForeignKey',
      'user_id'      => 'ForeignKey',
      'article_id'   => 'ForeignKey',
      'reply_id'     => 'Number',
      'reply_depth'  => 'Number',
      'content'      => 'Text',
      'content_html' => 'Text',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
