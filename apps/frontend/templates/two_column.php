<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <script type="text/javascript" charset="utf-8">
      $(function() {
      });
    </script>
  </head>
  <body>
        
    <div id="header">
      <?php include_partial('global/top'); ?>
      <?php include_partial('global/nav'); ?>
    </div>
    
    <div class="container">
             
      <div class="columns-12">
        <?php include_partial('global/flash'); ?>       
      </div>
      
      <div class="columns-8">
        <?php echo $sf_content ?>
      </div>
      
      <div class="columns-4">
        <?php include_slot('side'); ?>
      </div>
      
    </div>
    
    <?php include_partial('global/footer'); ?>
    
  </body>
</html>