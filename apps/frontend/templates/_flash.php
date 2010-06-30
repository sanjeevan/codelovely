<?php if ($sf_user->hasFlash('notice')): ?>
  <div class="flash-message flash-notice">
    <?php echo $sf_user->getFlash('notice') ?>
  </div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('error')): ?>
  <div class="flash-message flash-error">
    <?php echo $sf_user->getFlash('error') ?>
  </div>
<?php endif; ?>