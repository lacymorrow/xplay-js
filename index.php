<?php session_start(); $_SESSION['page'] = 3;  ?>
<!DOCTYPE html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->

<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8"><!-- Set the viewport width to device width for mobile -->
    <meta name="viewport" content="width=device-width">

    <title>xPlayJS</title><!-- Included CSS Files (Uncompressed) -->
    <!--
  <link rel="stylesheet" href="stylesheets/foundation.css">
  -->
    <!-- Included CSS Files (Compressed) -->
    <link rel="stylesheet" href="stylesheets/foundation.min.css" type="text/css">
    <link rel="stylesheet" href="stylesheets/app.css" type="text/css">
    <link rel="stylesheet" href="javascripts/me/mediaelementplayer.min.css" type="text/css">
    <script src="javascripts/modernizr.foundation.js" type="text/javascript">
</script>
</head>

<body>
    <?php include('../javascripts/menu.php'); ?>

    <div class="row">
        <div class="twelve columns">
            <h1>xPlay.js</h1>

            <h4 class="subheader">The drop&ndash;and&ndash;go html5 media player &mdash; <span class="red">beta</span></h4>
            <hr>

            <p>xPlay.js is the ultimate tool in playing multiple media files on a website. Scan an entire directory and generate a playlist, complete with album art, from your audio and video. Playlist is completely customizable via CSS and controlled via JS.</p>

            <h4 class="green">Video support is now available. Who would have guessed? It works on iDevices too!</h4>

            <p>To begin, click a playlist item below: <span class="subheader right">by <a href="http://www.lacymorrow.com/">Lacy Morrow</a></span></p>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="two columns">
            <h4 class="bottom-border">Playlist</h4>

            <ul class="" id="playjs-playlist"></ul>
        </div>

        <div class="five columns right">
            <div id="playjs-image"></div>

            <div id="playjs-player"></div>
        </div>

        <div class="five columns">
            <div id="wrapper-info" class="bottom-border">
                <h2 id="playjs-title">Title</h2>

                <h4 id="playjs-album">Album</h4>

                <h3 id="playjs-creator">Creator</h3>
            </div>

            <h5>Duration - <span id="playjs-duration">0:00</span></h5>

            <h5>Comments:</h5>

            <h5 id="playjs-annotation"></h5>

            <h5 id="playjs-info"></h5>
        </div>
    </div>

    <div class="row">
        <div class="twelve columns">
            <hr>

            <p class="tiny text-right right">Animation by <a href="http://www.youtube.com/watch?v=Jn88Aun2SxA&feature=related" target="_blank">Felix Tsao</a></p>

            <h4>What&rsquo;s going on behind the scenes:</h4>
        </div>
    </div>

    <div class="row">
        <div class="six columns"><img src="images/hierarchy.jpg" alt=""></div>

        <div class="six columns">
            <ul class="list square">
                <li>A media directory is scanned recursively using a PHP script and file associations are made to connect video sources, and artwork for audio tracks.<br></li>

                <li>An XSPF playlist file is generated and saved (for cacheing purposes). Files can be named using the directory structure or using built&ndash;in ID3 tag information.</li>

                <li>A jQuery script is then generated which outputs playlist and track information elements to the page. Customize the elements as you please, all of the track handling is taken care of!<br>
                The open&ndash;source media player <a href="http://www.mediaelementjs.com/" target="_blank">Media&ndash;Element.js</a> is included by default.</li>
            </ul><br>
            <a href="xplayjs.zip" class="large button">Download Source&nbsp;&raquo;</a><br>

            <p class="tiny text-right">Created by <a href="http://www.lacymorrow.com/">Lacy Morrow</a></p>
        </div>
    </div><!-- Included JS Files (Uncompressed) -->
    <!--
  <script src="javascripts/jquery.js"></script><script src="javascripts/jquery.foundation.mediaQueryToggle.js"></script><script src="javascripts/jquery.foundation.forms.js"></script><script src="javascripts/jquery.foundation.reveal.js"></script><script src="javascripts/jquery.foundation.orbit.js"></script><script src="javascripts/jquery.foundation.navigation.js"></script><script src="javascripts/jquery.foundation.buttons.js"></script><script src="javascripts/jquery.foundation.tabs.js"></script><script src="javascripts/jquery.foundation.tooltips.js"></script><script src="javascripts/jquery.foundation.accordion.js"></script><script src="javascripts/jquery.placeholder.js"></script><script src="javascripts/jquery.foundation.alerts.js"></script><script src="javascripts/jquery.foundation.topbar.js"></script><script src="javascripts/jquery.foundation.joyride.js"></script><script src="javascripts/jquery.foundation.clearing.js"></script><script src="javascripts/jquery.foundation.magellan.js"></script>
  -->
    <!-- Included JS Files (Compressed) -->
    <script src="javascripts/jquery.js" type="text/javascript">
</script><script src="javascripts/foundation.min.js" type="text/javascript">
</script><!-- Initialize JS Plugins -->
    <script src="javascripts/app.js" type="text/javascript">
</script><script src="javascripts/me/mediaelement-and-player.min.js" type="text/javascript">
</script><script src="jukebox.php" type="text/javascript">
</script><script type="text/javascript">
  var _gaq=[['_setAccount','UA-247410-8'],['_trackPageview']];
      (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
      g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
      s.parentNode.insertBefore(g,s)}(document,'script'));
    </script>
</body>
</html>
