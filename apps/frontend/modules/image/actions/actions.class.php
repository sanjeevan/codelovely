<?php

/**
 * image actions.
 *
 * @package    codelovely
 * @subpackage image
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class imageActions extends sfActions
{
  public function executeCropSnapshot(sfWebRequest $request)
  {
    $this->article = Doctrine::getTable('Article')->find($request->getParameter('articleid'));
    $this->file = $this->article->getSnapshot();
    
    if (!$this->file){
      $this->forward404("Snapshot not found");
    }
    
    $image = new sfImage($this->file->getLocation(), $this->file->getMimeType());
    $this->imgw = $image->getWidth();
    $this->imgh = $image->getHeight();
    
    
    if ($request->isMethod('post')){
      $left = $request->getParameter('x');
      $top = $request->getParameter('y');
      $image->crop($left, $top, 400, 300);
      $image->save();
      
      $this->file->setHash(sha1_file($this->file->getLocation()));
      $this->file->save();
      
      $this->getUser()->setFlash('notice', 'Image has been cropped');
      $this->redirect($this->article->getViewUrl());
    }
    
    /*
    $image = new sfImage($this->file->getLocation(), $this->file->getMimeType());
    $max_width = 800;    
    
    if ($image->getWidth() > $max_width){
      $thumb = new sfThumbnail($max_width, $max_width, true, true, 100);
      $thumb->loadFile($this->file->getLocation());
      $thumb->save($this->file->getLocation(), 'image/png');

      $this->file->setHash(sha1_file($this->file->getLocation()));
      $this->file->save();
    }
    */
  }
}
