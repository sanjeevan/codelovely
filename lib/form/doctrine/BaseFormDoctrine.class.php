<?php

/**
 * Project form base class.
 *
 * @package    codelovely
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormBaseTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class BaseFormDoctrine extends sfFormDoctrine
{
  public function setup()
  {
    $this->widgetSchema->setFormFormatterName('list');
    $this->widgetSchema->getFormFormatter()->setHelpFormat('<div class="help">%help%</div>');
  }
}
