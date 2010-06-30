<?php

class sfMessaging 
{
  protected $sender = null;
  protected $sep    = ',';
  
  public function __construct(User $user)
  {
    $this->sender = $user;
  }
  
  public function send($to, $title, $message)
  {
    $recipients   = explode($this->sep, $to);
    $valid_users  = array();
    $to_cleaned   = array();
    $to_str       = '';
    
    foreach ($recipients as $username){
      $username = trim($username);
      $user = Doctrine::getTable('User')->findOneByUsername($username);
      if ($user){
        $valid_users[] = $user;
        $to_cleaned[] = $user->getUsername();
      }
    }
    
    $to_str = implode(', ', $to_cleaned);
    
    // send message to all recipients
    foreach ($valid_users as $user){
      $this->sendToUser($user, $title, $message, $to_str);
    }
    
    // put a copy in the sender's outbox
    $sf_message_outbox = new sfMessageOutbox();
    $sf_message_outbox->setUser($this->sender);
    $sf_message_outbox->setToStr($to_str);
    $sf_message_outbox->setTitle($title);
    $sf_message_outbox->setMessage($message);
    $sf_message_outbox->save();
  }
  
  protected function sendToUser($user, $title, $message, $to)
  {
    $sf_message_inbox = new sfMessageInbox();
    $sf_message_inbox->setToStr($to);
    $sf_message_inbox->setFromUser($this->sender);
    $sf_message_inbox->setUser($user);
    $sf_message_inbox->setTitle($title);
    $sf_message_inbox->setMessage($message);
    $sf_message_inbox->save();
    
    return $sf_message_inbox;
  }
  
  public function hasNewMail()
  {
    $q = Doctrine_Query::create()->select('m.*')
        ->from('sfMessageInbox m')
        ->where('m.user_id = ?', $this->sender->getId())
        ->andWhere('m.is_read is null')
        ->limit(1);
   
    $r = $q->fetchOne();
        
    return ($r instanceof sfMessageInbox); 
  }
}