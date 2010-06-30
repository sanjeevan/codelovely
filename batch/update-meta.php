<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration);

$files = Doctrine::getTable('File')->findAll();


foreach ($files as $f){
  if ($f->isImage()){
    $size = @getimagesize($f->getLocation());
    
    if (isset($size[0], $size[1])){
      $f->setMetaWidth($size[0]);
      $f->setMetaHeight($size[1]);
      $f->save();
    }
  }
}