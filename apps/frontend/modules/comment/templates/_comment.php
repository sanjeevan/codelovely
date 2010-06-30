<?php $t = $comment['Thing']; ?>
<?php $u = $comment['User']; ?>
<?php $score = $t['score']; ?>

<?php $left_margin = $comment['reply_depth'] * 40;?>

<div id="comment-box-<?php echo $comment['id']; ?>" class="commentbox" style="margin-left: <?php echo $left_margin; ?>px;">
  
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
      <a href="#" title="Login" class="thickbox"><?php echo image_tag('up.png', array('id' => 'link-up-' . $t['id'])) ?></a>
      <a href="#" title="Login" class="thickbox"><?php echo image_tag('down.png', array('id' => 'link-down-' . $t['id'])) ?></a>
    <?php endif; ?>
  </div>
  
  <div class="comment-avatar">
    <img class="photo" src="<?php echo url_for('profile/avatar?userid=' . $u['id']); ?>" />
  </div>
  
  <div class="commentwrap">
    <div class="commentheader">
      <?php echo link_to($u['username'], 'profile/' . $u['username'], array('class' => 'user')); ?>
      <?php if ($u['is_admin'] == 1): ?>
      <span style="color:red;">[A]</span>
      <?php endif; ?>
      <?php if ($score > 0): ?>
        <span class="date">
          <?php if ($score === 1): ?>
            <span id="link-score-<?php echo $t['id']; ?>"><?php echo $t['score']; ?></span> point
          <?php else: ?>
            <span id="link-score-<?php echo $t['id']; ?>"><?php echo $t['score']; ?></span> points
          <?php endif; ?>
        </span>
      <?php endif; ?>
      
      <span class="date"><?php echo time_ago_in_words(strtotime($comment['created_at'])); ?></span>
    </div>
    
    <div id="comment-message-<?php echo $comment['id']; ?>" class="message">
      <?php echo $comment['content_html']; ?> 
    </div>
        
    <div class="commentfooter" id="commentfooter-<?php echo $comment['id'];?>">
      <?php if ($sf_user->isAuthenticated()): ?>
        <span class="pillbox pillbox-red"><a href="#" onclick="return false;">report</a></span>
        <span class="pillbox pillbox-green"><a href="#" onclick="reply(<?php echo $comment['id']; ?>); return false;">reply</a></span>
        <?php if ($comment['user_id'] == $sf_user->getId()): ?>
          <span class="pillbox pillbox-orange"><a href="#" onclick="editComment(<?php echo $comment['id']; ?>); return false;">edit</a></span>
        <?php endif;?>
        <?php if ($sf_user->isAdmin()): ?>
          <span class="pillbox pillbox-red"><?php echo link_to('delete', 'comment/delete?commentid=' . $comment['id'], array('confirm' => 'Are you sure you want to delete this comment?')); ?></span>
        <?php endif; ?>
      <?php endif;?>  
    </div>
    
    <?php include_partial('comment/add_reply', array('comment' => $comment)); ?>
        
  </div>
  
  <div style="clear: both;"></div>
  
  

</div>
