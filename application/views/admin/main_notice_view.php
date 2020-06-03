<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<html>
<head>
  <title><?=$view['title']?> - 공지 보기</title>
  <link href="/public/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <style type="text/css">
    article { width: 648px; }
    .notice-title { font-size: 24px; font-family: "Malgun Gothic"; font-weight: bold; margin: 0px; padding: 0px; float: left; }
    .notice-button { float: right; }
    .sub-notice-header { font-weight: bold; background-color: #d9d9d9; border: 1px solid #999; border-width: 1px 0; margin: 0px; padding: 7px 15px; }
    .sub-content { font-size: 14px; text-align: justify; overflow: hidden; }
    .sub-content img { max-width: 100%; cursor: pointer; }
    .btn-default { color: #fff; background-color: #FF6C00; }
    .btn-default:hover { color: #fff; background-color: #FF842A; }
  </style>
</head>
<body>

<article>
  <h2 class="notice-title"><?=$notice['subject']?></h2></td>
  <div class="notice-button text-right pb-3">
    <?=!empty($notice['weather']) ? '<a target="_blank" href="' . $notice['weather'] . '"><button type="button" class="btn btn-sm btn-primary mr-2">날씨</button></a>' : ''?>
    <a target="_blank" href="<?=BASE_URL?>/reserve/list/<?=$notice['idx']?>"><button type="button" class="btn btn-sm btn-default btn-notice">좌석</button></a>
  </div>

  <?php foreach ($listNoticeDetail as $value): ?>
  <div class="sub-notice-header"><?=$value['title']?></div>
  <div class="sub-content"><?=$value['content']?></div><br>
  <?php endforeach; ?>
</article>

</body>
</html>
