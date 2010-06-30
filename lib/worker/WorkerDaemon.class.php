<?php

abstract class WorkerDaemon
{
  private $run_dir  = null;
  private $name     = null;
  private $logfile  = null;
  private $pid      = null;
  private $sid      = null;
  private $abort    = false;
  private $uid      = false;
  private $gid      = false;
  private $sleep    = 1;
  
  public function __construct($name = '', $run_dir = 'run')
  {
    $this->name = $name;
    $this->run_dir = $run_dir;
    $this->setLogFile($run_dir . '/' . $name . '.log');

    $this->initialize();
  }

  // to be implemented by sub classes
  public abstract function process();
  public abstract function initialize();

  /**
   * The main loop of the daemon
   */
  public function run()
  {
    $this->fork();

    while (!$this->abort){
      $this->process();
      clearstatcache();
      
      if ($this->sleep !== false){
        sleep($this->sleep);
      }
    }
    
    return true;
  }

  /**
   * Fork off as a new process
   */
  private function fork()
  {
    $pid = pcntl_fork();

    if ($pid === -1){
      $this->logMessage('Could fork off a child process!');
      $this->shutdown();
      exit(1);
    } else if ($pid) {
      $this->logMessage('Forked off succesfully. Killing parent');
      $this->shutdown();
      exit(0);
    } else {
      $this->updatePidInformation();
      $this->logMessage('Spawned daemon with PID: ' . $this->pid);

      @umask(0);
      declare(ticks = 1);
      $this->sid = posix_setsid();

      $this->switchUser();
    }
  }

  /**
   * Run worker as a different uid/gid
   *
   * @param integer $uid
   * @param integer $gid
   */
  public function runAsUserGroup($uid = false, $gid = false)
  {
    $this->uid = $uid;
    $this->gid = $gid;
  }

  /**
   * Switch to new user and group
   */
  private function switchUser()
  {
    if ($this->uid !== false && $this->gid !== false){
      posix_setuid($this->uid);
      posix_setgid($this->gid);
    }
  }

  /**
   * Update PID information after process has been forked
   */
  private function updatePidInformation()
  {
    $this->pid = posix_getpid();
    $fp = fopen($this->getPidFile(), 'w+');
    fwrite($fp,  $this->pid);
    fclose($fp);
  }
  
  /**
   * Returns the filename of the pidfile
   *
   * @return string
   */
  public function getPidFile()
  {
    return "{$this->run_dir}/{$this->name}.pid";
  }

  /**
   * Set the filename of the log file
   *
   * @param string $filename
   * @return boolean
   */
  public function setLogFile($filename)
  {
    if (is_resource($this->logfile)){
      fclose($this->logfile);
    }

    $this->logfile = fopen($filename, 'a+');
    if (!$this->logfile){
      throw new Exception("Could not open $filename for writing");
    }
    return true;
  }

  /**
   * Log a message
   * @param <string $message
   */
  public function logMessage($message)
  {
    if ($message instanceof Exception){
      $message = $message->getTraceAsString();
    }

    $ts = date('d-M-Y H:i');
    fwrite($this->logfile, "[{$ts}] $message\n");
    fflush($this->logfile);
  }

  /**
   * Called during object gc
   */
  public function  __destruct()
  {
    $this->shutdown();
  }

  /**
   * Cleanup
   */
  public function shutdown()
  {
    if (is_resource($this->logfile)){
      fclose($this->logfile);
    }
  }
}