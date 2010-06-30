#!/usr/bin/php
<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration);
error_reporting(E_ALL & ~E_STRICT);

define('RUN_DIR', dirname(__FILE__) . '/run');
define('JOB_NAME', 'thumbnails-scraper');

function usage(){
  echo "Usage: ./thumbnails-scraper.php [start|stop] name\n";
}

if (php_sapi_name() != 'cli'){
  echo "This must be run from the command line\n";
  exit(1);
}

if (!isset($argv[1]) || !$argv[2]){
  usage();
  exit(1);
}

if (posix_getuid() != 0){
  echo "This must be run as root, as we need to switch to www-data\n";
  exit(1);
}

$valid_cmds = array('start', 'stop');
if (in_array($argv[1], $valid_cmds)){
  $command = $argv[1];
} else {
  echo "Not a valid command.\n";
  usage();
  exit(1);
}

if (!preg_match('//', $argv[2])){
  echo "Worker name must match a-z\n";
  exit(1);
} else {
  $name = $argv[2];
}

$worker_name = JOB_NAME . '-' . $name;

if ($command == 'stop'){
  WorkerDaemonTools::stopWorker($worker_name, RUN_DIR);
  echo "Process killed\n";
  exit(0);
}

if (WorkerDaemonTools::isWorkerRunning($worker_name, RUN_DIR)){
  echo "Worker $worker_name is already running\n";
  exit(1);
}

try {
  $worker = new ThumbnailScraperWorker($worker_name, RUN_DIR);
  $worker->run();
} catch (Exception $e){
  $worker->logMessage($e);
  exit(0);
}



