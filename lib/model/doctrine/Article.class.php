<?php

/**
 * Article
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    codelovely
 * @subpackage model
 * @author     Sanjeevan Ambalavanar
 */
class Article extends BaseArticle
{
  static $valid_flavours = array(
    'link'      => 'Links',
    'code'      => 'Code',
    'snapshot'  => 'Snapshots',
    'question'  => 'Questions' 
  );
  
  public static function getFlavourName($flavour)
  {
    if (isset(self::$valid_flavours[$flavour])){
      return self::$valid_flavours[$flavour];
    }
  }
  
  /**
   * Get valid flavours
   * 
   */
  public static function getFlavours()
  {
    return array_keys(self::$valid_flavours);
  }
  
  /**
   * Invalidate cache, the actual invalidation of the cache is run in a background
   * process
   * 
   */
  public function invalidateCache()
  {
    /*
    $manager = Doctrine_Manager::getInstance();
    $cache_driver = $manager->getAttribute(Doctrine::ATTR_RESULT_CACHE);
    
    if ($cache_driver instanceof Doctrine_Cache_Driver){
      $c1 = $cache_driver->deleteByPrefix('hot');
      $c2 = $cache_driver->deleteByPrefix('latest');
      AppLog::add('info', 'invalidated cache hotlist: ' . $c1);
      AppLog::add('info', 'invalidated cache latestlist: ' . $c2);
    }
    */
    
    $job = array('operation' => 'UpdateListingCache', 'TotalPages' => 5);
    $queue = new RedisJobQueue(CacheUpdateWorker::QUEUE_NAME);
    $queue->addJob($job);
  }
  
  public function postSave($event)
  {
    $this->invalidateCache();
    parent::postSave($event);
  }
  
  public function postDelete($event)
  {
    $this->invalidateCache();
    parent::postDelete($event);
  }
  
  /**
   * Returns symfony route to article/slug for this article
   * 
   */
  public function getViewUrl()
  {
    return '@' . $this->getFlavour() . '?slug=' . $this->getSlug();
  }
  
  /**
   * Create record in database, as well as associated Thing and Vote
   * @see plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine/Doctrine_Record::save()
   */
  public function save(Doctrine_Connection $conn = null)
  {
    // create thing and associated vote
    if ($this->isNew()){
      $thing = new Thing();
      $thing->setIsPublished(1);
      $thing->setUps(1);
      $thing->setDowns(0);
      $thing->setScore(1);
      $thing->updateHotness();
      $thing->save();

      $this->setThing($thing);

      $vote = new Vote();
      $vote->setUserId($this->getUserId());
      $vote->setThing($thing);
      $vote->setType('up');
      $vote->save();
    }

    parent::save($conn);
  }
  
  /**
   * Get the correct form for this article
   * 
   * @return BaseForm
   */
  public function getFlavouredForm()
  {
    switch ($this->getFlavour()){
      case 'code':
        $form = ArticleCodeForm::fromModel($this);
        break;
      case 'question':
        $form = ArticleQuestionForm::fromModel($this);
        break;
      case 'link':
        $form = ArticleLinkForm::fromModel($this);
        break;
      case 'snapshot':
        $form = ArticleSnapshotForm::fromModel($this);
        break;
      default:
        return false;
    }
    
    return $form;
  }
  

  /**
   * Returns all supported languages
   * 
   */
  public static function getSupportedCodeLanguages()
  {
    $languages = array(
      'PHP'           => array('brush' => 'shBrushPhp.js', 'alias' => 'php'),
      'Ruby'          => array('brush' => 'shBrushRuby.js', 'alias' => 'ruby'),
      'Actionscript'  => array('brush' => 'shBrushAS3.js', 'alias' => 'as3'),
      'BASH'          => array('brush' => 'shBrushBash.js', 'alias' => 'bash'),
      'ColdFusion'    => array('brush' => 'shBrushColdFusion.js', 'alias' => 'coldfusion'),
      'C++'           => array('brush' => 'shBrushCpp.js', 'alias' => 'cpp'),
      'C#'            => array('brush' => 'shBrushCSharp.js', 'alias' => 'csharp'),
      'CSS'           => array('brush' => 'shBrushCss.js', 'alias' => 'css'),
      'Java'          => array('brush' => 'shBrushJava.js', 'alias' => 'java'),
      'Javscript'     => array('brush' => 'shBrushJScript.js', 'alias' => 'js'),
      'Perl'          => array('brush' => 'shBrushPerl.js', 'alias' => 'perl'),
      'Python'        => array('brush' => 'shBrushPython.js', 'alias' => 'python'),
      'Text'          => array('brush' => 'shBrushPlain.js', 'alias' => 'plain'),
      'XML'           => array('brush' => 'shBrushXml.js', 'alias' => 'xml')
    );
    
    asort($languages);
    
    return $languages;
  }
  
  /**
   * Return the brush alias used to inform SyntaxHighlighter as to what language
   * this code snippet is in
   * 
   */
  public function getBrushAlias()
  {
    $langs = self::getSupportedCodeLanguages();
    return $langs[$this->getCodeLanguage()]['alias'];
  }
  
  /**
   * Return javascript brush file for this article
   * 
   */
  public function getLanguageBrushJavascript()
  {
    $langs = self::getSupportedCodeLanguages();
    
    if (isset($langs[$this->getCodeLanguage()])){
      return $langs[$this->getCodeLanguage()]['brush'];
    }
    
    return false;
  }
  
  /**
   * Delete all files attached to this article
   * 
   */
  public function deleteFiles()
  {
    $ftas = $this->getFiles();
    
    foreach ($ftas as $fta){
      $fta->delete();
      $fta->getFile()->delete();
    }
  }
  
  /**
   * Returns the first file attached to this article
   * 
   * @param boolean $cache Set true to go through cache driver
   * @return File
   */
  public function getSnapshot($cache = false)
  {
    $ftas = $this->getFiles($cache);
    return $ftas->getFirst()->getFile();
  }

  /**
   * Returns all FileToArticle records for this article
   * 
   * @param boolean $cache Set true to go through cache driver
   * @return array<FileToArticle>
   */
  public function getFiles($cache = false)
  {
    $q2 = Doctrine_Query::create()->select('fta.*, f.*')
      ->from('FileToArticle fta')
      ->where('fta.article_id = ?', $this->getId())
      ->innerJoin('fta.File f');
      
    if ($cache){
      $q2->useResultCache(true);
    }  

    return $q2->execute();
  }
    
  /**
   * Delete this article and associated comments, files, things, and votes
   * 
   * @see plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine/Doctrine_Record::delete()
   */
  public function delete(Doctrine_Connection $conn = null)
  {
    foreach ($this->getComments() as $comment){
      $comment->delete();
    }
    
    foreach ($this->getFiles() as $fta){
      $fta->delete();
      $fta->getFile()->delete();
    }
    
    parent::delete($conn);

    $this->getThing()->delete();
  }
}
