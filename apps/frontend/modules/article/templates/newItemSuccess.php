<h1>Post something new</h1>

<div id="add-selection">
  I want to add <a onclick="showForm($(this).attr('name')); return false;" href="#" name="link">a link</a>,
  a <a onclick="showForm($(this).attr('name')); return false;" name="code" href="#">code snippet</a>,
  a <a onclick="showForm($(this).attr('name')); return false;" name="snapshot" href="#">snapshot</a> of my work
  or <a onclick="showForm($(this).attr('name')); return false;" name="question" href="#">ask a question</a>
</div>

<form method="post" action="#question">
  <ul class="article-form" id="question" style="display: none">
    <?php echo $forms['question']; ?>
    <li>
      <button type="submit"><?php echo image_tag('blacks/16x16/checkmark.png'); ?>Ask question</button>
    </li>
  </ul>
</form>

<form method="post" action="#link">
  <ul class="article-form" id="link" style="display: none">
    <?php echo $forms['link']['title']->renderRow(); ?>
    <?php echo $forms['link']['url']->renderRow(); ?>
    <li><a href="#" class="toggle-optional" data-toggle="link-summary">Add a short description</a></li>
    <li id="link-summary" style="display:none">
      <?php echo $forms['link']['summary']->renderError(); ?>
      <?php echo $forms['link']['summary']->renderLabel(); ?>
      <?php echo $forms['link']['summary']->render(); ?>
      <?php echo $forms['link']['summary']->renderHelp(); ?>
    </li>
    <?php echo $forms['link']['images']->renderRow(); ?>
    <li>
      <?php echo $forms['link']['type']->render(); ?>
      <?php echo $forms['link']['_csrf_token']->render(); ?>
      <button type="submit"><?php echo image_tag('blacks/16x16/checkmark.png'); ?> Add link</button>
    </li>
  </ul>
</form>

<form method="post" action="#snapshot" enctype="multipart/form-data">
  <ul class="article-form" id="snapshot" style="display: none">
    <?php echo $forms['snapshot']['title']->renderRow(); ?>
    <?php echo $forms['snapshot']['snapshot']->renderRow(); ?>
    <li><a href="#" class="toggle-optional" data-toggle="snapshot-summary">Add a short description</a></li>
    <li id="snapshot-summary" style="display:none">
      <?php echo $forms['snapshot']['summary']->renderError(); ?>
      <?php echo $forms['snapshot']['summary']->renderLabel(); ?>
      <?php echo $forms['snapshot']['summary']->render(); ?>
      <?php echo $forms['snapshot']['summary']->renderHelp(); ?>
    </li>
    <li>
      <?php echo $forms['snapshot']['type']->render(); ?>
      <?php echo $forms['snapshot']['_csrf_token']->render(); ?>
      <button type="submit"><?php echo image_tag('blacks/16x16/checkmark.png'); ?> Add snapshot</button>
    </li>
  </ul>
</form>

<form method="post" action="#code">
  <ul class="article-form" id="code" style="display: none">
    <?php echo $forms['code']['title']->renderRow(); ?>
    <?php echo $forms['code']['code']->renderRow(); ?>

    <li><a href="#" class="toggle-optional" data-toggle="code-summary">Add a short description</a></li>
    <li id="code-summary" style="display:none">
      <?php echo $forms['code']['summary']->renderError(); ?>
      <?php echo $forms['code']['summary']->renderLabel(); ?>
      <?php echo $forms['code']['summary']->render(); ?>
      <?php echo $forms['code']['summary']->renderHelp(); ?>
    </li>
    <?php echo $forms['code']['language']->renderRow(); ?>
    <li>
      <?php echo $forms['code']['type']->render(); ?>
      <?php echo $forms['code']['_csrf_token']->render(); ?>
      <button type="submit"><?php echo image_tag('blacks/16x16/checkmark.png'); ?> Add code snippet</button>
    </li>
  </ul>
</form>

<script type="text/javascript">
  function showForm(name){
    var forms = ['link', 'code', 'snapshot', 'question'];
    $(forms).each(function(idx){
      var formId = forms[idx];
      if (formId == name){
        $('#' + formId).show();
      } else {
        $('#' + formId).hide();
      }
    });
  };
  
  $(document).ready(function(){
    var hash = window.location.hash;
    if (hash){
      showForm(hash.substr(1));
    }
    $('a.toggle-optional').click(function(e){
      var panelId = $(this).attr('data-toggle');
      $('#' + panelId).toggle();
      $(this).parent().hide();
      return false;
    })
  });
</script>