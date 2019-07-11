<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <div id="right-panel" class="right-panel">
    <div class="breadcrumbs">
      <div class="col-sm-4">
        <div class="page-header float-left">
          <div class="page-title">
              <h1>다녀온 산행 목록</h1>
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
                산행일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 산행분담금 : <?=number_format($value['cost'])?>원
              </td>
              <td align="right">
                0원<br><?=cntRes($value['idx'])?>명
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
