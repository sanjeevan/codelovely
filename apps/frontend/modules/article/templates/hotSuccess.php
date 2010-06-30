<?php use_helper('Date', 'Global', 'Text'); ?>

<?php $class_link   = $flavour == 'link' ? 'active' : ''; ?>
<?php $class_code   = $flavour == 'code' ? 'active' : ''; ?>
<?php $class_snap   = $flavour == 'snapshot' ? 'active' : ''; ?>
<?php $class_quest  = $flavour == 'question' ? 'active' : ''; ?>

<h1 style="float: left;">Popular</h1>
<ul class="listing-flavour-filter">
  <li><?php echo link_to('Links', '@hot_flav?flavour=link', array('class' => $class_link)); ?></li>
  <li><?php echo link_to('Code', '@hot_flav?flavour=code', array('class' => $class_code)); ?></li>
  <li><?php echo link_to('Snapshots', '@hot_flav?flavour=snapshot', array('class' => $class_snap)); ?></li>
  <li><?php echo link_to('Questions', '@hot_flav?flavour=question', array('class' => $class_quest)); ?></li>
</ul>

<div class="clear"></div>

<?php $pos = $pager->getFirstIndice(); ?>
<?php foreach ($pager->getResults() as $row): ?>
  <?php include_partial('article/article', array('article' => $row->toArray(), 'pos' => $pos, 'a' => $row)); ?>
  <?php $pos++; ?>
<?php endforeach;?>

<?php 
  $page_route = $flavour === null ? url_for('@hot') : url_for('@hot_flav?flavour=' . $flavour); 
?>

<?php echo render_pagination($pager, $page_route); ?>

<?php if ($has_code): ?>
<script type="text/javascript">
 $(document).ready(function(){
   SyntaxHighlighter.defaults['collapse'] = true;
   SyntaxHighlighter.all();
 });
</script>
<?php endif; ?>