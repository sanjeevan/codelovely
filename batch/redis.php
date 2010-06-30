<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration);

error_reporting(E_ALL);

$job = array(
  'url'     => 'http://www.myinkblog.com/2010/05/23/blue-collar-designers-5-lessons-from-the-lunch-pail/',
  'link_id' => rand(100, 999),
  'action'  => 'getthumbnails'
);

try {
  $job_queue = new RedisJobQueue('TestQueue');
  $r = $job_queue->addJob($job);
  
  var_dump( $job_queue->getJobMeta($r['id']));
  
  $job_queue->deleteMeta($r['id']);

} catch (Exception $e){
  echo $e->getMessage();
  exit(1);
}

/*
$manager = Doctrine_Manager::getInstance();
$default = $manager->getConnection('doctrine');
$default->close();

$con = Doctrine_Manager::getInstance();

try {
  $articles = Doctrine::getTable('Article')->findAll();
  $i = 0;
  foreach ($articles as $row){
    echo $i . ", " . $row->getTitle() . "\n";
    $i++;
  }
} catch (Exception $e){
  echo $e->getTraceAsString();
}
*/




