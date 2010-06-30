<?php

class ArticleSnapshotForm extends BaseForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'     => new sfWidgetFormInputText(),
      'snapshot'  => new sfWidgetFormInputFile(),
      'summary'   => new sfWidgetFormTextarea(),
      'type'      => new sfWidgetFormInputHidden()
    ));

    $snapshot_validators = array(
      new sfValidatorFile(array('mime_types' => 'web_images')),
      new sfValidatorCallback(array('callback' => array($this, 'checkSnapshotDimensions')))
    );
    
    $this->setValidators(array(
      'title'     => new sfValidatorString(array('required' => true, 'max_length' => 255, 'min_length' => 5)),
      'snapshot'  => new sfValidatorAnd($snapshot_validators),
      'summary'   => new sfValidatorString(array('required' => false)),
      'type'      => new sfValidatorString(array('required' => true))
    ));

    $this->widgetSchema->setFormFormatterName('list');
    $this->widgetSchema->setNameFormat('article[%s]');

    parent::setup();
  }
  
  /**
   * Checks that the minimum size of the uploaded image is 400x300
   * 
   * @param sfValidator $validator
   * @param mixed $value
   * @throws sfValidatorError
   */
  public function checkSnapshotDimensions($validator, $value)
  {
    if ($value instanceof sfValidatedFile){
      $size = @getimagesize($value->getTempName());  
      $w = sfConfig::get('app_image_snapshotw');
      $h = sfConfig::get('app_image_snapshoth');
      if ($size[0] <  $w || $size[1] < $h){
        throw new sfValidatorError($validator, "Snapshot must be at least $w x $h pixels");
      }
    }
    
    return $value;
  }
  
  /**
   * If called, it will make the file upload optional
   *
   */
  public function setEditMode()
  {
    $this->validatorSchema['snapshot'] = new sfValidatorFile(array('required' => false));
  }
  
  /**
   * Creates the from from an existing Article model, and populates the default
   * values
   * 
   * @param Article $article
   */
  public static function fromModel(Article $article)
  {
    $defaults = array(
      'title' => $article->getTitle(),
      'summary' => $article->getSummary(),
      'type' => 'snapshot'
    );
    
    return new ArticleSnapshotForm($defaults);
  }

  /**
   * Save the form, will create the article. If $article is not null, then the
   * existing article will be updated
   * 
   * @param User $user
   * @param Article $article
   */
  public function save(User $user = null, $article = null)
  {
    $edit_mode = false;
    
    if ($article === null){
      $article = new Article();  
    } else {
      $edit_mode = true;
    }
    
    $article->setUserId($user->getId());
    $article->setUsername($user->getUsername());
    $article->setTitle($this->getValue('title'));
    $article->setFlavour('snapshot');
    $article->setHasThumbnails(false);
    
    if (strlen($this->getValue('summary')) > 0){
      $article->setSummary($this->getValue('summary'));
    }
    $article->save();
    
    $vfile = $this->getValue('snapshot');
    
    if ($vfile instanceof sfValidatedFile){
      if ($edit_mode){
        $article->deleteFiles();
      }
      
      $file = new File();
      $file->setFilename($vfile->getOriginalName());
      $file->setFilesize($vfile->getSize());
      $file->setMimetype($vfile->getType());
      $file->setExtension(myUtil::getFileExtension($file->getFilename()));
      $file->setHash(sha1_file($vfile->getTempName()));
      $file->useTempFile($vfile->getTempName());
      $file->save();
  
      $fta = new FileToArticle();
      $fta->setFile($file);
      $fta->setArticle($article);
      $fta->setIsPublished(true);
      $fta->save();
    }

    return $article;
  }
}