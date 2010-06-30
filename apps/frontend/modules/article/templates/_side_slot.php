<?php slot('side'); ?>

  <div class="pillbox" id="article-side">
  
    <div class="points"><?php echo $article->getThing()->getScore(); ?> points</div>
    
    <div class="ups"><?php echo $article->getThing()->getUps(); ?> up votes</div>
    <div class="downs"><?php echo $article->getThing()->getDowns(); ?> down votes</div>
        
    <br/>
    
    <div>
      @ <?php echo $domain; ?>
    </div>
    
    <div>
      <span>added by <?php echo link_to($article->getUsername(), 'profile/' . $article->getUsername()); ?>, <?php echo time_ago_in_words($article->getDateTimeObject('created_at')->format('U')); ?> ago</span>
    </div>
    
    <div>
      <script type="text/javascript">
      $(document).ready(function(){
        var data = [];
        <?php foreach ($vote_stats as $vote_stat):?>
        data.push(<?php echo $vote_stat['freq']; ?>);
        <?php endforeach; ?>
        $('span#vote-stats-spark').sparkline(data, {type: 'bar'});
      });
      </script>
      <span id="vote-stats-spark"></span>
    </div>
    
    
  </div>

<?php end_slot(); ?>
