<table class="view-message">
  <tr>
    <td colspan="3">
      <div>This conversation is between: <?php echo render_recipients($msg); ?></div>
    </td>
  </tr>
  <tr>
    <td class="image"><img class="photo" src="<?php echo url_for('profile/avatar?userid=' . $msg->getFromId()); ?>" /></td>
    <td>
      from <span class="user"><?php echo link_to($msg->getFromUser()->getUsername(), "@show_profile?username={$msg->getFromUser()->getUsername()}"); ?></span>
      <span class="sent">sent <?php echo time_ago_in_words($msg->getDateTimeObject('created_at')->format('U')); ?> ago</span>
      <span class="pillbox pillbox-green"><a href="#" onclick="$('div#message-reply-wrap, div#message-content').toggle(); return false;">Reply</a></span>
      <div id="message-reply-wrap" style="display: none;">
        <form class="message-reply" method="post" action="<?php echo url_for('message/send'); ?>">
          <ul>
            <?php echo $form; ?>
            <li>
              <button type="submit"><?php echo image_tag('blacks/16x16/checkmark.png'); ?> Send reply</button>
            </li>
          </ul>
        </form> 
      </div>
      <div id="message-content">
        <?php echo myUtil::markdown($msg->getMessage()); ?>
      </div>
    </td>
    <td>
    </td>
  </tr>
</table>