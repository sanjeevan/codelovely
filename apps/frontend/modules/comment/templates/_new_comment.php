<form id="new-comment" method="post" action="<?php echo url_for('comment/add?articleid=' . $article->getId()); ?>">
<ul>
  <?php echo $form; ?>
  <li><button type="submit"><?php echo image_tag('blacks/16x16/round_checkmark.png'); ?> Add comment</button></li>
</ul>
</form>
<script type="text/javascript">
$('textarea#comment_content').watermark('<?php echo sfConfig::get('app_comment_default'); ?>');
$('form#new-comment').validate(commentRules);
</script>