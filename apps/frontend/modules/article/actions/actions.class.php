<?php

/**
 * article actions.
 *
 * @package    frostty
 * @subpackage article
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class articleActions extends ApplicationActions
{
  /**
   * View for popular articles
   * 
   * @param sfWebRequest $request
   */
  public function executeHot(sfWebRequest $request)
  {
    $this->flavour = $request->getParameter('flavour', 'all');
    
    $q = Doctrine_Query::create()->select('a.*, t.*, v.*')
      ->from('article a, a.Thing t')
      ->groupBy('a.id')
      ->orderBy('t.hot DESC');
    
    if (in_array($this->flavour, Article::getFlavours())){
      $q->where('a.flavour = ?', $this->flavour);
    } else {
      $this->flavour = 'all';
    }
    
    $cache_hash = array(
      'hot',
      'flavour-' . $this->flavour,
      'page-'  . $request->getParameter('page', 1),
      'perpage-' . sfConfig::get('app_things_perpage')  
    );
    
    $cache_hash = implode('_', $cache_hash);
    $q->useResultCache(true, 3600, $cache_hash);
    
    $this->pager = new sfDoctrinePager('Article', sfConfig::get('app_things_perpage'));
    $this->pager->setQuery($q);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
        
    $this->insertCodeFlavouredResources();
    
    // page title
    $title = '';
    if ($this->flavour != 'all'){
      $title .= 'Hot ' . Article::getFlavourName($this->flavour);
      $title .= ' - Page ' . $request->getParameter('page', 1);
      $title .= ' - ' . sfConfig::get('app_name');
    } else {
      $title .= sfConfig::get('app_tagline');
      $title .= ' - Page ' . $request->getParameter('page', 1);
      $title .= ' - ' . sfConfig::get('app_name');
    }
    $this->getResponse()->setTitle($title);
  }

  /**
   * View for latest articles
   * 
   * @param $request
   */
  public function executeLatest(sfWebRequest $request)
  {
    $this->flavour = $request->getParameter('flavour', 'all');
    
    $q = Doctrine_Query::create()->select('a.*, t.*, v.*')
      ->from('article a, a.Thing t')
      ->groupBy('a.id')
      ->orderBy('a.created_at DESC');
    
    if (in_array($this->flavour, Article::getFlavours())){
      $q->where('a.flavour = ?', $this->flavour);
    } else {
      $this->flavour = 'all';
    }
    
    $cache_hash = array(
      'latest',
      'flavour-' . $this->flavour,
      'page-' . $request->getParameter('page', 1),
      'perpage-' . sfConfig::get('app_things_perpage')  
    );
    
    $cache_hash = implode('_', $cache_hash);
    $q->useResultCache(true, 3600, $cache_hash);

    $this->pager = new sfDoctrinePager('Article', sfConfig::get('app_things_perpage'));
    $this->pager->setQuery($q);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
    
    $this->insertCodeFlavouredResources();
    
    // page title
    $title = '';
    if ($this->flavour != 'all'){
      $title .= 'Latest ' . Article::getFlavourName($this->flavour);
      $title .= ' - Page ' . $request->getParameter('page', 1);
      $title .= ' - ' . sfConfig::get('app_name');
    } else {
      $title .= sfConfig::get('app_tagline');
      $title .= ' - Page ' . $request->getParameter('page', 1);
      $title .= ' - ' . sfConfig::get('app_name');
    }
    $this->getResponse()->setTitle($title);
  }
  
  /**
   * Insert syntax highlighter js and related code brushes, if there are code
   * flavoured articles in the pager assigned to the current template
   * 
   */
  private function insertCodeFlavouredResources()
  {
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
  }

  /**
   * Delete an article
   * 
   * @param sfWebRequest $request
   * @throws sfSecurityException
   */
  public function executeDelete(sfWebRequest $request)
  {
    $article = Doctrine::getTable('Article')->find($request->getParameter('articleid'));

    if (!$article){
      $this->getUser()->setFlash('error', 'Article not found');
      $this->redirect($request->getReferer());
    }

    if (!$this->getUser()->isAdmin()){
      throw new sfSecurityException("You cannot delete this article");
    }
    
    if ($article->getUserId() != $this->getUser()->getId()){
      $this->getUser()->setFlash('error', 'You cannot delete this article!');
      $this->redirect($request->getReferer());
    }
    
    $article->delete();
    $this->getUser()->setFlash('notice', 'Article deleted');
    $this->redirect($request->getReferer());
  }
  	
  /**
   * View article, by its slug
   *
   * @param sfWebRequest $request
   */
  public function executeSlug(sfWebRequest $request)
  {
    $article = Doctrine::getTable('Article')->findOneBySlug($request->getParameter('slug'));
    
    if (!$article){
      $this->forward404("Item not found");
    }
    
    $this->populateArticleInformation($article, $request);
    $this->setTemplate('show');
  }
  
  /**
   * Show article in more detail
   * 
   * @param sfWebRequest $request
   */
  public function executeShow(sfWebRequest $request)
  {
    $article = Doctrine::getTable('Article')->find($request->getParameter('articleid'));
    
    if (!$article){
      $this->forward404("Article not found");
    }
    
    $this->populateArticleInformation($article, $request);
  }
  
  /**
   * Add article information to template
   * 
   * @param Article $article
   * @param sfWebRequest $request
   */
  private function populateArticleInformation(Article $article, sfWebRequest $request)
  {
    $this->article = $article;
    
    if ($this->article->getFlavour() == 'link'){
      // get domain for article
      $url_info = parse_url($this->article->getUrl());
      $this->domain = $url_info['host'];
    }
    
    // comments
    $this->new_comment_form = new CommentForm();
    
    if ($request->hasParameter('comment_error_schema')){
      $this->new_comment_form->getErrorSchema()->addErrors($request->getParameter('comment_error_schema'));
    }
    
    $q = Doctrine_Query::create()->select('c.*, t.*, u.*, v.*')
      ->from('Comment c')
      ->where('c.article_id = ?', $this->article->getId())
      ->innerJoin('c.User u')
      ->innerJoin('c.Thing t')
      ->leftJoin('t.Vote v with v.user_id = ?', $this->getUser()->getId())
      ->orderBy('t.hot DESC');
     
    $cache_hash = array(
      'articlecomments-' . $this->article->getId(),
      $this->getUser()->getId(),
    );
    
    $cache_hash = implode('_', $cache_hash);
    $q->useResultCache(true, 600, $cache_hash);
    
    $this->comments = $q->execute(null, Doctrine::HYDRATE_ARRAY);
    
    if ($this->article->getFlavour() == 'code'){
      $this->getResponse()->addJavascript('lib/sh/src/shCore.js');
      $this->getResponse()->addStylesheet('/js/lib/sh/styles/shCore.css');
      $this->getResponse()->addStylesheet('/js/lib/sh/styles/shThemeEclipse.css');
      if ($brush = $this->article->getLanguageBrushJavascript()){
        $this->getResponse()->addJavascript('/js/lib/sh/scripts/' . $brush);
      } 
    }
    
    $this->getResponse()->setTitle($article->getTitle());
  }
  
  /**
   * Edit an existing article
   * 
   * @param sfWebRequest $request
   */
  public function executeEdit(sfWebRequest $request)
  {
    $this->article = Doctrine::getTable('Article')->find($request->getParameter('articleid'));
    if (!$this->article){
      $this->forward404("Article not found");
    }
    
    if ($this->article->getUserId() != $this->getUser()->getId()){
      $this->forward404("You cannot edit this item");
    }
    
    $this->form = $this->article->getFlavouredForm();
    if (method_exists($this->form, 'setEditMode')){
      $this->form->setEditMode();
    }

    if ($request->isMethod('post')){
      $params = $request->getParameter($this->form->getName());
      
      if ($this->article->getFlavour() == 'snapshot'){
        $this->form->bind($params, $request->getFiles('article')); 
      } else {
        $this->form->bind($params);
      }
      
      if ($this->form->isValid()){
        $this->form->save($this->getUser()->getModel(), $this->article);
        $this->redirect($this->article->getViewUrl());
      }
    }
  }
  
  /**
   * Create a new article
   * 
   * @param sfWebRequest $request
   */
  public function executeNewItem(sfWebRequest $request)
  {
    $this->forms = array(
      'link'      => new ArticleLinkForm(array('type' => 'link')),
      'question'  => new ArticleQuestionForm(array('type' => 'question')),
      'code'      => new ArticleCodeForm(array('type' => 'code')),
      'snapshot'  => new ArticleSnapshotForm(array('type' => 'snapshot'))
    );
    
    $types = array_keys($this->forms);

    if ($request->isMethod('post')){
      $params = $request->getParameter('article');
      $type = $params['type'];

      if (!in_array($type, $types)){
        $this->forward404('Not a valid type');
      }
      
      if ($type == 'snapshot'){
        $this->forms[$type]->bind($params, $request->getFiles('article'));
      } else {
        $this->forms[$type]->bind($params);
      }

      if ($this->forms[$type]->isValid()){
        $user = $this->getUser()->getModel();
        $article = $this->forms[$type]->save($user);

        // for links with has thumbs
        if ($type == 'link' && $this->forms[$type]->getJob() !== null){
          $job = $this->forms[$type]->getJob();
          $url = "article/fetchingThumbs?articleid={$article->getId()}&jobid={$job['id']}";
          $this->redirect($url);
        }
        
        if ($type == 'snapshot'){
          $url = "image/cropSnapshot?articleid={$article->getId()}";
          $this->redirect($url);
        }

        // everything else
        if ($type == 'link' || $type == 'question' || $type == 'code'){
          $this->redirect($article->getViewUrl());
        }
      }
    }
  }

  /**
   * This is shown after an article is submitted, it waits until the get-thumbnails
   * worker has gotten thumbnails for this url.
   *
   * @param sfWebRequest $request
   */
  public function executeFetchingThumbs(sfWebRequest $request)
  {
    $this->article = Doctrine::getTable('Article')->find($request->getParameter('articleid'));
    if (!$this->article){
      $this->forward404('Error finding valid article');
    }

    $queue = new RedisJobQueue(ThumbnailScraperWorker::QUEUE_NAME);
    $this->job = $queue->getJobMeta($request->getParameter('jobid'));

    if (!$this->job){
      $this->forward404("Invalid job key");
    }
  }

  /**
   * This is the interface, and action where the user will set the thumnails for
   * this article. If the timeout param is present in the request, then the get-
   * thumbnails job didn't run in time, or there was some error
   *
   * @param sfWebRequest $request
   */
  public function executeSetThumbnails(sfWebRequest $request)
  {
    $this->article = Doctrine::getTable('Article')->find($request->getParameter('articleid'));
    if (!$this->article){
      $this->forward404("There was an error fetching the thumbnails!");
    }

    $queue = new RedisJobQueue(ThumbnailScraperWorker::QUEUE_NAME);
    $this->job = $queue->getJobMeta($request->getParameter('jobid'));

    if (!$this->job){
      $this->forward404("Invalid job id");
    }

    $this->files_to_article = $this->article->getFiles();

    if ($request->hasParameter('timeout') || $this->files_to_article->count() == 0){
      $queue->deleteMeta($this->job['id']);
      $this->article->setHasThumbnails(false);
      $this->article->save();
      $this->redirect($this->article->getViewUrl());
    }

    if ($request->isMethod('post')){
      $selected_file_ids = $request->getParameter('thumbnail');
      
      foreach ($this->files_to_article as $fta){
        if (!in_array($fta->getFileId(), $selected_file_ids)){
          $fta->delete();
          $fta->getFile()->delete();
        }
      }

      if (count($selected_file_ids) > 0){
        $this->article->setHasThumbnails(true);
        $this->article->save();
      }

      $queue->deleteMeta($this->job['id']);
      $this->redirect($this->article->getViewUrl());
    }
  }
}
