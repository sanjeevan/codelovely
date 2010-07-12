<?php

/**
 * Article form base class.
 *
 * @method Article getObject() Returns the current form's model object
 *
 * @package    socialhub
 * @subpackage form
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseArticleForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'user_id'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'thing_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Thing'), 'add_empty' => true)),
      'username'             => new sfWidgetFormInputText(),
      'title'                => new sfWidgetFormInputText(),
      'url'                  => new sfWidgetFormInputText(),
      'summary'              => new sfWidgetFormTextarea(),
      'fulldescription'      => new sfWidgetFormTextarea(),
      'code'                 => new sfWidgetFormTextarea(),
      'code_language'        => new sfWidgetFormInputText(),
      'question'             => new sfWidgetFormTextarea(),
      'total_comments'       => new sfWidgetFormInputText(),
      'has_thumbnails'       => new sfWidgetFormInputCheckbox(),
      'flavour'              => new sfWidgetFormChoice(array('choices' => array('link' => 'link', 'code' => 'code', 'question' => 'question', 'snapshot' => 'snapshot'))),
      'published'            => new sfWidgetFormInputCheckbox(),
      'summary_html'         => new sfWidgetFormTextarea(),
      'fulldescription_html' => new sfWidgetFormTextarea(),
      'question_html'        => new sfWidgetFormTextarea(),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
      'slug'                 => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'user_id'              => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'thing_id'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Thing'), 'required' => false)),
      'username'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'title'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'url'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'summary'              => new sfValidatorString(array('required' => false)),
      'fulldescription'      => new sfValidatorString(array('required' => false)),
      'code'                 => new sfValidatorString(array('required' => false)),
      'code_language'        => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'question'             => new sfValidatorString(array('required' => false)),
      'total_comments'       => new sfValidatorInteger(array('required' => false)),
      'has_thumbnails'       => new sfValidatorBoolean(array('required' => false)),
      'flavour'              => new sfValidatorChoice(array('choices' => array(0 => 'link', 1 => 'code', 2 => 'question', 3 => 'snapshot'), 'required' => false)),
      'published'            => new sfValidatorBoolean(array('required' => false)),
      'summary_html'         => new sfValidatorString(array('required' => false)),
      'fulldescription_html' => new sfValidatorString(array('required' => false)),
      'question_html'        => new sfValidatorString(array('required' => false)),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
      'slug'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Article', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('article[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Article';
  }

}
