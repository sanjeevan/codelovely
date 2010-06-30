<?php if ($article->getFlavour() == 'snapshot'): ?>
  <form method="post" action="" enctype="multipart/form-data">
<?php else: ?>
  <form method="post" action="">
<?php endif; ?>
  <ul>
    <?php echo $form; ?>
    <li>
      <button type="submit"><?php echo image_tag('blacks/16x16/checkmark.png'); ?> Save</button>
      <?php echo link_to('Cancel', $article->getViewUrl()); ?>
    </li>
  </ul>
</form>