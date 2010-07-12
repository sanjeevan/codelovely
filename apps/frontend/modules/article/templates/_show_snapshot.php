<div class="profile">
  <img class="photo" src="<?php echo url_for('profile/avatar?userid=' . $user->getId()); ?>" />
  <h1><?php echo $article->getTitle(); ?></h1>
  <ul>
    <li>
      <?php if ($sf_user->getId() == $article->getUserId()): ?>
      <?php echo link_to(image_tag('blacks/16x16/doc_edit'), "article/edit?articleid={$article->getId()}"); ?>
      <?php endif; ?>
      by <?php echo link_to($user->getUsername(), '@show_profile?username=' . $user->getUsername()); ?>
      <span class="sep">|</span>
      <span class="ups"><?php echo image_tag('blacks/16x16/sq_br_up.png'); ?> <?php echo $article->getThing()->getUps(); ?> up votes</span>
      <span class="sep">|</span>
      <span class="downs"><?php echo image_tag('blacks/16x16/sq_br_down.png'); ?> <?php echo $article->getThing()->getDowns(); ?> down votes</span>
      <span class="sep">|</span>
      <?php echo image_tag('blacks/16x16/spechbubble_sq_line.png'); ?> <?php echo intval($article->getTotalComments()); ?> comments
    </li>
  </ul>
  <div class="clear"></div>
</div>

<?php $snapshot = $article->getSnapshot(); ?>

<img class="snapshot" src="<?php echo $snapshot->getUrl(); ?>" />

<p class="snapshot-summary"><?php echo $article->getSummaryHtml(); ?></p>

<div class="clear"></div>