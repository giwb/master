<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>현지영상</title>
  <link href="/public/css/tripkorea.css" rel="stylesheet">
  <script type="text/javascript" src="/public/js/jquery-3.3.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</head>
<body>

  <video id="video" class="video-js" autoplay controls></video>

  <script>
    $(document).ready(function() {
      var hls = new Hls();
      var video = document.getElementById('video');
      hls.loadSource('<?=$_GET['link']?>');
      hls.attachMedia(video);
      hls.on(Hls.Events.MANIFEST_PARSED,function() {
        video.muted = true;
        video.play();
      });
    });
  </script>

</body>
</html>