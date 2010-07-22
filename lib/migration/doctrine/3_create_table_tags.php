<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version3 extends Doctrine_Migration_Base
{
  public function up()
  {
    // tag table
    $this->createTable('tag', array(
      'id'          => array('type' => 'integer', 'length' => '4', 'autoincrement' => '1', 'primary' => '1'),
      'name'        => array('type' => 'string','length' => '100'),
      'created_at'  => array('notnull' => '1', 'type' => 'timestamp', 'length' => '25'),
      'updated_at'  => array('notnull' => '1', 'type' => 'timestamp', 'length' => '25'),
      'slug'        => array('type' => 'string', 'length' => '255')
    ), 
    array('indexes' => array(
      'sluggable' => array(
        'fields' => array(0 => 'slug'), 
        'type' => 'unique')), 
        'primary' => array(0 => 'id'))
    );
    
    // table_to_article table
    $this->createTable('tag_to_article', array(
      'id'          => array('type' => 'integer', 'length' => '4', 'autoincrement' => '1', 'primary' => '1'),
      'tag_id'      => array('type' => 'integer', 'length' => '4'),
      'article_id'  => array('type' => 'integer', 'length' => '4'),
      'created_at'  => array('notnull' => '1', 'type' => 'timestamp', 'length' => '25'),
      'updated_at'  => array('notnull' => '1', 'type' => 'timestamp', 'length' => '25'),
    ), 
    array('indexes' => array(
      'article_to_tag_index' => array(
        'fields' =>  array(0 => 'tag_id', 1 => 'article_id'), 
        'type' => 'unique')), 
        'primary' => array(0 => 'id'))
    );
  }

  public function down()
  {
    $this->dropTable('tag');
    $this->dropTable('tag_to_article');
  }
}