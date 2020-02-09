<?php foreach ($userReserveCancel as $value): ?>
<dl>
  <dd>
    <?=viewStatus($value['notice_status'])?> <a href="<?=BASE_URL?>/reserve/<?=$value['resCode']?>"><?=$value['subject']?></a><br>
    <small>
      취소일시 : <?=date('Y-m-d', $value['regdate'])?> (<?=calcWeek(date('Y-m-d', $value['regdate']))?>) <?=date('H:i', $value['regdate'])?>
    </small>
  </dd>
</dl>
<?php endforeach; ?>