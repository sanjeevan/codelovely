<?php

/**
 * blog actions.
 *
 * @package    frostty
 * @subpackage blog
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class blogActions extends ApplicationActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $q = Doctrine_Query::create()
      ->select('be.*')
      ->from('BlogEntry be')
      ->where('be.status = ?', 'Published')
      ->andWhere('be.published_at <= ?', date(myUtil::MYSQL_DATETIME))
      ->orderBy('be.published_at DESC');

    $this->pager = new sfDoctrinePager('BlogEntry', 10);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->setQuery($q);
    $this->pager->init();
  }

  /**
   * Add a new blog post entry
   * 
   * @param sfWebRequest $request
   */
  public function executeNew(sfWebRequest $request)
  {
    $blog_entry = new BlogEntry();
    $blog_entry->setPublishedAt(date(myUtil::MYSQL_DATETIME));

    $this->form = new BlogEntryForm($blog_entry);

    if ($request->isMethod('post')){
      $params = $request->getParameter($this->form->getName());
      $this->form->bind($params);

      if ($this->form->isValid()){
        $blog_entry = $this->form->save();
        $blog_entry->setUser($this->getUser()->getModel());
        $blog_entry->setSummaryHtml(myUtil::markdown($blog_entry->getSummary()));
        $blog_entry->setBodyHtml(myUtil::markdown($blog_entry->getBody()));
        $blog_entry->save();

        $this->getUser()->setFlash('notice', 'New post addded');
        $this->redirect($request->getReferer());
      } else {
        $this->getUser()->setFlash('error', 'There was an error in saving the post');
      }
    }
  }

  /**
   * Edit existing blog post entry
   * 
   * @param sfWebRequest $request
   */
  public function executeEdit(sfWebRequest $request)
  {
    $blog_entry = Doctrine::getTable('BlogEntry')->find($request->getParameter('id'));

    if (!$blog_entry){
      $this->forward404('Could not find post to edit');
    }

    if (!$this->getUser()->isAdmin()){
      throw new sfSecurityException("You cannot edit this article");
    }

    $this->form = new BlogEntryForm($blog_entry);

    if ($request->isMethod('post')){
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid()){
        $blog_entry = $this->form->save();
        $blog_entry->setUser($this->getUser()->getModel());
        $blog_entry->setSummaryHtml(myUtil::markdown($blog_entry->getSummary()));
        $blog_entry->setBodyHtml(myUtil::markdown($blog_entry->getBody()));
        $blog_entry->save();

        $this->getUser()->setFlash('notice', 'Post updated');
        $this->redirect('post/' . $blog_entry->getSlug());
      }
    }
  }

  /**
   * Delete blog entry
   * 
   * @param sfWebRequest $request
   * @throws sfSecurityException
   */
  public function executeDelete(sfWebRequest $request)
  {
    $post = Doctrine::getTable('BlogEntry')->find($request->getParameter('id'));

    if (!$this->getUser()->isAdmin()){
      throw new sfSecurityException("You cannot delete this article");
    }

    if ($post){
      $post->delete();
      $this->getUser()->setFlash('notice', 'Post has been deleted');
    }

    $this->redirect('@homepage');
  }

  /**
   * Show a single blog post
   * 
   * @param sfWebRequest $request
   */
  public function executeShow(sfWebRequest $request)
  {
    $this->b = Doctrine::getTable('BlogEntry')->findOneBySlug($request->getParameter('slug'));

    if (!$this->b){
      $this->forward404("Could not find post");
    }
  }
}
