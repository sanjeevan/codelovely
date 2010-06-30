<?php

/**
 * Comment
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    frostty
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
class Comment extends BaseComment
{
  public function save(Doctrine_Connection $conn = null)
  {
    if ($this->isNew()){
      $thing = new Thing();
      $thing->setIsPublished(1);
      $thing->setUps(1);
      $thing->setDowns(0);
      $thing->setScore(1);
      $thing->updateHotness();
      $thing->save();

      $vote = new Vote();
      $vote->setUserId($this->getUserId());
      $vote->setThing($thing);
      $vote->setType('up');
      $vote->save();
    
      $this->setThing($thing);
    }
    
    parent::save($conn);
  }
  
  public function invalidateCache()
  {
    $manager = Doctrine_Manager::getInstance();
    $cache_driver = $manager->getAttribute(Doctrine::ATTR_RESULT_CACHE);
    
    if ($cache_driver instanceof Doctrine_Cache_Driver){
      $cache_driver->deleteByPrefix('articlecomments-' . $this->getArticleId());
    }
  }
  
  public function postDelete($event)
  {
    $this->invalidateCache();
    
    parent::postDelete($event);
  }
  
  public function postSave($event)
  {
    $this->invalidateCache();
    
    parent::postSave($event);
  }
  
  public function delete(Doctrine_Connection $conn = null)
  {
    parent::delete($conn);
    $this->getThing()->delete();
  }
}
