Hi <?php echo $to; ?>,

Welcome to <?php echo sfConfig::get('app_name')?>!

You account has been created successfully and you can login at <?php echo url_for('user/login', true); ?>

Your username is <?php echo $user->getUsername(); ?>.


Best,
The <?php echo sfConfig::get('app_name'); ?> Team


This email was intended for <?php echo $to; ?>

