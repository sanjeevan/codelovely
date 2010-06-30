<h1 style="float: left;">Settings</h1>

<ul class="listing-flavour-filter">
  <li><?php echo link_to('Invites', 'user/invites', array('class' => 'active')); ?></li>
</ul>
<div class="clear"></div>

<p>You currently have <?php echo $total_invites; ?> invites remaining</p>

<h2>Send an invite</h2>

<form method="post">
  <ul>
    <?php echo $form; ?>
    <li>
      <button type="submit"><?php echo image_tag('blacks/16x16/checkmark.png'); ?> Send invite</button>
    </li>
  </ul>
</form>

