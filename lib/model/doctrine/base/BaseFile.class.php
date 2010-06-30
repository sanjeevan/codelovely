<?php

/**
 * BaseFile
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $filename
 * @property integer $filesize
 * @property string $extension
 * @property string $mimetype
 * @property string $location
 * @property integer $meta_width
 * @property integer $meta_height
 * @property string $hash
 * @property Doctrine_Collection $FileToArticle
 * @property Doctrine_Collection $UserToAvatars
 * 
 * @method integer             getId()            Returns the current record's "id" value
 * @method string              getFilename()      Returns the current record's "filename" value
 * @method integer             getFilesize()      Returns the current record's "filesize" value
 * @method string              getExtension()     Returns the current record's "extension" value
 * @method string              getMimetype()      Returns the current record's "mimetype" value
 * @method string              getLocation()      Returns the current record's "location" value
 * @method integer             getMetaWidth()     Returns the current record's "meta_width" value
 * @method integer             getMetaHeight()    Returns the current record's "meta_height" value
 * @method string              getHash()          Returns the current record's "hash" value
 * @method Doctrine_Collection getFileToArticle() Returns the current record's "FileToArticle" collection
 * @method Doctrine_Collection getUserToAvatars() Returns the current record's "UserToAvatars" collection
 * @method File                setId()            Sets the current record's "id" value
 * @method File                setFilename()      Sets the current record's "filename" value
 * @method File                setFilesize()      Sets the current record's "filesize" value
 * @method File                setExtension()     Sets the current record's "extension" value
 * @method File                setMimetype()      Sets the current record's "mimetype" value
 * @method File                setLocation()      Sets the current record's "location" value
 * @method File                setMetaWidth()     Sets the current record's "meta_width" value
 * @method File                setMetaHeight()    Sets the current record's "meta_height" value
 * @method File                setHash()          Sets the current record's "hash" value
 * @method File                setFileToArticle() Sets the current record's "FileToArticle" collection
 * @method File                setUserToAvatars() Sets the current record's "UserToAvatars" collection
 * 
 * @package    socialhub
 * @subpackage model
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BaseFile extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('file');
        $this->hasColumn('id', 'integer', 4, array(
             'primary' => true,
             'autoincrement' => true,
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('filename', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('filesize', 'integer', 11, array(
             'type' => 'integer',
             'length' => '11',
             ));
        $this->hasColumn('extension', 'string', 25, array(
             'type' => 'string',
             'length' => '25',
             ));
        $this->hasColumn('mimetype', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('location', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('meta_width', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('meta_height', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('hash', 'string', 32, array(
             'type' => 'string',
             'length' => '32',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('FileToArticle', array(
             'local' => 'id',
             'foreign' => 'file_id'));

        $this->hasMany('UserToAvatar as UserToAvatars', array(
             'local' => 'id',
             'foreign' => 'file_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}