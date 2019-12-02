<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <script type="text/javascript" src="<?=base_url()?>public/js/clipboard.min.js"></script>
      <script type="text/javascript" src="<?=base_url()?>public/js/story.js"></script>
      <script type="text/javascript">(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>

      <div id="fb-root"></div>
      <div class="club-main">
        <div class="your-story">
          <div class="story-article">
          </div>
          <input type="hidden" name="p" value="1">
          <input type="hidden" name="n" value="<?=!empty($storyIdx) ? $storyIdx : ''?>">
        </div>
      </div>
