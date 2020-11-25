<?php foreach ($userPenalty as $value): ?>
<?php
  switch ($value['action']):
    case LOG_PENALTYUP:
?>
<li><strong><span class="text-danger">[페널티추가]</span> <?=!empty($value['subject']) ? $value['subject'] . ' - ' : ''?><?=number_format($value['point'])?> 페널티 추가</strong>
<?php
      break;
    case LOG_PENALTYDN:
?>
<li><strong><span class="text-primary">[페널티감소]</span> <?=!empty($value['subject']) ? $value['subject'] . ' - ' : ''?><?=number_format($value['point'])?> 페널티 감소</strong>
<?php
      break;
  endswitch;
?>
  <small>일시 : <?=date('Y-m-d, H:i:s', $value['regdate'])?></small></li>
<?php endforeach; ?>