<?php

/**
 * file actions.
 *
 * @package    frostty
 * @subpackage file
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class fileActions extends sfActions
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
  
  public function executeDownload(sfWebRequest $request)
  {
    $file = Doctrine::getTable('File')->find($request->getParameter('fileid'));

    if (!$file)
    {
      $this->forward404('File not found');
    }
    
    header('Cache-control: private');
    header('Pragma: public');
    header('Content-disposition: attachment; filename="' . $file->getFilename() . '"');
    header('Content-type: ' . $file->getMimetype());
    
    readfile($file->getLocation());
    
    exit(0);
  }
  
  public function executeThumbnail(sfWebRequest $request)
  {
    $file = Doctrine::getTable('File')->find($request->getParameter('fileid'));
    $this->forward404Unless($file, "File not found");
    
    $size = $request->getParameter('size', 80);
    $savefile = $file->getThumbnailFilename($size);
    $path = dirname($savefile);
    if (!is_readable($savefile)){
      if (!is_dir($path)){
        mkdir($path, 0775, true);
      }
      
      $thumb = new sfThumbnail($size, $size, true, true, 100, 'sfImageMagickAdapter');
      $thumb->loadFile($file->getLocation());
      $thumb->save($savefile, 'image/png');
      
      header('Content-type: image/png');
      readfile($savefile);
      exit(0);
    } else {
      header('Content-type: image/png');
      readfile($savefile);
      exit(0);
    }
  }
  
  public function executeAjaxDeleteFiles(sfWebRequest $request)
  {
    $file_ids = (array) $request->getParameter('files');

    if (!$this->getUser()->isAdmin()){
      throw new sfSecurityException("You are not an admin");
    }

    foreach ($file_ids as $file_id)
    {
      $fta = Doctrine::getTable('FileToArticle')->findOneByFileId($file_id);
      $file = $fta->getFile();

      $fta->delete();
      $file->delete();
    }

    return $this->renderText('OK');
  }
  
  public function executeDeleteFromArticle(sfWebRequest $request)
  {
    $fta = Doctrine::getTable('FileToArticle')->find($request->getParameter('ftaid'));
    $this->forward404Unless($fta, "File not found");

    if (!$this->getUser()->isAdmin()){
      throw new sfSecurityException("You are not an admin");
    }

    $file = $fta->getFile();
    $fta->delete();
    $file->delete();
    
    $this->redirect($request->getReferer());
  }
}
