<?php

/**
 * This is a simple job queue which used redis as the data store. The actual
 * queue is implemented as a redis list. 
 * When jobs are added to the queue, the information is serialized, then pushed 
 * onto the list (FIFO). 
 * 
 * @author sanjeevan
 *
 */
class RedisJobQueue
{
  private $options = array(
    'servers' => array(
      'server1' => array('host' => '127.0.0.1','port' => 6379)
    ),
    'namespace' => 'JobQueue'
  );

  /**
   * phpredis client
   *
   * @var Redis
   */
  private $redis = null;
  
  /**
   * Job queue name
   * 
   * @var string
   */
  private $name = '';

  public function  __construct($name = null)
  {
    $path = sfConfig::get('sf_root_dir') . '/lib/vendor/predis';
    require_once $path . "/Predis.php";
    require_once $path . "/Predis_Compatibility.php";
    
    if (!$name){
      throw new Exception('RedisJobQueue must have a name specified');
    }

    $this->name = $this->options['namespace'] . ':' . $name;
    
    $conn = $this->options['servers']['server1'];
    $this->redis = new Predis_Client($conn);
  }

  /**
   * Close, then connect to redis
   *
   */
  public function reConnect()
  {
    $this->redis->disconnect();
    $this->redis->connect();
  }

  /**
   * Connect to redis
   *
   */
  public function connect()
  {
    if (!$this->redis->isConnected()){
      $this->redis->connect();
    }
  }

  /**
   * Returns phpredis
   *
   * @return Redis
   */
  public function getRedis()
  {
    return $this->redis;
  }

  /**
   * Add a job to the redis queue, returns meta information about added job
   *
   * @param array $job_data
   * @param integer $expire
   * @param boolean $meta Set to false, if the job does not need to be tracked
   * @return Job
   */
  public function addJob($job = array(), $expire = 3600, $meta = true)
  {
    // attach an id, and timestamp to the data
    $job['id'] = $this->name . ':Job:' . myUtil::UUID();
    $job['created_at'] = time();

    // add to queue
    $queue_data = serialize($job);
    $this->redis->rpush($this->name, $queue_data);

    if (!$meta){
      return true;
    }
    
    $job_meta = array(
      'id'      => $job['id'],
      'status'  => 'new',
      'expire'  => $expire > 0 ? time() + $expire : false
    );

    $this->setJobMeta($job['id'], $job_meta);  
    
    return $job_meta;
  }

  /**
   * Set a single meta field
   * 
   * @param string $id
   * @param string $field
   * @param mixed $value
   */
  public function setJobMetaField($id, $field, $value)
  {
    $this->redis->set("{$id}:{$field}", serialize($value));
  }

  /**
   * Set meta information for a key
   * 
   * @param string $key
   * @param array $data
   */
  public function setJobMeta($key = null, $data = array())
  {
    $this->redis->set("{$key}:_fields", serialize(array_keys($data)));

    foreach ($data as $k => $v){
      $this->redis->set("{$key}:$k", serialize($v));
    }
  }

  /**
   * Get meta information for a job
   * 
   * @param string $key
   */
  public function getJobMeta($key)
  {
    $field_data = $this->redis->get("{$key}:_fields");

    if (!$field_data){
      return false;
    }

    $fields = unserialize($field_data);
    $meta = array();

    foreach ($fields as $f){
      $d = $this->redis->get("{$key}:$f");
      if ($d !== false){
        $meta[$f] = unserialize($d);
      }
    }

    return $meta;
  }

  /**
   * Delete meta information added to a queued job
   * 
   * @param string $key
   */
  public function deleteMeta($key)
  {
    $field_data = $this->redis->get("{$key}:_fields");

    if (!$field_data){
      return false;
    }

    $this->redis->delete("{$key}:_fields");
    $fields = unserialize($field_data);

    foreach ($fields as $f){
      $this->redis->delete("{$key}:$f");
    }

    return true;
  }

  /**
   * Pop a job off the end of the list
   *
   * @return array
   */
  public function popJob()
  {
    $data = $this->redis->lpop($this->name);

    if ($data !== false){
      return unserialize($data);
    }
  }
}