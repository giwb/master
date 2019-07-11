<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <div id="right-panel" class="right-panel">
    <div class="breadcrumbs">
      <div class="col-sm-4">
        <div class="page-header float-left">
          <div class="page-title">
              <h1>출석체크 보기</h1>
          </div>
        </div>
      </div>
      <div class="col-sm-8">
        <div class="float-right">
          <button class="btn-get-attendance">최신 데이터 받기</button>
        </div>
      </div>
    </div>
    <div class="table-check">
      <div class="table-head w30">No.</div>
      <div class="table-head w100">닉네임</div>
      <div class="table-head w30">횟수</div>
<?php
  if (!empty($viewType)) {
?>
      <div class="table-head w88p">산행내역</div>
<?php
  } else {
    foreach ($listAttendanceNotice as $value) {
?>
      <div class="table-head w30" title="<?=$value['mname']?>"><a href="#"><?=$value['mname']?></a></div>
<?php
    }
  }
?>
    </div>
    <div class="scrolling">
<?php
  foreach ($listNickname as $value) {
?>
      <div class="table-check">
        <div class="table-body w30"><?=$value['rank']?></div>
        <div class="table-body w100"><a href="#"><?=$value['nickname']?>님</a></div>
        <div class="table-body w30"><?=$value['cnt']?></div>
<?php
    foreach ($value['listNotice'] as $notice) {
?>
        <div class="table-body w30"><?=!empty($notice['idx']) ? 1 : '&nbsp;'?></div>
<?
    }
?>
      </div>
<?php
  }
?>
    </div>
  </div>
