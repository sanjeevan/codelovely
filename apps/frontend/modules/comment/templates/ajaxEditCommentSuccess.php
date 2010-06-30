<form id="comment-edit-<?php echo $comment['id']; ?>" method="post" action="<?php echo url_for("comment/ajaxEditComment"); ?>" onsubmit="editCommentSave(<?php echo $comment['id']; ?>); return false;">
  <input type="hidden" name="commentid" value="<?php echo $comment['id'];?>" />
  <ul>
    <li><textarea style="width: 100%" rows="4" cols="30" name="comment[content]"><?php echo $comment['content']; ?></textarea></li>
    <li>
      <button type="submit"><?php echo image_tag('blacks/16x16/round_checkmark.png'); ?> Update</button>
      <a href="#" onclick="cancelEditComment(<?php echo $comment['id']; ?>); return false;">Cancel</a>
    </li>
  </ul>
</form>
<script type="text/javascript">$("form#comment-edit-<?php echo $comment['id'];?>").validate(commentRules);</script>  
