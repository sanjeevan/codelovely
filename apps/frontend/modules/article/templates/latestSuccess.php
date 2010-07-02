<?php use_helper('Date', 'Global', 'Text'); ?>

<?php $class_link   = $flavour == 'link' ? 'active' : ''; ?>
<?php $class_code   = $flavour == 'code' ? 'active' : ''; ?>
<?php $class_snap   = $flavour == 'snapshot' ? 'active' : ''; ?>
<?php $class_quest  = $flavour == 'question' ? 'active' : ''; ?>

<h1 style="float: left;">Latest</h1>
<ul class="listing-flavour-filter">
  <li><?php echo link_to('Links', '@latest_flav?flavour=link', array('class' => $class_link)); ?></li>
  <li><?php echo link_to('Code', '@latest_flav?flavour=code', array('class' => $class_code)); ?></li>
  <li><?php echo link_to('Snapshots', '@latest_flav?flavour=snapshot', array('class' => $class_snap)); ?></li>
  <li><?php echo link_to('Questions', '@latest_flav?flavour=question', array('class' => $class_quest)); ?></li>
</ul>

<div class="clear"></div>

<?php $pos = $pager->getFirstIndice(); ?>
<?php foreach ($pager->getResults() as $article): ?>
  <?php include_partial('article/article', array('pos' => $pos, 'a' => $article)); ?>
  <?php $pos++; ?>
<?php endforeach;?>

<?php $page_route = $flavour === null ? url_for('@latest') : url_for('@latest_flav?flavour=' . $flavour); ?>
<?php echo render_pagination($pager, $page_route); ?>

<?php if ($has_code): ?>
<script type="text/javascript">
 $(document).ready(function(){
   SyntaxHighlighter.defaults['collapse'] = true;
   SyntaxHighlighter.all();
 });
</script>
<?php endif; ?>
