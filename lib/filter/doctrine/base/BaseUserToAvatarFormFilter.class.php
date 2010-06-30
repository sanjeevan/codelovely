<?php

/**
 * UserToAvatar filter form base class.
 *
 * @package    socialhub
 * @subpackage filter
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUserToAvatarFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'is_default' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'user_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'file_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('File'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'is_default' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'user_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'file_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('File'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('user_to_avatar_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UserToAvatar';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'is_default' => 'Boolean',
      'user_id'    => 'ForeignKey',
      'file_id'    => 'ForeignKey',
    );
  }
}
