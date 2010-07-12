<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration);

$articles = Doctrine::getTable('Article')->findAll();

foreach ($articles as $article){
  $article->setSummaryHtml(myUtil::markdown($article->getSummary()));
  $article->setQuestionHtml(myUtil::markdown($article->getQuestion()));
  $article->setFulldescriptionHtml(myUtil::markdown($article->getFulldescription()));
  $article->save();
}

echo "Updated article html fields \n";