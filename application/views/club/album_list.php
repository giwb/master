<div class="row">
  <?php foreach ($listAlbumMain as $key => $value): ?>
  <div class="col-6 col-sm-3 p-2 text-center album-item"><a href="javascript:;" class="btn-album" data-idx="<?=$value['idx']?>"><img class="album-photo border mb-2" src="<?=$value['photo']?>"></a><br><?=$value['subject']?><br><span class="small"><?=$value['nickname']?>님 | <?=calcStoryTime($value['created_at'])?><?=$value['created_by'] == $userIdx || !empty($adminCheck) ? ' | <a href="' . BASE_URL . '/album/entry/?n=' . $value['idx'] .'">수정</a>' : ''?></span></div>
  <?php endforeach; ?>
</div>