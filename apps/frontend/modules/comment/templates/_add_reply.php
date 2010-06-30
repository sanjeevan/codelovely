
<form id="reply-<?php echo $comment['id']; ?>" method="post" action="<?php echo url_for("comment/addReply"); ?>" style="display: none;">
  <input type="hidden" name="articleid" value="<?php echo $comment['article_id'];?>" />
  <input type="hidden" name="replyid" value="<?php echo $comment['id'];?>" />
  <ul>
    <li><textarea style="width: 100%" rows="4" name="comment[content]" id="comment_reply_content_<?php echo $comment['id'];?>"></textarea></li>
    <li><button type="submit"><?php echo image_tag('blacks/16x16/round_checkmark.png'); ?> Add reply</button></li>
  </ul>
</form>
<script type="text/javascript">$("form#reply-<?php echo $comment['id'];?>").validate(commentRules);</script>  
