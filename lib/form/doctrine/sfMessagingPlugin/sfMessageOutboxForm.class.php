<?php

/**
 * sfMessageOutbox form.
 *
 * @package    codelovely
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfMessageOutboxForm extends PluginsfMessageOutboxForm
{
  public function configure()
  {
    $this->useFields(array(
      'to_str',
      'title',
      'message'
    ));
    
    $this->widgetSchema->setFormFormatterName('list');
    $this->widgetSchema->setLabels(array(
      'to_str' => 'To (seperate different contacts with a comma)'
    ));
  }
}
