<?php

/**
 * InviteRequest form.
 *
 * @package    socialhub
 * @subpackage form
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class InviteRequestForm extends BaseInviteRequestForm
{
  public function configure()
  {
    $this->useFields(array(
      'email', 'firstname', 'lastname', 'url'
    ));
    
    $this->widgetSchema->setLabels(array(
      'url' => "URL of something you've created"
    ));
    
    $this->widgetSchema->setHelps(array(
      'url' => 'Can be a website, open source project, your design portfolio...etc'
    ));
    
    $validators = array(
      'firstname' => new sfValidatorString(array('required' => true, 'max_length' => 50)),
      'email'      => new sfValidatorString(array('max_length' => 100, 'required' => true)),
      'url'        => new sfValidatorString(array('max_length' => 100, 'required' => true)),
      'lastname'   => new sfValidatorString(array('max_length' => 50, 'required' => true)),
    );
    
    foreach ($validators as $field => $validator){
      $this->validatorSchema[$field] = $validator;
    }
  }
}
