<?php if (count($comments) > 0): ?>
  <div id="comments" style="margin: 10px 0 10px 0; padding: 5px;">
    <?php $start_time = microtime(); ?>
    <?php echo render_comments($comments, $article, $sf_user); ?>
    <div>
      <span style="float: right; font-size: xx-small;">
        <span id="comment-stats-spark"></span>
        <?php echo microtime() - $start_time; ?>
      </span>
      <div style="clear: both;"></div>
    </div>
  </div>
<?php endif;?>