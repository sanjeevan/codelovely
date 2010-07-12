<div id="invite-only">
  <span>
    If you want to post new links, code, snapshots or ask a questions then <?php echo link_to('grab an invite here', 'invite/request'); ?>      
  </span>
  <span style="float: right;"><a href="#" onclick="$.get('<?php echo url_for('invite/hidePromo'); ?>'); $('div#invite-only').hide(); return false; ">close</a></span> 
  
</div>
