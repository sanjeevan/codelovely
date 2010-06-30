<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration);

error_reporting(E_ALL);

$manager = Doctrine_Manager::getInstance();
$cache_driver = $manager->getAttribute(Doctrine::ATTR_RESULT_CACHE);

if ($cache_driver instanceof Doctrine_Cache_Driver){
  $c1 = $cache_driver->deleteByPrefix('hot');
  $c2 = $cache_driver->deleteByPrefix('latestlist');
  echo "Clear hotlist items: $c1\n";
  echo "Clear latestlist items: $c2\n";
}