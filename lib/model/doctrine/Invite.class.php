<?php

/**
 * Invite
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    codelovely
 * @subpackage model
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
class Invite extends BaseInvite
{
  const TTL = -1;
  
  /**
   * Returns true if sent invite has expired
   * 
   * @return boolean
   */
  public function hasInviteExpired()
  {
    //$sent_at = $this->getDateTimeObject('sent_at')->format('U');
    return false;
  }
  
  /**
   * Generates an invite code
   * 
   * @return string
   */
  public static function generateCode($length = 6)
  {
    $code = myUtil::getRandomSalt($length);
    return strtoupper($code); 
  }
  
  /**
   * Create an invite for a user's account
   * 
   * @param User $user
   */
  public static function createInvite(User $user)
  {
    $invite = new Invite();
    $invite->setUser($user);
    $invite->setStatus('unused');
    $invite->setCode(self::generateCode(10));
    $invite->save();
    
    return $invite;
  }
  
  /**
   * Get a single unsed invite for a user
   * 
   * @param User $user
   * @return Invite
   */
  public static function getUnused(User $user)
  {
    $q = Doctrine_Query::create()
      ->select('i.*')
      ->from('Invite i')
      ->where('i.user_id = ? and i.status = ?', array($user->getId(), 'unused'))
      ->limit(1);
      
    return $q->fetchOne();
  }
}
