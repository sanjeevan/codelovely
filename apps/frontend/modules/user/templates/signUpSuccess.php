<h1>Create a new account</h1>

<p><?php echo sfConfig::get('app_name'); ?> is currently invite only. You'll need an invite code to sign up,
if you don't have an invite then get on the <?php echo link_to('waiting list', 'invite/request'); ?>, we mail out new invites every week</p>

<form method="post" action="<?php echo url_for('user/signUp'); ?>">
  <ul>
    <?php echo $form; ?>
    <li>
      <button type="submit"><?php echo image_tag('blacks/16x16/checkmark.png'); ?> Sign up</button>
    </li>
  </ul>
</form>