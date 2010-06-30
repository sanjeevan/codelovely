<table class="view-message">
  <tr>
    <td colspan="3">
      <div>Sent to: <?php echo render_sent_to($msg); ?></div>
    </td>
  </tr>
  <tr>
    <td class="image"><img class="photo" src="<?php echo url_for('profile/avatar?userid=' . $msg->getUserId()); ?>" /></td>
    <td>
      <span class="user"><?php echo $msg->getUser()->getUsername(); ?></span>
      <span class="sent">sent <?php echo time_ago_in_words($msg->getDateTimeObject('created_at')->format('U')); ?> ago</span>
      <br/>
      <p><?php echo $msg->getMessage(); ?></p>
    </td>
    <td></td>
  </tr>
</table>