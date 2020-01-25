<?php foreach ($userVisit as $value): ?>
<dl>
  <dd>
    <?=viewStatus($value['notice_status'])?> <a href="<?=BASE_URL?>/reserve/?n=<?=$value['resCode']?>"><?=$value['subject']?></a> - <?=checkDirection($value['seat'], $value['bus'], $value['notice_bustype'], $value['notice_bus'])?>번 좌석<br>
    <small>
      일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 
      요금 : <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원
    </small>
  </dd>
</dl>
<?php endforeach; ?>