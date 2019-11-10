<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="club-main">
  <div class="memberForm">
    <h2>마이페이지</h2>
    <b><?=$viewMember['nickname']?></b>님은 현재 <?=$viewLevel['levelName']?> 이십니다.<br>
    현재 산행 횟수 <?=$viewLevel['cntNotice']?>회, 예약 횟수 <?=$viewLevel['cntReserve']?>회, 취소 페널티 <?=$viewMember['penalty']?>점, 현재 레벨은 <?=$viewLevel['level']?>점 입니다.

    <h3>■ 예약 내역</h3>
    <h3>■ 산행 내역</h3>
    <h3>■ 포인트 내역</h3>
    <h3>■ 페널티 내역</h3>
  </div>
</div>
