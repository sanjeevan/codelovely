<?php use_helper('Date', 'Text'); ?>

<div class="profile">
  <img class="photo" src="<?php echo url_for('profile/avatar?userid=' . $user->getId()); ?>" />
  <h1><?php echo $user->getFirstname() . ' ' . $user->getLastname(); ?> <em>(<?php echo $user->getUsername(); ?>)</em></h1>
  <ul>
    <li>
      Joined <?php echo time_ago_in_words($user->getDateTimeObject('created_at')->format('U')); ?> ago
      <span class="sep">|</span>
      posted <?php echo $total_articles; ?> items
      <?php if ($sf_user->isAuthenticated()): ?>
        <span class="sep">|</span>
        <?php echo image_tag('blacks/16x16/mail.png'); ?> <?php echo link_to('Send message', 'message/send?to=' . $user->getUsername()); ?>
      <?php endif; ?>
    </li>
    <li>
      <?php echo url_for('@show_profile?username=' . $user->getUsername(), true); ?>
    </li>
  </ul>
  <div class="clear"></div>
</div>

<?php $class_link   = $flavour == 'link' ? 'active' : ''; ?>
<?php $class_code   = $flavour == 'code' ? 'active' : ''; ?>
<?php $class_snap   = $flavour == 'snapshot' ? 'active' : ''; ?>
<?php $class_quest  = $flavour == 'question' ? 'active' : ''; ?>

<ul class="flavour-tabs">
  <li><?php echo link_to('Links', "@show_profile_flav?username={$user->getUsername()}&flavour=link", array('class' => $class_link)); ?></li>
  <li><?php echo link_to('Code', "@show_profile_flav?username={$user->getUsername()}&flavour=code", array('class' => $class_code)); ?></li>
  <li><?php echo link_to('Snapshots', "@show_profile_flav?username={$user->getUsername()}&flavour=snapshot", array('class' => $class_snap)); ?></li>
  <li><?php echo link_to('Questions', "@show_profile_flav?username={$user->getUsername()}&flavour=question", array('class' => $class_quest)); ?></li>
</ul>
<div class="clear"></div>
<hr noshade="noshade" />

<?php $pos = $pager->getFirstIndice(); ?>
<?php foreach ($pager->getResults() as $article): ?>
  <?php include_partial('article/article', array('article' => $article->toArray(), 'pos' => $pos, 'a' => $article)); ?>
  <?php $pos++; ?>
<?php endforeach; ?>

<?php echo render_pagination($pager, url_for("@show_profile_flav?username={$user->getUsername()}&flavour={$flavour}")); ?>

<?php if ($has_code): ?>
<script type="text/javascript">
 $(document).ready(function(){
   SyntaxHighlighter.defaults['collapse'] = true;
   SyntaxHighlighter.all();
 });
</script>
<?php endif; ?>