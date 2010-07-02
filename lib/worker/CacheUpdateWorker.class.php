<?php

class CacheUpdateWorker extends WorkerDaemon
{
  const QUEUE_NAME = 'CacheUpdate';
  
  private $iter =0;
  private $queue = null;
  
  // how the listing cache ids are formatted
  private $listing_cache_hashes = array(
    'cacheHotQuery'     => 'hot_flavour-%flavour%_page-%page%_perpage-%perpage%',
    'cacheLatestQuery'  =>'latest_flavour-%flavour%_page-%page%_perpage-%perpage%',
  );
    
  public function initialize()
  {
    try {
      $this->queue = new RedisJobQueue(self::QUEUE_NAME);
    } catch (Exception $e){
      $this->logMessage($e);
      $this->logMessage('Shutting down worker');
      $this->shutdown();
      exit(0);
    }
  }
  
  public function keepAlive($interval = 300)
  {
    if ($this->iter == $interval){
      // reconnect to redis
      $this->logMessage('Reconnecting to redis and refreshing doctrine connection');
      $this->queue->reConnect(); // refresh connection
      $this->iter = 0;
      
      // close database connection
      $manager = Doctrine_Manager::getInstance();
      $default = $manager->getConnection('doctrine');
      $default->close();
      $default->connect();
    }
    
    $this->iter++;
  }
  
  public function process()
  {
    $this->keepAlive();
    
    while ($job = $this->queue->popJob()){
      $this->processJob($job);
    }
  }
  
  public function processJob($job)
  {
    $operation = isset($job['operation']) ? $job['operation'] : 'UpdateListingCache';
    
    switch ($operation){
      case 'UpdateListingCache':
        $this->refreshAllListingCache($job);
        break;
      default:
        break;
    }
    return true;
  }
  
  /**
   * Refreshes caches for all listings
   * 
   */
  public function refreshAllListingCache($job = array())
  {
    $perpage = sfConfig::get('app_things_perpage');
    $flavours = array_merge(array('all'), Article::getFlavours());
    $total_pages = isset($job['TotalPages']) ? $job['TotalPages'] : 10;
    
    $cache_vars = array('%flavour%', '%page%', '%perpage%');
    
    // generates the cache hash for hot/latest listing and rehashes them
    foreach ($this->listing_cache_hashes as $cache_method => $hash_id_template){
      foreach ($flavours as $flavour){
        for ($page = 1; $page <= $total_pages; $page++){
          $replace = array($flavour, $page, $perpage);
          $hash = str_replace($cache_vars, $replace, $hash_id_template); 
          
          if (method_exists($this, $cache_method)){
            if ($this->$cache_method($hash, $flavour, $page, $perpage)){
              $this->logMessage('Recached: ' . $hash);
            }
          }
        }
      }      
    }
  }
  
  /**
   * Recache hot listing cache
   * 
   * @param string $hash
   * @param string $flavour
   * @param integer $page
   * @param integer $per_page
   * @param integer $lifetime
   */
  public function cacheHotQuery($hash, $flavour = 'all', $page = 1, $per_page = 25, $lifetime = 3600)
  {    
    $q = Doctrine_Query::create()->select('a.*, t.*, v.*')
      ->from('article a, a.Thing t')
      ->groupBy('a.id')
      ->orderBy('t.hot DESC')
      ->expireResultCache(true)
      ->useResultCache(true, 3600, $hash);
      
    if ($flavour != 'all'){
      $q->where('a.flavour = ?',  $flavour);
    }
      
    $pager = new sfDoctrinePager('Article', $per_page);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();
    
    $results = $pager->getResults();
    return true;
  }
  
  /**
   * Recache latest listings cache
   * 
   * @param string $hash
   * @param string $flavour
   * @param integer $page
   * @param integer $per_page
   * @param integer $lifetime
   */
  public function cacheLatestQuery($hash, $flavour = 'all', $page = 1, $per_page = 25, $lifetime = 3600)
  {
    $q = Doctrine_Query::create()->select('a.*, t.*, v.*')
      ->from('article a, a.Thing t')
      ->groupBy('a.id')
      ->orderBy('a.created_at DESC')
      ->expireResultCache(true)
      ->useResultCache(true, 3600, $hash);
      
    if ($flavour != 'all'){
      $q->where('a.flavour = ?',  $flavour);
    }
      
    $pager = new sfDoctrinePager('Article', $per_page);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();
    
    $results = $pager->getResults();
    return true;
  }
}