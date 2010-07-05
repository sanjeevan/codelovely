<?php 
  if ($sf_user->getAttribute('hasNewMail', false, 'mail') === true)
    $mail_icon = image_tag('mail_new.png');
  else
    $mail_icon = image_tag('email.png');
?>

<?php echo link_to(image_tag('logo.png', array('id' => 'logo')), '@homepage'); ?>
  
<?php if ($sf_user->isAuthenticated()): ?>
  <div id="account-controls">
    <span><?php echo image_tag('white/16x16/round_plus.png'); ?> <?php echo link_to('Add', '@add'); ?></span>,
    <?php if ($sf_user->isAdmin()): ?>
      <span><?php echo link_to('Add blog post', 'blog/new'); ?></span>,
      <span><?php echo link_to('Invite requests', 'invite/listRequests'); ?>
    <?php endif; ?>
    <span class="welcome">Welcome back <span class="username"><?php echo link_to($sf_user->getModel()->getUsername(), '@show_profile?username=' . $sf_user->getModel()->getUsername()); ?></span></span>
    <?php echo link_to('settings', '/user/settings'); ?>
    <span class="img-a"><?php echo link_to($mail_icon, '/message/inbox'); ?></span>
    <?php echo link_to('logout', '/user/logout'); ?>
  </div>
<?php else: ?>
  <div id="account-controls">
    <span><?php echo link_to('sign up', 'user/signUp'); ?></span>
    <span><?php echo link_to('login', 'user/login'); ?></span>
  </div>
<?php endif;?>

<div style="clear: both;"></div>
