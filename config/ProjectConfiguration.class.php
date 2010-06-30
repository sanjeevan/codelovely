<?php

require_once '/usr/local/src/symfony/RELEASE_1_4_3/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin', 'sfThumbnailPlugin', 'sfMessagingPlugin', 'sfFormExtraPlugin');
    $this->enablePlugins('sfFormExtraPlugin');
    $this->enablePlugins('sfImageTransformPlugin');
  }
  
  public function configureDoctrine(Doctrine_Manager $manager)
  {    
    $servers = array(
      'host'        => 'localhost',
      'port'        => 11211,
      'persistent'  => true
    );
    
    $driver = new Doctrine_Cache_Memcache(array(
      'servers'     => $servers,
      'compression' => false
    ));
    
    // cache results to memcache
    $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE, $driver);
    $manager->setAttribute(Doctrine::ATTR_CACHE_LIFESPAN, 3600);
    
    // cache queries to memcache
    $manager->setAttribute(Doctrine::ATTR_QUERY_CACHE, $driver);
  }
}