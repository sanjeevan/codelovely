<?php

class ArticleQuestionForm extends BaseForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'     => new sfWidgetFormInputText(),
      'question'  => new sfWidgetFormTextarea(),
      'type'      => new sfWidgetFormInputHidden()
    ));

    $this->setValidators(array(
      'title'     => new sfValidatorString(array('required' => true, 'max_length' => 255, 'min_length' => 5)),
      'question'  => new sfValidatorString(array('required' => true)),
      'type'      => new sfValidatorString(array('required' => true))
    ));

    $this->widgetSchema->setLabels(array(
      'question' => 'What question do you want answered?'
    ));

    $this->widgetSchema->setFormFormatterName('list');
    $this->widgetSchema->setNameFormat('article[%s]');

    parent::setup();
  }
  
  public static function fromModel(Article $article)
  {
    $defaults = array(
      'title' => $article->getTitle(),
      'question' => $article->getQuestion(),
      'type' => 'question'
    );
    
    return new ArticleQuestionForm($defaults);
  }

  public function save(User $user, $article = null)
  {
    if ($article === null){
      $article = new Article();  
    }
    
    $article->setUserId($user->getId());
    $article->setUsername($user->getUsername());
    $article->setTitle($this->getValue('title'));
    $article->setQuestion($this->getValue('question'));
    $article->setFlavour('question');
    $article->setHasThumbnails(false);
    $article->save();

    return $article;
  }
}