<?php use_helper('Global', 'Date'); ?>
<h1><?php echo $message['title']; ?></h1>

<?php include_partial('message/mail_menu', array('msg' => $message)); ?>

<div class="setting-header"></div>
<?php if ($message instanceof sfMessageInbox): ?>
  <?php include_partial('message/inbox_msg', array('msg' => $message, 'form' => $form))?>
<?php else: ?>
  <?php include_partial('message/outbox_msg', array('msg' => $message))?>
<?php endif;?>

