<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration);

error_reporting(E_ALL);

/*
$manager = Doctrine_Manager::getInstance();
$default = $manager->getConnection('doctrine');

var_dump($default->getOptions());

$default->close();

if ($default->isConnected()){
  echo "1) Connected to database\n";
}

//$con = Doctrine_Manager::getInstance();

try {
  $articles = Doctrine::getTable('Article')->findAll();
  if ($default->isConnected()){
    echo "2) connected to database\n";
  }
  $i = 0;
  foreach ($articles as $row){
    echo $i . ", " . $row->getTitle() . "\n";
    $i++;
  }
} catch (Exception $e){
  echo $e->getTraceAsString();
}
*/
/*
$mailer = sfContext::getInstance()->getMailer();

echo get_class($mailer->getTransport());
*/
/*
if (!class_exists('HTTP_Request')){
  require_once('HTTP/Request.php');
}


function getUrl($url)
{
  $req = @new HTTP_Request($url);
  $req->setMethod(HTTP_REQUEST_METHOD_GET);
  $req->addHeader('User-Agent', 'SocialHub Thumbail Agent/1.0');
  $req->sendRequest();

  $html = $req->getResponseBody();
  unset($req);
  return $html;
}

// <base href="http://www.9tuts.com/" />

$html = getUrl("http://www.google.com");


$dom = new DOMDocument();
@$dom->loadHTML($html);

$xpath = new DOMXPath($dom);
$base_paths = $xpath->evaluate('/html/head/base');

foreach ($base_paths as $base_path){
  if ($base_path->hasAttribute("href")){
    var_dump($base_path->getAttribute('href'));
  }
}
*/

echo sfConfig::get('sf_root_dir');

