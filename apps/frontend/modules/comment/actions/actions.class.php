<?php

/**
 * comment actions.
 *
 * @package    frostty
 * @subpackage comment
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class commentActions extends sfActions
{
  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }

  /**
  * Delete a comment
  *
  * @param sfWebRequest $request 
  */
  public function executeDelete(sfWebRequest $request)
  {
    $comment = Doctrine::getTable('Comment')->find($request->getParameter('commentid'));
    if (!$comment){
      $this->forward404("Could not find comment to delete");
    }

    if (!$this->getUser()->isAdmin()){
      $this->forward404("You are not an admin");
    }

    $comment->delete();
    $this->getUser()->setFlash('notice', 'Comment deleted');
    $this->redirect($request->getReferer());
  }

  /**
   * Edit a comment
   * 
   * @param sfWebRequest $request
   */
  public function executeAjaxEditComment(sfWebRequest $request)
  {
    $this->comment = Doctrine::getTable('Comment')->find($request->getParameter('commentid'));
    
    if ($request->isMethod('post'))
    {
      $params = $request->getParameter('comment');
      $this->comment->setContent($params['content']);
      $this->comment->setContentHtml(myUtil::markdown($params['content']));
      $this->comment->save();

      return $this->renderText($this->comment->getContentHtml());
    }
  }
  
  /**
   * Add a new reply
   * 
   * @param sfWebRequest $request
   * @throws AppModelNotFoundException
   */
  public function executeAddReply(sfWebRequest $request)
  {
    $article = Doctrine::getTable('Article')->find($request->getParameter('articleid'));

    if (!$article){
      throw new AppModelNotFoundException('Article not found');
    }
    
    $parent_comment = Doctrine::getTable('Comment')->find($request->getParameter('replyid'));
    
    if (!$parent_comment){
      throw new AppModelNotFoundException('Parent comment not found');
    }

    if ($request->isMethod('post')){
      $parameters = $request->getParameter('comment');
      
      $comment = new Comment();
      $comment->setUserId($this->getUser()->getModel());
      $comment->setArticle($article);
      $comment->setContent($parameters['content']);
      $comment->setContentHtml(myUtil::markdown($parameters['content']));
      $comment->setReplyDepth($parent_comment->getReplyDepth() + 1);
      $comment->setReplyId($parent_comment->getId());
      $comment->save();
      
      $article->setTotalComments($article->getTotalComments()+1);
      $article->save();
    }

    $this->redirect($article->getViewUrl() . '#comment-box-' . $comment->getId());
  }

  /**
   * Add new comment
   * 
   * @param sfWebRequest $request
   * @throws AppModelNotFoundException
   */
  public function executeAdd(sfWebRequest $request)
  {
    $article = Doctrine::getTable('Article')->find($request->getParameter('articleid'));

    if (!$article){
      throw new AppModelNotFoundException('Article not found');
    }

    if ($request->isMethod('post')){
      $form = new CommentForm();
      $form->bind($request->getParameter($form->getName()));

      if ($form->isValid()){
        $comment = new Comment();
        $comment->setUserId($this->getUser()->getModel());
        $comment->setArticle($article);
        $comment->setContent($form->getValue('content'));
        $comment->setContentHtml(myUtil::markdown($form->getValue('content')));
        $comment->setReplyDepth(0);
        $comment->setReplyId(0);
        $comment->save();
        
        $article->setTotalComments($article->getTotalComments()+1);
        $article->save();
        
        $this->getUser()->setFlash('notice', 'Comment added');
      } else {
        $request->setParameter('comment_error_schema', $form->getErrorSchema());
        $this->forward('article', 'show');
      }
    }

    $this->redirect($article->getViewUrl() . '#comments');
  }
}
