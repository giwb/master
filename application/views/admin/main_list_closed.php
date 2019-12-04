<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">다녀온 산행 목록</h1>
        </div>

        <table class="table">
          <tbody>
<?php
  foreach ($list as $value) {
?>
            <tr>
              <td>
                <b>[<?=viewStatus($value['status'])?>]</b> <a href="#"><?=$value['subject']?></a><br>
                산행일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 산행분담금 : <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원
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
