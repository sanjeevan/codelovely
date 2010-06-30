<?php slot('side'); ?>
<div id="side">

  <ul>
    <li><?php echo link_to('projects', '/projects'); ?></li>
    <li><?php echo link_to('favourites', '/favourites'); ?></li>
    <li><?php echo link_to('friends', '/friends'); ?></li>
    <li><?php echo link_to('messages', '/messages')?></li>
  </ul>

</div>

<?php end_slot(); ?>