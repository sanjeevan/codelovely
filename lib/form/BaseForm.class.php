<?php

/**
 * Base project form.
 * 
 * @package    frostty
 * @subpackage form
 * @author     Your name here 
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends sfFormSymfony
{
  public function setup()
  {
    $this->widgetSchema->getFormFormatter()->setHelpFormat('<div class="help">%help%</div>');

    parent::setup();
  }
}
