<?php

class ThumbnailScraperWorker extends WorkerDaemon
{
  const QUEUE_NAME = 'GetThumbnails';

  private $agent = 'Mozilla/5.0 (X11; U; Linux i686; en-GB; rv:1.9.1.9) Gecko/20100401 Ubuntu/9.10 (karmic) Firefox/3.5.9';
  
  private $iter = 0;
  private $image_types = array('jpeg', 'jpg', 'png');
  private $tmp_path = '/tmp/frostty';
  
  private $ignore_image_regex = array(
    '#.*www.gravatar.com/.*#',
    '#^http://digg.com/.*#',
    '#.*/wp-content/themes/.*/images/.*#'
  );

  /**
   * Queue for jobs
   *
   * @var RedisJobQueue
   */
  private $queue = null;
  
  /**
   * Current page being processed
   *
   * @var string
   */
  private $current_page_url = '';

  /**
   * The current job being run
   *
   * @var array
   */
  private $current_job = null;


  /**
   * Run by WorkerDaemon after it has done it's thing
   * 
   * (non-PHPdoc)
   * @see worker/WorkerDaemon::initialize()
   */
  public function initialize()
  {
    if (!class_exists('HTTP_Request')){
      require_once('HTTP/Request.php');
    }

    try {
      $this->queue = new RedisJobQueue(self::QUEUE_NAME);
    } catch (Exception $e){
      $this->logMessage($e);
      $this->logMessage('Shutting down worker');
      $this->shutdown();
      exit(0);
    }

    // make the download temp path
    if (!is_dir($this->tmp_path)){
      mkdir($this->tmp_path, 0775, true);
      chown($this->tmp_path, 'www-data');
      chgrp($this->tmp_path, 'www-data');
    }

    // run as www-data
    $this->runAsUserGroup(33, 33);
  }

  /**
   * Convert relative urls to absolute urls
   *
   * @param string $rel
   * @param string $base
   * @return string
   */
  private function relativeUrlToAbs($rel, $base)
  {
    // return if already absolute URL
    if (parse_url($rel, PHP_URL_SCHEME) != ''){
      return $rel;
    }

    // queries and anchors
    if ($rel[0]=='#' || $rel[0]=='?'){
      return $base . $rel;
    }

    extract(parse_url($base));
    
    // remove non-directory element from path
    $path = preg_replace('#/[^/]*$#', '', $path);

    // destroy path if relative url points to root
    if ($rel[0] == '/'){
      $path = '';
    }

    // dirty absolute URL
    $abs = "$host$path/$rel";

    // replace '//' or '/./' or '/foo/../' with '/'
    $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');

    for($n = 1; $n > 0; $abs = preg_replace($re, '/', $abs, -1, $n)){
      
    }

    return $scheme . '://' . $abs;
  }

  /**
   * Parses out image links found in the html string passed
   *
   * @param string $html
   * @return array
   */
  private function getPageImageUrls($html = '')
  {
    $base_path = $this->current_page_url;
    
    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    // check if a base url has been defined in the page
    $x1 = new DOMXPath($dom);
    foreach ($x1->evaluate('/html/head/base') as $bp){
      if ($bp->hasAttribute('href')){
        $base_path = (string) $bp->getAttribute('href');
        break;
      }
    }
    
    // get img tags from page
    $x2 = new DOMXPath($dom);
    $image_urls = array();
    
    foreach ($x2->evaluate('/html/body//img') as $node){
      $src = (string) $node->getAttribute('src');
      if (!$this->ignoreThisImage($src)){
        $ext = myUtil::getFileExtension($src);
        if (in_array($ext, $this->image_types)){
          $url = $this->relativeUrlToAbs($src, $base_path);
          $image_urls[] = str_replace(' ', '%20', $url);
        }
      }
    }

    unset($x1);
    unset($xpath);
    unset($dom);

    return array_unique($image_urls);
  }

  /**
   * Returns true if the image should be ignored
   *
   * @param string $url
   * @return boolean
   */
  private function ignoreThisImage($url)
  {
    foreach ($this->ignore_image_regex as $regex){
      if (preg_match($regex, $url)){
        $this->logMessage('Ignoring: ' . $url);
        return true;
      }
    }

    return false;
  }

  /**
   * Fetch a single page
   *
   * @param string $url
   * @return string
   */
  private function getUrl($url)
  {
    $req = @new HTTP_Request($url);
    $req->setMethod(HTTP_REQUEST_METHOD_GET);
    $req->addHeader('User-Agent', 'SocialHub Thumbail Agent/1.0');
    $req->sendRequest();

    $html = $req->getResponseBody();
    unset($req);
    return $html;
  }
  
  /**
   * Main method called which is run externally
   */
  public function process()
  {
    if ($this->iter == 300){
      // reconnect to redis
      $this->logMessage('Reconnecting to redis and refreshing doctrine connection');
      $this->queue->reConnect(); // refresh connection

      // close database connection
      $manager = Doctrine_Manager::getInstance();
      $default = $manager->getConnection('doctrine');
      $default->close();
      $default->connect();
      $this->iter = 0;
    }

    $job = $this->queue->popJob();

    try {
      $this->processJob($job);
    } catch (Exception $e){
      $this->logMessage($e);
    }

    $this->iter++;
  }

  /**
   * Process a single job item from the queue
   *
   * @param array $job
   * @return null
   */
  private function processJob($job = null)
  {
    if (!$job){
      return;
    }

    $this->current_job = $job;
    $this->logMessage('--- NEW JOB: ' . $job['url']);
    
    $meta = $this->queue->getJobMeta($job['id']);
    if (is_array($meta)){
      $this->queue->setJobMetaField($job['id'], 'status', 'processing');
    } else {
      $this->logMessage("No job meta entry in redis. Ignoring job: " . $job['id']);
      return false;
    }
    
    $this->current_page_url = $job['url'];
    $html = $this->getUrl($this->current_page_url);
    $image_urls = $this->getPageImageUrls($html);

    if (count($image_urls) > 0){
      $this->logMessage("Added (" . count($image_urls) . ") to be fetched");
      $rolling_curl = new RollingCurl(array($this, 'imageFetchFinished'));

      foreach ($image_urls as $image_url){
        $req = new Request($image_url);
        $req->headers = array('Referer' => $this->current_page_url, 'User-Agent' => $this->agent);
        $rolling_curl->add($req);
      }

      // blocks until everything is downloaded
      $rolling_curl->execute();
    } else {
      $this->logMessage('No images found! Argggh');
    }

    $this->queue->setJobMetaField($job['id'], 'status', 'finished');
  }

  /**
   * Callback function used when image has downloaded to disk. Must be public,
   * because its a callback method
   *
   * @param string $response
   * @param array $info
   */
  public function imageFetchFinished($response, $info)
  {
    $http_code = $info['http_code'];
    $this->logMessage("Got: [$http_code]: {$info['url']}");

    $valid_http_codes = array(200, 201, 202, 203, 204, 204, 206);
    if (!in_array($http_code, $valid_http_codes)){
      $this->logMessage("Bad http code, skipping!");
      return;
    }
    
    $filename = basename($info['url']);
    $location = $this->tmp_path . '/' . $filename;

    $fp = fopen($location, 'w+');
    fwrite($fp, $response);
    fclose($fp);

    $file = new File();
    $file->setFilename($filename);
    $file->setFilesize(filesize($location));
    $file->setExtension(myUtil::getFileExtension($filename));
    $file->setMimetype(myUtil::getMimeType($location));
    $file->setHash(sha1_file($location));
    $file->useTempFile($location, false);
    $file->save();

    $fta = new FileToArticle();
    $fta->setArticleId($this->current_job['article_id']);
    $fta->setFileId($file->getId());
    $fta->save();
  }
}