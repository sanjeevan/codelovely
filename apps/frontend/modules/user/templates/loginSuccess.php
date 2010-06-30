<h1>Sign into your account</h1>

<form method="post" action="">
  <ul>
    <?php echo $form; ?>
    <li>
      <button type="submit"><?php echo image_tag('blacks/16x16/key.png'); ?> Login</button>
      <?php echo link_to('Forgot password?', 'user/forgotPassword'); ?>
    </li>
  </ul>
</form>
