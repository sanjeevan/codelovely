<?php

class ArticleLinkForm extends BaseForm
{
  private $job = null;
  
  /**
   * True if we're editing an existing article
   * 
   * @var boolean
   */
  private $edit_mode = false;
  
  /**
   * Article that this form is editing
   * 
   * @var Article
   */
  private $object = null;

  public function setup()
  {
    $this->setWidgets(array(
      'title'     => new sfWidgetFormInputText(),
      'url'       => new sfWidgetFormInputText(),
      'summary'   => new sfWidgetFormTextarea(),
      'images'    => new sfWidgetFormInputCheckbox(array('value_attribute_value' => 1)),
      'type'      => new sfWidgetFormInputHidden()
    ));
    
    $url_validator = new sfValidatorAnd(array(
      new sfValidatorUrl(),
      new sfValidatorCallback(array('callback' => array($this, 'uniqueUrl')))
    ), array('required' => true));
        
    $this->setValidators(array(
      'title'     => new sfValidatorString(array('required' => true, 'max_length' => 255, 'min_length' => 5)),
      'url'       => $url_validator,
      'summary'   => new sfValidatorString(array('required' => false)),
      'type'      => new sfValidatorString(array('required' => true)),
      'images'    => new sfValidatorInteger(array('required' => false))
    ));

    $this->widgetSchema->setLabels(array(
      'images'  => 'Fetch images from this page?',
      'summary' => 'Tell us about this link'
    ));

    $this->widgetSchema->setHelps(array(
      'images'  => "If selected, we'll grab thumbnails of pictures on that page"
    ));

    $this->widgetSchema->setFormFormatterName('list');
    $this->widgetSchema->setNameFormat('article[%s]');
    
    parent::setup();
  }
  
  /**
   * If set to true, we remvoe fetching of thumbnails
   * 
   */
  public function setEditMode()
  {
    unset($this['images']);
    $this->edit_mode = true;
  }
  
  /**
   * Set the object we're editing
   * 
   * @param Article $article
   */
  public function setObject(Article $article)
  {
    $this->object = $article;
  }
  
  /**
   * Checks if the passed value which is a URL is unique
   * 
   * @param sfValidator $validator
   * @param mixed $value
   */
  public function uniqueUrl($validator, $value)
  {
    $q = Doctrine_Query::create()
      ->select('a.*')
      ->from('Article a')
      ->where('a.url = ?', $value)
      ->limit(1);
      
    if ($this->object instanceof Article){
      $q->andWhere('a.id != ?', $this->object->getId());
    }

    $article = $q->fetchOne();  
    
    if ($article){
      $title = htmlspecialchars($article->getTitle());
      $link = "<a target='_blank' href='/{$article->getFlavour()}/{$article->getSlug()}'>{$title}<a/>";
      throw new sfValidatorError($validator, "That url has already been submitted, see: $link");
    }
    
    return $value;
  }
  
  /**
   * Create form, and populate values from specified models
   * 
   * @param Article $article
   * @return ArticleLinkForm
   */
  public static function fromModel(Article $article)
  {
    $defaults = array(
      'title' => $article->getTitle(),
      'url' => $article->getUrl(),
      'summary' => $article->getSummary(),
      'type' => 'link'
    );
    
    $form = new ArticleLinkForm($defaults);
    $form->setObject($article);    
    
    return $form;
  }

  public function save(User $user = null, $article = null)
  {
    if ($article === null){
      $article = new Article();
    }
    
    $article->setUserId($user->getId());
    $article->setUsername($user->getUsername());
    $article->setTitle($this->getValueEscaped('title'));
    $article->setUrl($this->getValue('url'));

    if (strlen($this->getValue('summary')) > 0){
      $article->setSummary($this->getValue('summary'));
      $article->setSummaryHtml(myUtil::markdown($this->getValue('summary')));
    }

    $article->setFlavour('link');
    $article->save();

    if ($this->getValue('images') == 1){
      $job_data = array(
        'url'         => $article->getUrl(),
        'article_id'  => $article->getId(),
      );

      $job_queue = new RedisJobQueue(ThumbnailScraperWorker::QUEUE_NAME);
      $this->job = $job_queue->addJob($job_data, 120); // expire after 120 seconds
    }

    return $article;
  }

  /**
   * Get job information for fetching thumbnails
   * 
   * @return array
   */
  public function getJob()
  {
    return $this->job;
  }
}
