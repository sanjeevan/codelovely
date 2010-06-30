<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
new sfDatabaseManager($configuration);


$q = Doctrine_Query::create()->select('a.*, count(c.id) as t_comments')
  ->from('Article a')
  ->leftJoin('a.Comments c')
  ->groupBy('a.id');
  
  
$results = $q->execute();

foreach ($results as $article)
{
  echo $article->getTitle();
  echo " " . $article->getTComments();
  echo "\n";
  
  $article->setTotalComments($article->getTComments());
  $article->save();
  
}



