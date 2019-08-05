<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <section id="subpage">
    <table class="table">
      <tbody>
<?php
  foreach ($listNotice as $value) {
?>
        <tr>
          <td>
            <b>[<?=viewStatus($value['status'])?>]</b> <a href="<?=base_url()?>reservation/view/<?=$value['idx']?>"><?=$value['subject']?></a><br>
            <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost'])?>원 / 예약인원 <?=cntRes($value['idx'])?>명
          </td>
          <td align="right">
            <button type="button" class="btn btn-notice">공지</button>
            <button type="button" class="btn btn-seat">좌석</button>
          </td>
        </tr>
<?php
  }
?>
      </tbody>
    </table>
  </section>
