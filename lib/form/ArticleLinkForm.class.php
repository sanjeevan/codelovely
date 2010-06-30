<?php

class ArticleLinkForm extends BaseForm
{
  private $job = null;

  public function setup()
  {
    $this->setWidgets(array(
      'title'     => new sfWidgetFormInputText(),
      'url'       => new sfWidgetFormInputText(),
      'summary'   => new sfWidgetFormTextarea(),
      'images'    => new sfWidgetFormInputCheckbox(array('value_attribute_value' => 1)),
      //'captcha'   => new sfWidgetFormReCaptcha(array('public_key' => sfConfig::get('app_recaptcha_public'))),
      'type'      => new sfWidgetFormInputHidden()
    ));
        
    $this->setValidators(array(
      'title'     => new sfValidatorString(array('required' => true, 'max_length' => 255, 'min_length' => 5)),
      'url'       => new sfValidatorUrl(array('required' => true)),
      'summary'   => new sfValidatorString(array('required' => false)),
      //'captcha'   => new sfValidatorReCaptcha(array('private_key' => sfConfig::get('app_recaptcha_private'))),
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
    /*
    if (sfContext::getInstance()->getUser()->isAdmin()){
      unset($this['captcha']);
    }
    */
    parent::setup();
  }
  
  public function setEditMode()
  {
    unset($this['images']);
  }
  
  
  public static function fromModel(Article $article)
  {
    $defaults = array(
      'title' => $article->getTitle(),
      'url' => $article->getUrl(),
      'summary' => $article->getSummary(),
      'type' => 'link'
    );
    
    return new ArticleLinkForm($defaults);
  }

  public function save(User $user = null, $article = null)
  {
    if ($article === null){
      $article = new Article();
    }
    
    $article->setUserId($user->getId());
    $article->setUsername($user->getUsername());
    $article->setTitle($this->getValue('title'));
    $article->setUrl($this->getValue('url'));

    if (strlen($this->getValue('summary')) > 0){
      $article->setSummary($this->getValue('summary'));
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

  public function getJob()
  {
    return $this->job;
  }
}
