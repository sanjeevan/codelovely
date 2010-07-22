<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version4 extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->createForeignKey('tag_to_article', 'tag_to_article_tag_id_tag_id', array(
      'name' => 'tag_to_article_tag_id_tag_id',
      'local' => 'tag_id',
      'foreign' => 'id',
      'foreignTable' => 'tag',
    ));
    
    $this->createForeignKey('tag_to_article', 'tag_to_article_article_id_article_id', array(
      'name' => 'tag_to_article_article_id_article_id',
      'local' => 'article_id',
      'foreign' => 'id',
      'foreignTable' => 'article',
    ));
    
    $this->addIndex('tag_to_article', 'tag_to_article_tag_id', array('fields' => array(0 => 'tag_id',),));
    $this->addIndex('tag_to_article', 'tag_to_article_article_id', array('fields' => array(0 => 'article_id',),));
  }

  public function down()
  {
    $this->dropForeignKey('tag_to_article', 'tag_to_article_tag_id_tag_id');
    $this->dropForeignKey('tag_to_article', 'tag_to_article_article_id_article_id');
    $this->removeIndex('tag_to_article', 'tag_to_article_tag_id', array('fields' => array(0 => 'tag_id',),));
    $this->removeIndex('tag_to_article', 'tag_to_article_article_id', array('fields' => array(0 => 'article_id',),));
  }
}