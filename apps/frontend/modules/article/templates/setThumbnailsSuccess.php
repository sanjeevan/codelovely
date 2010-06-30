<style type="text/css">
  .select-thumbnail {
    float: left;
    width: 110px;
    margin: 5px 5px 5px 0;
    height: 110px;
    background: #ececec;
    padding: 2px;
  }

  .select-thumbnail .thumbnail {
    margin: 0 auto 0 auto;
    height: 85px;
    width: 85px;
  }

  .select-thumbnail input[type="checkbox"] {
    display: block;
    height: 16px;
    width: 16px;
    margin: 0 auto 0 auto;
  }
}
</style>

<h1><?php echo link_to($article->getTitle(), $article->getUrl(), array('target' => '_blank')); ?></h1>


<div class="summary">
  <?php if (strlen($article->getSummary()) > 0): ?>
    <?php echo $article->getSummary(); ?>
  <?php endif; ?>
</div>

<form method="post" action="">
  <div>
    <?php foreach ($files_to_article as $fta): ?>
      <div class="select-thumbnail">
        <div class="thumbnail"><img src="<?php echo url_for('file/thumbnail?fileid=' . $fta->getFileId()); ?>" title="<?php echo $fta->getFile()->getFilename(); ?>" /></div>
        <input type="checkbox" name="thumbnail[]" value="<?php echo $fta->getFileId(); ?>" />
      </div>
    <?php endforeach; ?>
    <br clear="all" />
    <button type="submit"><?php echo image_tag('blacks/16x16/checkmark.png'); ?> Use these thumbnails</button>
  </div>
</form>