<?php

/**
 * Article filter form base class.
 *
 * @package    socialhub
 * @subpackage filter
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseArticleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'thing_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Thing'), 'add_empty' => true)),
      'username'             => new sfWidgetFormFilterInput(),
      'title'                => new sfWidgetFormFilterInput(),
      'url'                  => new sfWidgetFormFilterInput(),
      'summary'              => new sfWidgetFormFilterInput(),
      'fulldescription'      => new sfWidgetFormFilterInput(),
      'code'                 => new sfWidgetFormFilterInput(),
      'code_language'        => new sfWidgetFormFilterInput(),
      'question'             => new sfWidgetFormFilterInput(),
      'total_comments'       => new sfWidgetFormFilterInput(),
      'has_thumbnails'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'flavour'              => new sfWidgetFormChoice(array('choices' => array('' => '', 'link' => 'link', 'code' => 'code', 'question' => 'question', 'snapshot' => 'snapshot'))),
      'published'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'summary_html'         => new sfWidgetFormFilterInput(),
      'fulldescription_html' => new sfWidgetFormFilterInput(),
      'question_html'        => new sfWidgetFormFilterInput(),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'slug'                 => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_id'              => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'thing_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Thing'), 'column' => 'id')),
      'username'             => new sfValidatorPass(array('required' => false)),
      'title'                => new sfValidatorPass(array('required' => false)),
      'url'                  => new sfValidatorPass(array('required' => false)),
      'summary'              => new sfValidatorPass(array('required' => false)),
      'fulldescription'      => new sfValidatorPass(array('required' => false)),
      'code'                 => new sfValidatorPass(array('required' => false)),
      'code_language'        => new sfValidatorPass(array('required' => false)),
      'question'             => new sfValidatorPass(array('required' => false)),
      'total_comments'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'has_thumbnails'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'flavour'              => new sfValidatorChoice(array('required' => false, 'choices' => array('link' => 'link', 'code' => 'code', 'question' => 'question', 'snapshot' => 'snapshot'))),
      'published'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'summary_html'         => new sfValidatorPass(array('required' => false)),
      'fulldescription_html' => new sfValidatorPass(array('required' => false)),
      'question_html'        => new sfValidatorPass(array('required' => false)),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'slug'                 => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('article_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Article';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'user_id'              => 'ForeignKey',
      'thing_id'             => 'ForeignKey',
      'username'             => 'Text',
      'title'                => 'Text',
      'url'                  => 'Text',
      'summary'              => 'Text',
      'fulldescription'      => 'Text',
      'code'                 => 'Text',
      'code_language'        => 'Text',
      'question'             => 'Text',
      'total_comments'       => 'Number',
      'has_thumbnails'       => 'Boolean',
      'flavour'              => 'Enum',
      'published'            => 'Boolean',
      'summary_html'         => 'Text',
      'fulldescription_html' => 'Text',
      'question_html'        => 'Text',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
      'slug'                 => 'Text',
    );
  }
}
