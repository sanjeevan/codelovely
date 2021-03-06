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
      $(document).ready(function(){
        $('a[rel*=lightbox]').lightBox();
      });
    </script>
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-570069-19']);
      _gaq.push(['_trackPageview']);
    
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
  </head>
  <body>
    <div id="header">
      <?php include_partial('global/top'); ?>
      <?php include_partial('global/nav'); ?>
    </div>
    
    <div class="container">

      <?php if (!$sf_user->isAuthenticated() && $sf_user->getAttribute('showpromo', true, 'promo') && sfConfig::get('app_signup_invite')): ?>
        <?php include_partial('invite/promo'); ?>
      <?php endif; ?>

      <?php if ($sf_user->hasFlash('notice') || $sf_user->hasFlash('error')): ?>
      <div class="columns-12">
        <?php include_partial('global/flash'); ?>       
      </div>
      <?php endif; ?>
      
      <div id="content-main">
        <?php echo $sf_content ?>
      </div>
      
    </div>
    
    <?php include_partial('global/footer'); ?>
    
  </body>
</html>