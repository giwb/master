<?php foreach ($listMembers as $value): $level = memberLevel($value['rescount'], $value['penalty'], $value['level'], $value['admin']); ?>
<dl>
  <dt>[<?=$value['idx']?>] <a href="<?=BASE_URL?>/admin/member_view/<?=$value['idx']?>" class="<?=$value['gender'] == 'M' ? 'text-primary' : 'text-danger'?>"><?=$value['realname']?> / <?=$value['nickname']?> / <?=$value['userid']?> / <?=$level['levelName']?> (<?=number_format($value['rescount'] - $value['penalty'])?>점)</a></dt>
  <dd><?=$value['phone']?>, <?=$value['birthday']?> <?=$value['birthday_type'] == '1' ? '(양력)' : '(음력)' ?><?=!empty($value['location']) ? ', ' . arrLocation(NULL, $value['location']) : ''?><br>등록일 : <?=date('Y-m-d, H:i:s', $value['regdate'])?>, <?=!empty($value['lastdate']) ? '최종접속일 : ' . date('Y-m-d, H:i:s', $value['lastdate']) . ', ' : ''?>접속횟수 : <?=$value['connect']?></dd>
</dl>
<?php endforeach; ?>