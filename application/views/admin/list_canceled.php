<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <div id="right-panel" class="right-panel">
    <div class="breadcrumbs">
      <div class="col-sm-4">
        <div class="page-header float-left">
          <div class="page-title">
              <h1>취소된 산행 목록</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <table class="table">
          <tbody>
<?php
  foreach ($list as $value) {
?>
            <tr>
              <td>
                <b>[<?=viewStatus($value['status'])?>]</b> <a href="#"><?=$value['subject']?></a><br>
                <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost'])?>원 / 예약인원 <?=cntRes($value['idx'])?>명
              </td>
              <td align="right">
                <button>수정</button>
                <button>승차</button>
                <button>정산</button>
              </td>
            </tr>
<?php
  }
?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
