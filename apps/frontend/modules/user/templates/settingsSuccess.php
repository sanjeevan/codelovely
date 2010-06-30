<h1 style="float: left;">Settings</h1>

<ul class="listing-flavour-filter">
  <li><?php echo link_to('Invites', 'user/invites', array('class' => '')); ?></li>
</ul>
<div class="clear"></div>

<script type="text/javascript">
<!--
  function showSetting(id){
    $('div#setting-info-' + id).toggle();
    $('div#setting-form-' + id).toggle();
  }

  $(document).ready(function(){
    if (window.location.hash){
      showSetting(window.location.hash.substr(1));
    }
  });

//-->
</script>

<div id="personal-settings" class="setting-group">
  
  <!-- personal information -->
  
  <div class="setting-header">
    <div class="setting-label">Personal</div>
    <div class="change-setting"><a href="#personal" onclick="showSetting('personal');">change</a></div>
  </div>
  <div class="clear"></div>
  <div class="setting-info" id="setting-info-personal">Change your personal information</div>
  
  <div class="setting-form-container" style="display: none;" id="setting-form-personal">
    <form method="post" action="#personal">
      <ul>
        <?php echo $personal_form; ?>
        <li><button type="submit"><?php echo image_tag('blacks/16x16/checkmark.png'); ?> Save</button></li>
      </ul>
    </form>
  </div>
  <div style="margin-bottom: 20px;"></div>
  
  <!-- security settings -->
  
  <div class="setting-header">
    <div class="setting-label">Email / Password</div>
    <div class="change-setting"><a href="#security" onclick="showSetting('security');">change</a></div>
  </div>  
  <div class="clear"></div>
  <div class="setting-info" id="setting-info-security">Change your password or email</div>
  
  <div class="setting-form-container" style="display: none;" id="setting-form-security">
    <form method="post" action="#security">
      <ul>
        <?php echo $security_form; ?>
        
        <li><button type="submit"><?php echo image_tag('blacks/16x16/checkmark.png'); ?> Save</button></li>
      </ul>
    </form>
  </div>
  <div style="margin-bottom: 20px;"></div>
  
  
  <!-- choose avatar -->
  
  <div class="setting-header">
    <div class="setting-label">Avatar</div>
    <div class="change-setting"><a href="#" onclick="showSetting('avatar'); return false;">change</a></div>
  </div>  
  <div style="clear:both;"></div>
  <div class="setting-info" id="setting-info-avatar">Change you profile picture</div>
  
  <div class="setting-form-container" style="display: none;" id="setting-form-avatar">
    <form method="post" action="<?php echo url_for('user/saveAvatar'); ?>#avatar" enctype="multipart/form-data">
      <?php if ($avatar): ?>
        <img style="display: block; margin-bottom: 5px;" src="<?php echo url_for('file/thumbnail?fileid=' . $avatar->getFileId()); ?>" />
      <?php endif; ?>
      <p>Select a file from your computer to upload:</p>
      <input type="file" name="avatar" />
      <br/>
      <br/>
      
      <button type="submit"><?php echo image_tag('blacks/16x16/checkmark.png'); ?> Save</button>
    </form>
  </div>
  
</div>


