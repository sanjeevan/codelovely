<?php

/**
 * BlogEntry form.
 *
 * @package    codelovely
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class BlogEntryForm extends BaseBlogEntryForm
{
  public function configure()
  {
    $this->useFields(array(
      'title',
      'summary',
      'body',
      'status',
      'published_at'
    ));

    $this->validatorSchema['title']   = new sfValidatorString(array('required' => true));
    $this->validatorSchema['summary'] = new sfValidatorString(array('required' => true));
    $this->validatorSchema['body']    = new sfValidatorString(array('required' => true));
    $this->validatorSchema['status']  = new sfValidatorChoice(array('choices' => $this->getStatusChoices() ));
    $this->validatorSchema['published_at'] = new sfValidatorDateTime(array('required' => true));

    $options['format'] = "%day%/%month%/%year%";
    $this->widgetSchema['published_at'] = new sfWidgetFormDate($options);
    $this->widgetSchema['status'] = new sfWidgetFormChoice(array('choices' => $this->getStatusChoices()));
  }

  /**
   * Get choices for status drop down
   * @return array
   */
  private function getStatusChoices()
  {
    $options = array(
      'Draft' => 'Draft',
      'Published' => 'Published'
    );

    return $options;
  }
}
