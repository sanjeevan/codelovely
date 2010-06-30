<?php

/**
 * *very simple* logging
 *
 * @version CVS: $Id: AppLog.class.php,v 1.9 2009/10/22 15:37:46 sanjeevan Exp $
 *
 */
class AppLog
{
  /**
   * logs an exception
   *
   * @param Exception $e
   */
  public static function addException($e)
  {
    $log = sfConfig::get('sf_root_dir') . '/log/app_expections.log';
    $fp = fopen($log, 'a+');

    fwrite($fp, "[" . date('Y-m-d H:i') . "]@" . @$_SERVER['REMOTE_ADDR'] . " ");
    fwrite($fp, $e->getTraceAsString() . "\n");
    fclose($fp);
  }

  /**
   * adds a new log event.
   * this produces two log files, one with basic info, and another will a mini-stack
   * trace
   *
   * @param string $event
   * @param string $message
   */
  public static function add($event, $message)
  {
    $path = sfConfig::get('sf_root_dir') . '/log/';
    $fp1 = fopen($path . 'app.log', 'a+');
    $fp2 = fopen($path . 'app_extended.log', 'a+');

    $sf_user = sfContext::getInstance()->getUser();
    
    if ($sf_user->isAuthenticated()){
      $username = $sf_user->getModel()->getUsername();
    } else {
      $username = 'guest';
    }
    
    $pre = sprintf("%s - %s [%s]",
      myUtil::getClientIpAddress(),
      $username,
      date('c')
    );
        
    fwrite($fp1, "$pre | $event / $message\n");
    fclose($fp1);
    
    fwrite($fp2, "$pre | $event / $message\n");
    fwrite($fp2, " `-> " . self::getTrace() . "\n");
    fclose($fp2);
  }
  
  /**
   * get's class, method, file and line
   * 
   * @return string
   */
  private static function getTrace() 
  {
    $bt = debug_backtrace();
     
    // get class, function called by caller of caller of caller
    $class = $bt[2]['class'];
    $function = $bt[2]['function'];
     
    // get file, line where call to caller of caller was made
    $file = $bt[1]['file'];
    $line = $bt[1]['line'];
    
    $root_dir = sfConfig::get('sf_root_dir');
    $file = 'ROOT' . str_replace($root_dir, '', $file);
     
    // build & return the message
    return "$class::$function in $file at $line";
  }
}

?>