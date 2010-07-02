<h1>Get on the waiting list</h1>

<p>To create an account on <?php echo sfConfig::get('app_name')?> you need an invite code as we're currently in beta. Use the form below to get on our waiting list.</p>
<p>Invites are sent out every week</p> 

<form method="post" action="">
  <ul>
    <?php echo $form; ?>
    <li>
      <button type="submit"><?php echo image_tag('blacks/16x16/checkmark.png'); ?> Apply</button>
    </li>
  </ul>
</form>