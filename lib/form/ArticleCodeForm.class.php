<?php

class ArticleCodeForm extends BaseForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'     => new sfWidgetFormInputText(),
      'code'      => new sfWidgetFormTextarea(),
      'language'  => new sfWidgetFormSelect(array('choices' => $this->getLanguageOptions())),
      'summary'   => new sfWidgetFormTextarea(),
      'type'      => new sfWidgetFormInputHidden()
    ));

    $this->setValidators(array(
      'title'     => new sfValidatorString(array('required' => true, 'max_length' => 255, 'min_length' => 5)),
      'code'      => new sfValidatorString(array('required' => true)),
      'language'  => new sfValidatorChoice(array('choices' => $this->getLanguageOptions())),
      'summary'   => new sfValidatorString(array('required' => false)),
      'type'      => new sfValidatorString(array('required' => true))
    ));

    $this->widgetSchema->setLabels(array(
      'code'    => 'Your code snippet',
      'summary' => 'A short description of the code'
    ));

    $this->widgetSchema->setFormFormatterName('list');
    $this->widgetSchema->setNameFormat('article[%s]');

    parent::setup();
  }
  
  /**
   * Create form, and populate values from an article
   * 
   * @param Article $article
   */
  public static function fromModel(Article $article)
  {
    $defaults = array(
      'type'      => 'code',
      'title'     => $article->getTitle(),
      'language'  => $article->getCodeLanguage(),
      'summary'   => $article->getSummary(),
      'code'      => $article->getCode()
    );
    
    return new ArticleCodeForm($defaults);
  }
  
  /**
   * Get options for supported code languages
   * 
   * @return array
   */
  public function getLanguageOptions()
  {
    $languages = Article::getSupportedCodeLanguages();
    $options = array_keys($languages);
    
    return array_combine($options, $options);
  }

  /**
   * Save the form and create the appropiate model
   * 
   * @param User $user
   * @param Article $article
   */
  public function save(User $user = null, $article = null)
  {
    if ($article === null){
      $article = new Article();
    }
    
    $article->setUserId($user->getId());
    $article->setUsername($user->getUsername());
    $article->setFlavour('code');
    $article->setHasThumbnails(false);

    if (strlen($this->getValue('summary')) > 0){
      $article->setSummary($this->getValue('summary'));
      $article->setSummaryHtml(myUtil::markdown($this->getValue('summary')));
    }

    $article->setTitle($this->getValue('title'));
    $article->setCode($this->getValue('code'));
    $article->setCodeLanguage($this->getValue('language'));
    $article->save();

    return $article;
  }
}