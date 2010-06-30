<?php use_helper('Global', 'Date'); ?>
<h1>Inbox</h1>

<?php include_partial('message/mail_menu', array('msg' => $message)); ?>

<div class="setting-header"></div>

<table class="std" width="100%" id="message-list">
  <?php foreach ($messages as $msg): ?>
    <?php $class = 'messages'; if ($msg['is_read'] == 0) $class .= ', unread'; ?>
    <tr class="<?php echo $class; ?>" id="<?php echo $msg['id']; ?>">
      <td class="chk"><input type="checkbox" name="message[]" value="<?php echo $msg['id']; ?>" /></td>
      <td class="to-from"><b><?php echo $msg->getFromUser()->getUsername(); ?></b></td>
      <td class="title"><?php echo render_msg_title($msg); ?></td>
      <td class="ago"><?php echo time_ago_in_words($msg->getDateTimeObject('created_at')->format('U')); ?> ago</td>
    </tr>
  <?php endforeach; ?>
</table>

<script type="text/javascript">
$().ready(function(){
  $('table#message-list tr').each(function(e){
    $(this).click(function(e){
      e.preventDefault();
      window.location.href = '<?php echo url_for('message/show?type=inbox'); ?>?messageid=' + $(this).attr('id');
    });
  });
});
</script>