<?php

/**
 * user actions.
 *
 * @package    codelovely
 * @subpackage user
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends ApplicationActions
{
  /**
   * Allows user to send their allocated invites
   * 
   * @param sfWebRequest $request
   */
  public function executeInvites(sfWebRequest $request)
  {
    $this->user = $this->getUser()->getModel();

    $q = Doctrine_Query::create()
      ->select('i.*')
      ->from('Invite i')
      ->where('i.user_id =?', $this->user->getId())   
      ->andWhere('i.status = ?', 'unused');
    
    $this->total_invites = $q->count();
    $this->form = new SendInviteForm();
    
    if ($request->isMethod('post')){
      $parameters = $request->getParameter($this->form->getName());
      $this->form->bind($parameters);
      
      if ($this->form->isValid()){
        $invite = Invite::getUnused($this->user);
        
        $template_vars = array(
          'invite' => $invite,
          'name'   => $this->form->getValue('name'),
          'user'   => $this->user,
          'to'     => $this->form->getValue('email')
        );
        
        $message = $this->getPartial('mail/new_invite', $template_vars);
        $email_job = array(
          'to'      => $this->form->getValue('email'),
          'subject' => 'You have been sent an invite to join ' . $_SERVER['HTTP_HOST'],
          'from'    => 'noreply@' . $request->getHost(),
          'message' => $message,
          'invite'  => $invite->toArray()    
        );
        
        $queue = new RedisJobQueue(SendEmailWorker::QUEUE_NAME);
        $queue->addJob($email_job, false, false);
        
        $this->getUser()->setFlash('notice', 'Invite has been sent', false);
      }
    }
  }
  
  /**
   * Set new image for user
   *
   * @param sfWebRequest $request
   * @return integer
   */
  public function executeSaveAvatar(sfWebRequest $request)
  {
    if ($request->isMethod('post')){
      $file = $request->getFiles('avatar');
      $user_to_avatar = $this->getUser()->getModel()->getUserToAvatars()->getFirst();
      
      if ($file['error'] != UPLOAD_ERR_OK){
        $this->getUser()->setFlash('error', 'Upload error');
        return $this->redirect($request->getReferer());
      }      
      
      if (!$user_to_avatar){
        $user_to_avatar = new UserToAvatar();
        $user_to_avatar->setUserId($this->getUser()->getId());
        $user_to_avatar->setIsDefault(true);
        
        $avatar_file = File::factoryFromRequest($request, 'avatar');
        
        $user_to_avatar->setFile($avatar_file);
        $user_to_avatar->save();
      } else {
        $avatar_file = $user_to_avatar->getFile();
        $new_avatar_file = File::factoryFromRequest($request, 'avatar');
        $user_to_avatar->setFile($new_avatar_file);
        $user_to_avatar->save();
        
        $avatar_file->delete();
      }
      
      $this->getUser()->setFlash('notice', 'Avatar Updated');
      $this->redirect($request->getReferer());
    }
  }
  
  /**
   * Settings page for user
   *
   * @param sfWebRequest $request
   */
  public function executeSettings(sfWebRequest $request)
  {
    $user = $this->getUser()->getModel();
    
    $this->security_form = new SettingsForm($user->toArray());
    $this->personal_form = new PersonalSettingsForm($user->toArray());

    if ($user->getUserToAvatars()->count() > 0){
      $this->avatar = $user->getUserToAvatars()->getFirst();
    }

    if ($request->isMethod('post')){

      // update security information
      if ($request->hasParameter($this->security_form->getName())){        
        $parameters = $request->getParameter($this->security_form->getName());
        $this->security_form->bind($parameters);

        if ($this->security_form->isValid()){
          $pass = $this->security_form->getValue('password1');
          $user->setEmail($this->security_form->getValue('email'));
          
          if (strlen($pass) > 6){
            $user->setPlaintextPassword($pass);
          }
          $user->save();
        }
      }
      
      // update personal information
      if ($request->hasParameter($this->personal_form->getName())){
        $parameters = $request->getParameter($this->personal_form->getName());
        $this->personal_form->bind($parameters);
        if ($this->personal_form->isValid()){
          $user->setFirstname($this->personal_form->getValue('firstname'));
          $user->setLastname($this->personal_form->getValue('lastname'));
          $user->setSkills($this->personal_form->getValue('skills'));
          $user->setTwitter($this->personal_form->getValue('twitter'));
          $user->setWebsiteUrl($this->personal_form->getValue('website_url'));
          $user->save();
        } 
      }
    }
  }

  /**
   * Log out current user
   *
   * @param sfWebRequest $request
   */
  public function executeLogout(sfWebRequest $request)
  {
    $this->getUser()->signOut();
    $this->redirect('@homepage');
  }

  /**
   * Login a user
   *
   * @param sfWebRequest $request
   */
  public function executeLogin(sfWebRequest $request)
  {
    $this->form = new UserLoginForm();

    if ($request->isMethod('post')){
      $this->form->bind($request->getParameter($this->form->getName()));

      if ($this->form->isValid()){
        $username = $this->form->getValue('username');
        $password = $this->form->getValue('password');

        $q = Doctrine_Query::create()->from('User u')->where('u.username = ?', $username);
        $user = $q->fetchOne();

        if (!$user){
          $this->getUser()->setFlash('error',  'Password is incorrect');
          return $this->redirect($request->getReferer());
        }

        if ($user->isCorrectPassword($password)){
          $this->getUser()->signIn($user);
          $this->getUser()->setFlash('notice', 'Welcome back ' . $user->firstname);
          return $this->redirect("@homepage");
        } else {
          $this->getUser()->setFlash('error',  'Password is incorrect');
          return $this->redirect($request->getReferer());
        }
      }
    }
  }

  /**
   * Create new account
   *
   * @param sfWebRequest $request
   */
  public function executeSignUp(sfWebRequest $request)
  {
    $this->form = new NewUserForm();
    $this->form->enableCaptcha();
    //$this->form->enableInvites();
    
    if ($request->hasParameter('invite')){
      $this->form->setDefault('invite', $request->getParameter('invite'));
    }

    if ($request->isMethod('post')){
      if ($this->form->isCaptchaEnabled()){
        $captcha = array(
          'recaptcha_challenge_field' => $request->getParameter('recaptcha_challenge_field'),
          'recaptcha_response_field'  => $request->getParameter('recaptcha_response_field'),
        );
  
        $params = $request->getParameter($this->form->getName());
        $params = array_merge($params, array('captcha' => $captcha));
      } else {
        $params = $request->getParameter($this->form->getName());
      }
      
      $this->form->bind($params);
      
      if ($this->form->isValid()){
        $user = $this->form->save();
        
        $message = $this->getPartial('mail/new_account', array('user' => $user));
        $email_job = array(
          'to' => $user->getEmail(),
          'subject' => 'Welcome to ' . sfConfig::get('app_name'),
          'from' => 'noreply@' . $request->getHost(),
          'message' => $message
        );
        
        // add to job queue
        $queue = new RedisJobQueue(SendEmailWorker::QUEUE_NAME);
        $queue->addJob($email_job, false, false);

        $this->getUser()->signIn($user);
        $this->getUser()->setFlash('notice', "Account created. You are now signed in. Welcome {$user->getFirstname()}");
        $this->redirect('@homepage');
      }
    }
  }
  
  /**
   * Send new password to user
   * 
   * @param sfWebRequest $request
   */
  public function executeForgotPassword(sfWebRequest $request)
  {
    if ($request->isMethod('post')){
      $user = Doctrine::getTable('User')->findOneByEmail($request->getParameter('email'));
      
      if (!$user){
        $this->getUser()->setFlash('error', 'That email is not is use');
        $this->redirect('user/forgotPassword');
      } else {
        $no_reply_address = 'noreply@' . $request->getHost();
        
        $passwd = myUtil::getRandomSalt(8);
        $user->setPlaintextPassword($passwd);
        $user->save();
        
        $this->getMailer()->composeAndSend(
          $no_reply_address, 
          $user->getEmail(), 
          "Password", 
          $this->getPartial('forgot_password', array('user' => $user, 'pass' => $passwd))
        );
        
        $this->getUser()->setFlash('notice', 'A new password has been emailed to you');
        $this->redirect('user/forgotPassword');
      }
    }
  }
}
