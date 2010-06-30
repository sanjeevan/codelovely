<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration);

$username = $argv[1];
$amount   = (int) $argv[2];

$user = Doctrine::getTable('User')->findOneByUsername($username);

if (!$user){
  die("User not found\n");
}


for ($i = 0; $i < $amount; $i++){
  $invite = new Invite();
  $invite->setUser($user);
  $invite->setStatus('unused');
  $invite->setCode(Invite::generateCode());
  $invite->save();
  
  echo "Created invite: {$invite->getCode()} \n";
}

echo "Finished\n";

