<?php $t = $article['Thing']; ?>
<div class="linkitem item-flavour-<?php echo $article['flavour'] ?>" id="link-item-<?php echo $article['id']; ?>">

  <?php if (isset($nopos)): ?>
    <span class="itempos">&nbsp;</span>
  <?php else: ?>
    <span class="itempos"><?php echo $pos; ?></span>
  <?php endif; ?>

  <div class="votebtn">
    <?php if ($sf_user->isAuthenticated()):?>
      <?php $vote = @$t['Vote'][0]; ?>
      <?php if (is_array($vote)): ?>
        <?php if ($vote['type'] == 'up'): ?>
            <a href="#" onclick="voteUp(this, <?php echo $t['id']; ?>); return false;">
              <?php echo image_tag('mod_up.png', array('id' => 'link-up-' . $t['id'])) ?>
            </a>
        <?php else: ?>
            <a href="#" onclick="voteUp(this, <?php echo $t['id']; ?>); return false;">
              <?php echo image_tag('up.png', array('id' => 'link-up-' . $t['id'])) ?>
            </a>
        <?php endif; ?>
        <?php if ($vote['type'] == 'down'): ?>
            <a href="#" onclick="voteDown(this, <?php echo $t['id']; ?>); return false;">
              <?php echo image_tag('mod_down.png', array('id' => 'link-down-' . $t['id'])) ?>
            </a>
        <?php else: ?>
            <a href="#" onclick="voteDown(this, <?php echo $t['id']; ?>); return false;">
              <?php echo image_tag('down.png', array('id' => 'link-down-' . $t['id'])) ?>
            </a>
        <?php endif; ?>
      <?php else: ?>
        <a href="#" onclick="voteUp(this, <?php echo $t['id']; ?>); return false;"><?php echo image_tag('up.png', array('id' => 'link-up-' . $t['id'])) ?></a>
        <a href="#" onclick="voteDown(this, <?php echo $t['id']; ?>); return false;"><?php echo image_tag('down.png', array('id' => 'link-down-' . $t['id'])) ?></a>
      <?php endif; ?>
    <?php else: ?>
      <a href="#" onclick="return false;"><?php echo image_tag('up.png', array('id' => 'link-up-' . $t['id'])) ?></a>
      <a href="#" onclick="return false;"><?php echo image_tag('down.png', array('id' => 'link-down-' . $t['id'])) ?></a>
    <?php endif; ?>
  </div>

  <div class="item">
    <?php if ($a->getFlavour() == 'link'): ?>
      <p><?php echo link_to($a->getTitle(), $a->getUrl(), array('class' => 'name', 'target' => '_blank')); ?></p>
      <p class="url"><?php echo $a->getUrl(); ?></p>
    <?php else: ?>
      <p><?php echo link_to_article($article, array('class' => 'name')); ?></p>
    <?php endif;?>
    
    <p class="link_footer">
      <span class="flavour-<?php echo $a['flavour']; ?>"><?php echo $a['flavour']; ?></span>
      <?php $score = $t['score']; ?>
      <?php if ($score > 0): ?>
        <?php if ($score === 1): ?>
          <span id="link-score-<?php echo $t['id']; ?>"><?php echo $t['score']; ?></span> point
        <?php else: ?>
          <span id="link-score-<?php echo $t['id']; ?>"><?php echo $t['score']; ?></span> points
        <?php endif; ?>
      <?php endif; ?>
            
      posted <?php echo time_ago_in_words(strtotime($article['created_at'])) ?> ago
      by <span class="link_author"><?php echo link_to($article['username'], 'profile/' . $article['username']); ?></span>
      <?php $comment_count = $article['total_comments']; ?>
      <?php if ($comment_count == 1): ?>
        <a class="ctrl" href="<?php echo url_for($a->getViewUrl()); ?>">1 comment</a>
      <?php else: ?>
        <a class="ctrl" href="<?php echo url_for($a->getViewUrl()); ?>"><?php echo $comment_count; ?> comments</a>
      <?php endif; ?>
      <?php if ($sf_user->isAuthenticated() && $sf_user->isAdmin()): ?>
        <?php echo link_to('delete', 'article/delete?articleid=' . $article['id'], array('class' => 'ctrl admin', 'confirm' => 'Are you sure you want to delet this?')); ?>
      <?php endif; ?>
    </p>

    <?php if ($a->getFlavour() == 'snapshot'): ?>
      <?php $snapshot = $a->getSnapshot(true); ?>
      <a title="<?php echo $a->getTitle(); ?>" rel="lightbox" target="_blank" href="<?php echo $snapshot->getUrl(); ?>"><img class="snapshot" src="<?php echo $snapshot->getThumbnailUrl(200); ?>" /></a>
      <p class="link_summary"><?php echo truncate_text($article['summary'], 200); ?></p>
      <div class="clear"></div>
    <?php endif; ?>

    <?php if ($a->getFlavour() == 'link'): ?>
      <p class="link_summary"><?php echo truncate_text($article['summary'], 200); ?></p>
      <?php if ($a->getHasThumbnails()): ?>
        <?php $ftas = $a->getFiles(true); ?>
        <?php if (count($ftas) > 0): ?>
          <div class="preview">
            <?php foreach ($ftas as $fta): ?>
              <img class="preview-image" src="<?php echo $fta->getFile()->getThumbnailUrl(); ?>" />
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    <?php endif; ?>
    
    <?php if ($a->getFlavour() == 'code'): ?>
<pre class="brush: <?php echo $a->getBrushAlias(); ?>"><?php echo htmlspecialchars($a->getCode()); ?></pre>
    <?php endif; ?>
    
    <?php if ($a->getFlavour() == 'question'): ?>
      <div class="question-body"><?php echo myUtil::markdown($a->getQuestion()); ?></div>
    <?php endif; ?>
  
  </div>
  <div style="clear:both;"></div>
</div>
