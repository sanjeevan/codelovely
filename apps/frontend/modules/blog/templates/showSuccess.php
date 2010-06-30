<div class="post">

<h3 class="title"><?php echo $b->getTitle(); ?></h3>

  <?php echo $b->getSummaryHtml(); ?>

  <?php echo $b->getBodyHtml(); ?>

  <div class="date">Posted on <?php echo $b->getDateTimeObject('published_at')->format('d F Y'); ?></div>

</div>

<?php if ($b->getUserId() == $sf_user->getId() && $sf_user->isAdmin()): ?>

  <span class="pillbox"><?php echo link_to('Edit this post', 'blog/edit?id=' . $b->getId()); ?></span>
  <span class="pillbox"><?php echo link_to('Delete', 'blog/delete?id=' . $b->getId(), array('confirm' => 'Are your sure you want to delete this post')); ?></span>

<?php endif; ?>

