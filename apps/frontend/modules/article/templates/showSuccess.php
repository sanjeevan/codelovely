<?php use_helper('Date', 'Comment', 'Text', 'JavascriptBase'); ?>
<?php
  if ($article->getFlavour() == 'link'){
    $params = array(
      'article' => $article,
      'domain'  => $domain,
      'user'    => $article->getUser()
    );
    include_partial('article/show_link', $params);
  }
  if ($article->getFlavour() == 'question'){
    $params = array(
      'article' => $article,
      'user'    => $article->getUser()
    );
    include_partial('article/show_question', $params);
  }
  if ($article->getFlavour() == 'snapshot'){
    $params = array(
      'article' => $article,
      'user'    => $article->getUser()
    );
    include_partial('article/show_snapshot', $params);
  }
  if ($article->getFlavour() == 'code'){
    $params = array(
      'article' => $article,
      'user'    => $article->getUser()
    );
    include_partial('article/show_code', $params);
  }
?>

<h1 id="#comments">Comments</h1>

<?php if ($sf_user->isAuthenticated()): ?>
  <?php include_partial('comment/new_comment', array('form' => $new_comment_form, 'article' => $article)); ?>
<?php else: ?>
  <p>You need to be logged in to comment, <?php echo link_to('sign in here', 'user/login'); ?> or <?php echo link_to('create a new account', 'user/signUp'); ?></p>
<?php endif;?>

<?php include_partial('comment/list_comments', array('comments' => $comments, 'article' => $article)); ?>



