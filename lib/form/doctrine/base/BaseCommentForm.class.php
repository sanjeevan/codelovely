<?php

/**
 * Comment form base class.
 *
 * @method Comment getObject() Returns the current form's model object
 *
 * @package    socialhub
 * @subpackage form
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCommentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'thing_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Thing'), 'add_empty' => true)),
      'user_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'article_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Article'), 'add_empty' => true)),
      'reply_id'     => new sfWidgetFormInputText(),
      'reply_depth'  => new sfWidgetFormInputText(),
      'content'      => new sfWidgetFormTextarea(),
      'content_html' => new sfWidgetFormTextarea(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'thing_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Thing'), 'required' => false)),
      'user_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'article_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Article'), 'required' => false)),
      'reply_id'     => new sfValidatorInteger(array('required' => false)),
      'reply_depth'  => new sfValidatorInteger(array('required' => false)),
      'content'      => new sfValidatorString(array('required' => false)),
      'content_html' => new sfValidatorString(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('comment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Comment';
  }

}
