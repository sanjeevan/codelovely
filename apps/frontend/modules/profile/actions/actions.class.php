<?php

/**
 * profile actions.
 *
 * @package    codelovely
 * @subpackage profile
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class profileActions extends ApplicationActions
{
  /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    // find the user by their username
    $username = $request->getParameter('username');
    $this->user = Doctrine::getTable('User')->findOneByUsername($username);
    $this->forward404Unless($this->user, "Could not find that user");

    $avatars = $this->user->getUserToAvatars();
    $this->avatar = $avatars->getFirst();

    // get the total amount of submitted articles by this user
    $q1 = Doctrine_Query::create()->select('article.id')
      ->from('article')
      ->where('article.user_id = ?', $this->user->getId())
      ->useResultCache(true);

    $this->total_articles = $q1->count();
    
    // get article listing
    $q2 = Doctrine_Query::create()->select('a.*, t.*, v.*')
      ->from('article a, a.Thing t')
      ->where('a.user_id = ?', $this->user->getId())
      ->groupBy('a.id')
      ->orderBy('a.created_at DESC')
      ->useResultCache(true);

    $this->flavour = $request->getParameter('flavour', 'all');  
      
    if (in_array($this->flavour, Article::getFlavours())){
      $q2->andWhere('a.flavour = ?', $this->flavour);
    } else {
      $this->flavour = 'all';
    }
    
    $this->pager = new sfDoctrinePager('Article', sfConfig::get('app_things_perpage'));
    $this->pager->setQuery($q2);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
    
    // insert syntax hightlighter resources for code flavoured articles
    $this->has_code = false;
    foreach ($this->pager->getResults() as $article){
      if ($article->getFlavour() == 'code'){
        $this->has_code = true;
        $this->getResponse()->addJavascript('lib/sh/src/shCore.js');
        $this->getResponse()->addStylesheet('/js/lib/sh/styles/shCore.css');
        $this->getResponse()->addStylesheet('/js/lib/sh/styles/shThemeEclipse.css');
        if ($brush = $article->getLanguageBrushJavascript()){
          $this->getResponse()->addJavascript('/js/lib/sh/scripts/' . $brush);
        }
      }
    }
    
    // set page title
    $fullname = $this->user->getFirstname() . ' ' . $this->user->getLastname();
    $flavour_filter = Article::getFlavourName($this->flavour);
    $title = $fullname;
    if (isset($flavour_filter)){
      $title .= ' - ' . $flavour_filter;
    }
    $title .= ' - ' . sfConfig::get('app_name');
    $this->getResponse()->setTitle($title);
  }
  
  public function executeAvatar(sfWebRequest $request)
  {
    $q1 = Doctrine_Query::create()->select('a.*')
      ->from('UserToAvatar a')
      ->where('a.user_id = ?', $request->getParameter('userid'))
      ->andWhere('a.is_default = 1')
      ->limit(1)
      ->useResultCache(true);
      
    $user_to_avatar = $q1->fetchOne();
    
    if ($user_to_avatar){
      $request->setParameter('fileid', $user_to_avatar->getFileId());
      $request->setParameter('size', 80);
      $this->forward('file', 'thumbnail');
    } else {
      header('Content-type: image/gif');
      readfile(sfConfig::get('sf_web_dir') . '/images/gravatar.gif');
      exit(0);
    }
    
    $this->redirect404();
  }
}
