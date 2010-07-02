<div id="invite-only">
  <span>
    <?php echo sfConfig::get('app_name'); ?> is currently invite only, if you want an account then <?php echo link_to('get on our waiting list', 'invite/request')?>
  </span>
  <span style="float: right;"><a href="#" onclick="$.get('<?php echo url_for('invite/hidePromo'); ?>'); $('div#invite-only').hide(); return false; ">close</a></span> 
  
</div>
