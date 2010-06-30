<?php slot('login'); ?>
<form method="post" action="<?php echo url_for('user/login');?>">
  <table class="form" width="50px">
    <tr>
      <td>
        <input class="text" type="text" name="user[username]" />    
      </td>
    </tr>  
    <tr>
      <td>
        <input class="text" type="password" name="user[password]" />    
      </td>
    </tr>
    <tr>
      <td>
        <button type="submit"><?php echo image_tag('icons/accept.png')?> Login</button>
        <?php echo link_to('Sign up', 'user/signUp'); ?>    
      </td>
    </tr>
  </table>
</form>
<?php end_slot(); ?>