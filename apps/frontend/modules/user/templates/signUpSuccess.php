<h1>Create a new account</h1>

<form method="post" action="<?php echo url_for('user/signUp'); ?>">
  <ul>
    <?php echo $form; ?>
    <li>
      <button type="submit"><?php echo image_tag('blacks/16x16/checkmark.png'); ?> Sign up</button>
    </li>
  </ul>
</form>