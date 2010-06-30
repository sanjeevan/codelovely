
<form method="post" action="">
  <ul>
    <?php echo $form; ?>
    <li>
      <button type="submit"><?php echo image_tag('icons/save.png'); ?> Save</button>
    </li>
  </ul>
</form>

<?php include_partial('blog_form_js');