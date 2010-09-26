<div class="profile">
  <img class="photo" src="<?php echo url_for('profile/avatar?userid=' . $user->getId()); ?>" />
  <h1><?php echo link_to($article->getTitle(), $article->getUrl(), array('target' => '_blank')); ?></h1>
  <p class="url"><?php echo $article->getUrl(); ?></p>
  <ul>
    <li>
      <?php if ($sf_user->getId() == $article->getUserId()): ?>
      <?php echo link_to(image_tag('blacks/16x16/doc_edit'), "article/edit?articleid={$article->getId()}"); ?>
      <?php endif; ?>
      Added by <?php echo link_to($user->getUsername(), '@show_profile?username=' . $user->getUsername()); ?>
      <span class="sep">|</span>
      <span class="points"><?php echo $article->getThing()->getScore(); ?> points</span>
      <span class="sep">|</span>
      <span class="ups"><?php echo image_tag('blacks/16x16/sq_br_up.png'); ?> <?php echo $article->getThing()->getUps(); ?> up votes</span>
      <span class="sep">|</span>
      <span class="downs"><?php echo image_tag('blacks/16x16/sq_br_down.png'); ?> <?php echo $article->getThing()->getDowns(); ?> down votes</span>
      <span class="sep">|</span>
      <?php echo image_tag('blacks/16x16/spechbubble_sq_line.png'); ?> <?php echo intval($article->getTotalComments()); ?> comments
      <span class="sep">|</span>
      <?php echo image_tag('blacks/16x16/top_right_expand.png'); ?> via <?php echo link_to($domain, 'http://' . $domain, array('target' => '_blank')); ?>
    </li>
  </ul>
  <div class="clear"></div>
</div>

<div class="summary">
  <?php if (strlen($article->getSummary()) > 0): ?>
    <?php echo $article->getSummaryHtml(); ?>
  <?php endif; ?>
</div>

<?php $ftas = $article->getFiles(true); ?>
<?php if (count($ftas) > 0): ?>
<div class="preview">
  <?php foreach ($ftas as $fta): ?>
    <img class="preview-image" src="<?php echo $fta->getFile()->getThumbnailUrl(); ?>" />
  <?php endforeach; ?>
</div>
<?php endif; ?>