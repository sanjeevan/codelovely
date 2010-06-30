<h1>Send message</h1>

<?php include_partial('message/mail_menu', array('msg' => $message)); ?>

<div class="setting-header"></div>

<form class="message" id="message" method="post" action="#">
<ul>
  <?php echo $form; ?>
  <li>
    <button type="submit"><?php echo image_tag('blacks/16x16/checkmark.png'); ?> Send</button>
  </li>
</ul>
</form>

<script type="text/javascript">
$().ready(function(){
  $('form#message').validate(messageRules);
});
</script>