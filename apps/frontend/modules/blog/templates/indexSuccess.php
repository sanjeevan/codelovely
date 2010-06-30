
<?php foreach ($pager->getResults() as $blog_entry): ?>
  <div class="post">
    <h3 class="title"><?php echo link_to($blog_entry->getTitle(), 'post/' . $blog_entry->getSlug()); ?></h3>
    <?php echo $blog_entry->getSummaryHtml(); ?>

    <span class="readmore"><?php echo link_to('Read more', 'post/' . $blog_entry->getSlug()); ?></span>

    <div class="date">Posted on <?php echo $blog_entry->getDateTimeObject('published_at')->format('d F Y'); ?></div>
  </div>
<?php endforeach; ?>