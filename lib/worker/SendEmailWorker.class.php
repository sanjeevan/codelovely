<?php

class SendEmailWorker extends WorkerDaemon
{
  const QUEUE_NAME = 'SendEmail';
  
  /**
   * Job queue
   * 
   * @var RedisJobQueue
   */
  private $queue;
  private $iter = 0;
  
  /**
   * Mail agent
   * 
   * @var sfMailer
   */
  private $mailer = null;
  
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
    
    $this->mailer = sfContext::getInstance()->getMailer();
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
    if (!$job){
      return;
    }
    
    $this->mailer->getTransport()->stop();
    $this->mailer->getTransport()->start();
    
    $mail = $this->mailer->compose();
    $mail->setTo($job['to']);
    $mail->setSubject($job['subject']);
    $mail->setFrom($job['from']);
    $mail->setBody($job['message']);

    $this->mailer->send($mail);
    
    if (isset($job['invite'])){
      $invite = Doctrine::getTable('Invite')->find($job['invite']['id']);
      
      if ($invite){
        $invite->setStatus('sent');
        $invite->setSentTo($job['to']);
        $invite->setSentAt(date(myUtil::MYSQL_DATETIME));
        $invite->save();
      }
      
      $this->logMessage('Invite sent to ' . $job['to']);
    } else {
      $this->logMessage('Email sent to ' . $job['to']);
    }
  }
}