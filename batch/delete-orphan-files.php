<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration);

$files = Doctrine::getTable('File')->findAll();

foreach ($files as $f){
  $q1 = Doctrine_Query::create()
    ->select('fta.*')
    ->from('FileToArticle fta')
    ->where('fta.file_id = ?', $f->getId());
    
  $t1 = $q1->count();
  
  $q2 = Doctrine_Query::create()
    ->select('a.*')
    ->from('UserToAvatar a')
    ->where('a.file_id = ?', $f->getId());
    
  $t2 = $q2->count();
  
  if ($t1 + $t2 == 0){
    echo "will delete {$file->getId()} {$file->getFilename()} \n";
    //$f->delete();
  }
  
}