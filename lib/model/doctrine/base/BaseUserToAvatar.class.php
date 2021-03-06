<?php

/**
 * BaseUserToAvatar
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property boolean $is_default
 * @property integer $user_id
 * @property integer $file_id
 * @property User $User
 * @property File $File
 * 
 * @method integer      getId()         Returns the current record's "id" value
 * @method boolean      getIsDefault()  Returns the current record's "is_default" value
 * @method integer      getUserId()     Returns the current record's "user_id" value
 * @method integer      getFileId()     Returns the current record's "file_id" value
 * @method User         getUser()       Returns the current record's "User" value
 * @method File         getFile()       Returns the current record's "File" value
 * @method UserToAvatar setId()         Sets the current record's "id" value
 * @method UserToAvatar setIsDefault()  Sets the current record's "is_default" value
 * @method UserToAvatar setUserId()     Sets the current record's "user_id" value
 * @method UserToAvatar setFileId()     Sets the current record's "file_id" value
 * @method UserToAvatar setUser()       Sets the current record's "User" value
 * @method UserToAvatar setFile()       Sets the current record's "File" value
 * 
 * @package    socialhub
 * @subpackage model
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BaseUserToAvatar extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('user_to_avatar');
        $this->hasColumn('id', 'integer', 4, array(
             'primary' => true,
             'autoincrement' => true,
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('is_default', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('file_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $this->hasOne('File', array(
             'local' => 'file_id',
             'foreign' => 'id'));
    }
}