<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<html>
<head>
  <title><?=$view['title']?> - 공지 보기</title>
  <style type="text/css">
    .sub-notice-header { font-weight: bold; background-color: #d9d9d9; border: 1px solid #999; border-width: 1px 0; margin: 0px; padding: 7px 15px; }
    .sub-content { font-size: 14px; text-align: justify; overflow: hidden; }
    .sub-content img { max-width: 100%; cursor: pointer; }
  </style>
</head>
<body>

<?php foreach ($listNoticeDetail as $value): ?>
<div class="sub-notice-header"><?=$value['title']?></div>
<div class="sub-content"><?=$value['content']?></div><br>
<?php endforeach; ?>

</body>
</html>
