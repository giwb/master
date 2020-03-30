<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  $uri = $_SERVER['REQUEST_URI'];
?>

<div class="row align-items-center text-center mb-4 view-header">
  <a class="col<?=strstr($uri, 'main_entry') ? ' active' : ''?>" href="<?=BASE_URL?>/admin/main_entry/<?=$view['idx']?>">수정</a>
  <a class="col<?=strstr($uri, 'main_notice') ? ' active' : ''?>" href="<?=BASE_URL?>/admin/main_notice/<?=$view['idx']?>">공지</a>
  <a class="col<?=strstr($uri, 'main_view_progress') ? ' active' : ''?>" href="<?=BASE_URL?>/admin/main_view_progress/<?=$view['idx']?>">예약</a>
  <a class="col<?=strstr($uri, 'main_view_boarding') ? ' active' : ''?>" href="<?=BASE_URL?>/admin/main_view_boarding/<?=$view['idx']?>">승차</a>
  <a class="col<?=strstr($uri, 'main_view_sms') ? ' active' : ''?>" href="<?=BASE_URL?>/admin/main_view_sms/<?=$view['idx']?>">문자</a>
  <a class="col<?=strstr($uri, 'main_view_adjust') ? ' active' : ''?>" href="<?=BASE_URL?>/admin/main_view_adjust/<?=$view['idx']?>">정산</a>
</div>