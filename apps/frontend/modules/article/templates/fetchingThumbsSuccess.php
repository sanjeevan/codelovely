<h1><?php echo $article->getTitle(); ?></h1>

<div class="summary">
  <?php if (strlen($article->getSummary()) > 0): ?>
    <?php echo $article->getSummary(); ?>
  <?php else: ?>
    <p><i>No summary for this link</i></p>
  <?php endif; ?>
</div>

<div class="margin5-top">&nbsp;</div>

<p><?php echo image_tag('loadingAnimation.gif'); ?></p>

<p>Please wait while we fetch some thumbnails..., don't close your tab just yet</p>


<script type="text/javascript">
$(document).ready(function(){
  $.PeriodicalUpdater({
    url: '<?php echo url_for('job/getJobStatus'); ?>',
    method: 'get',
    sendData: {'jobid': '<?php echo $job['id'] ?>'},
    minTimeout: 2000,
    type: 'json'
  },
  function(data){
    if (data.status == 'finished'){
      window.location.href = "<?php echo url_for("article/setThumbnails?articleid=" . $article->getId() . '&jobid=' . $job['id']); ?>";
    }

    var debug = false;
    var expire = data.expire * 1000;
    var now = Date.now();

    // failure to get thumbnails, so don't put them in
    if ((now > expire || data.status == 'fail') && !debug){
      window.location.href = "<?php echo url_for("article/setThumbnails?articleid=" . $article->getId() . '&jobid=' . $job['id'] . '&timeout=1'); ?>";
    }
  });
});
</script>