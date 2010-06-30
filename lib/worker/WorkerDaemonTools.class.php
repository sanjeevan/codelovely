<?php

class WorkerDaemonTools
{
  public static function stopWorker($name, $run_dir = 'run')
  {
    $pidfile = "$run_dir/$name.pid";
    if (!is_readable($pidfile)){
      throw new Exception("Could not read pid file");
    }
    $pid = (int) file_get_contents($pidfile);
    if (self::checkPid($pid)){
      if (!posix_kill($pid, SIGKILL)){
        throw new Exception("Error killing process $pid");
      }
      unlink($pidfile);
      return true;
    } else {
      unlink($pidfile);
    }
    return false;
  }

  public static function isWorkerRunning($name, $run_dir = 'run')
  {
    $pidfile = "$run_dir/$name.pid";
    if (!is_readable($pidfile)){
      return false;
    }
    $pid = (int) file_get_contents($pidfile);
    if (self::checkPid($pid)){
      return true;
    } else {
      unlink($pidfile);
      return false;
    }
    return false;
  }

  public static function checkPid($pid)
  {
    $res = @pcntl_getpriority($pid);
    return !($res === false);
  }
}