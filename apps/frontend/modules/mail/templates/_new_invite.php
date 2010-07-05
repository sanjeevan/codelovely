Hi <?php echo $to; ?>,

You have just been sent and invitation by <?php echo $user->getFirstname() . ' ' . $user->getLastname() ?> to join <?php echo sfConfig::get('app_name'); ?>. 

<?php echo sfConfig::get('app_name'); ?> is a community for web developers and designers. You can share links, useful code snippets, ask questions or post snapshots of projects you are working on.

To get started, sign up here:

<?php echo url_for('user/signUp?invite=' . $invite->getCode(), true); ?>
 
 
Best,
The <?php echo sfConfig::get('app_name'); ?> Team
 
 
This email was intended for <?php echo $to; ?>
