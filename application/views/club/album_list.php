<?php foreach ($listAlbumMain as $key => $value): ?>
  <?php if ($key == 0): ?><div class="row"><?php elseif ($key%3 == 0): ?></div><div class="row"><?php endif; ?>
  <div class="col-sm-4 text-center mb-4 album-item"><a href="javascript:;" class="btn-album" data-idx="<?=$value['idx']?>"><img class="album-photo border mb-2" src="<?=$value['photo']?>"></a><br><?=$value['subject']?><br><span class="small"><?=$value['nickname']?>님 | <?=calcStoryTime($value['created_at'])?><?=$value['created_by'] == $userIdx || !empty($adminCheck) ? ' | <a href="' . BASE_URL . '/album/entry/?n=' . $value['idx'] .'">수정</a>' : ''?></span></div>
<?php endforeach; ?>
<?php if (!empty($listAlbumMain)): ?></div><?php endif; ?>