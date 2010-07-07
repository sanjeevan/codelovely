<?php


require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration);


$files = Doctrine::getTable('File')->findAll();

foreach ($files as $f){
  $location = str_replace('frostty', 'codelovely', $f->getLocation());
  echo "$location\n";
  $f->setLocation($location);
  $f->save();
}
