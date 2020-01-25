<?php
foreach ($listReply as $value):
  if (!empty($userData['idx']) && ($userData['idx'] == $value['created_by'] || $userData['admin'] == 1)) {
    $delete = ' <a href="javascript:;" class="btn-reply-update" data-idx="' . $value['idx'] . '">[수정]</a> <a href="javascript:;" class="btn-post-delete-modal" data-idx="' . $value['idx'] . '" data-action="delete_reply">[삭제]</a>';
  } else {
    $delete = '';
  }
  if (!empty($value['updated_at'])) {
    $date = calcStoryTime($value['created_at']) . ' 작성, ' . calcStoryTime($value['updated_at']) . ' 수정';
  } else {
    $date = calcStoryTime($value['created_at']);
  }
  if (file_exists(PHOTO_PATH . $value['created_by'])) {
    $size = getImageSize(PHOTO_PATH . $value['created_by']);
    $value['photo'] = base_url() . 'public/photos/' . $value['created_by'];
    $value['photo_width'] = $size[0];
    $value['photo_height'] = $size[1];
  } else {
    $value['photo'] = base_url() . 'public/images/user.png';
    $value['photo_width'] = 64;
    $value['photo_height'] = 64;
  }
?>
<dl class="story-reply-item" data-idx="<?=$value['idx']?>"><dt><img class="img-profile photo-zoom" src="<?=$value['photo']?>" data-filename="<?=$value['photo']?>" data-width="<?=$value['photo_width']?>" data-height="<?=$value['photo_height']?>"></dt><dd><strong><?=$value['nickname']?></strong> · <span class="reply-date"><?=$date . $delete?></span><div class="reply-content" data-idx="<?=$value['idx']?>"><?=$value['content']?></div></dd></dl>
<?php endforeach; ?>