<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">진행중 산행 목록</h1>
        </div>

        <table class="table">
          <tbody>
<?php
  foreach ($list as $value) {
?>
            <tr>
              <td>
                <b>[<?=viewStatus($value['status'])?>]</b> <a href="<?=base_url()?>admin/main_view_progress/<?=$value['idx']?>"><?=$value['subject']?></a><br>
                <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost'])?>원 / 예약인원 <?=cntRes($value['idx'])?>명
              </td>
              <td align="right">
                <a href="<?=base_url()?>admin/main_entry/<?=$value['idx']?>"><button type="button" class="btn btn-primary">수정</button></a>
                <button type="button" class="btn btn-primary btn-seat">승차</button>
                <button type="button" class="btn btn-primary btn-adjust">정산</button>
              </td>
            </tr>
<?php
  }
?>
          </tbody>
        </table>
      </div>
    </div>
