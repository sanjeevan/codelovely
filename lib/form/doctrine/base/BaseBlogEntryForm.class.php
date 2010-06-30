<?php

/**
 * BlogEntry form base class.
 *
 * @method BlogEntry getObject() Returns the current form's model object
 *
 * @package    socialhub
 * @subpackage form
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseBlogEntryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'user_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'title'        => new sfWidgetFormInputText(),
      'summary'      => new sfWidgetFormTextarea(),
      'summary_html' => new sfWidgetFormTextarea(),
      'body'         => new sfWidgetFormTextarea(),
      'body_html'    => new sfWidgetFormTextarea(),
      'status'       => new sfWidgetFormInputText(),
      'published_at' => new sfWidgetFormDateTime(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
      'slug'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'user_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'title'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'summary'      => new sfValidatorString(array('required' => false)),
      'summary_html' => new sfValidatorString(array('required' => false)),
      'body'         => new sfValidatorString(array('required' => false)),
      'body_html'    => new sfValidatorString(array('required' => false)),
      'status'       => new sfValidatorString(array('max_length' => 15, 'required' => false)),
      'published_at' => new sfValidatorDateTime(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
      'slug'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'BlogEntry', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('blog_entry[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'BlogEntry';
  }

}
