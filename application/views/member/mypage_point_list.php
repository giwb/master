<?php foreach ($userPoint as $value): ?>
<?php
  switch ($value['action']):
    case LOG_POINTUP:
?>
<li><strong><span class="text-primary">[포인트추가]</span> <?=!empty($value['subject']) ? $value['subject'] . ' - ' : ''?><?=number_format($value['point'])?> 포인트 추가</strong>
<?php
      break;
    case LOG_POINTDN:
?>
<li><strong><span class="text-danger">[포인트감소]</span> <?=!empty($value['subject']) ? $value['subject'] . ' - ' : ''?><?=number_format($value['point'])?> 포인트 감소</strong>
<?php
      break;
  endswitch;
?>
  <small>일시 : <?=date('Y-m-d, H:i:s', $value['regdate'])?></small></li>
<?php endforeach; ?>