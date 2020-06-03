<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<html>
<head>
  <title><?=$view['title']?> - 공지 보기</title>
  <link href="/public/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <style type="text/css">
    .sub-content img { max-width: 100%; cursor: pointer; }
  </style>
</head>
<body>

<div style="clear:both;">
  <h2><div style="float: left; font-size: 24px; font-family: 'Malgun Gothic'; font-weight: bold; margin: 0px; padding: 0px;"><?=$notice['subject']?></div></h2>
  <a target="_blank" href="<?=BASE_URL?>/reserve/list/<?=$notice['idx']?>"><button type="button" style="float: right; border-radius: 0.2rem; font-family: inherit; font-size: 0.875rem; line-height: 1.5; overflow: visible; color: rgb(255, 255, 255); text-align: center; vertical-align: middle; user-select: none; background-color: rgb(0, 123, 255); border-width: 1px; border-style: solid; border-color: rgb(0, 123, 255); padding: 0.25rem 0.5rem; transition: color 0.15s ease-in-out 0s, background-color 0.15s ease-in-out 0s, border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s; cursor: pointer;">좌석</button></a>
  <?=!empty($notice['weather']) ? '<a target="_blank" href="' . $notice['weather'] . '"><button type="button" style="float: right; border-radius: 0.2rem; font-family: inherit; font-size: 0.875rem; line-height: 1.5; overflow: visible; color: rgb(255, 255, 255); text-align: center; vertical-align: middle; user-select: none; background-color: rgb(255, 108, 0); border-width: 1px; border-style: solid; border-color: transparent; margin: 0 0.5rem 0 0; padding: 0.25rem 0.5rem; transition: color 0.15s ease-in-out 0s, background-color 0.15s ease-in-out 0s, border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s; cursor: pointer;">날씨</button></a>' : ''?>
</div>

<div style="clear:both; padding-top: 20px;">
  <?php foreach ($listNoticeDetail as $value): ?>
  <div style="font-weight: bold; background-color: #d9d9d9; border: 1px solid #999; border-width: 1px 0; margin: 0px; padding: 7px 15px;"><?=$value['title']?></div>
  <div class="sub-content" style="font-size: 14px; text-align: justify; overflow: hidden;"><?=$value['content']?></div><br>
  <?php endforeach; ?>
</div>

</body>
</html>
