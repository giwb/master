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
    $value['photo'] = PHOTO_URL . $value['created_by'];
    $value['photo_width'] = $size[0];
    $value['photo_height'] = $size[1];
  } else {
    $value['photo'] = '/public/images/user.png';
    $value['photo_width'] = 64;
    $value['photo_height'] = 64;
  }
  if (!empty($value['parent_idx'])) {
    $responseClass = ' response'; 
    $reply = '';
  } else {
    $responseClass = '';
    $value['parent_idx'] = $value['idx'];
    $reply = ' <a href="javascript:;" class="btn-reply-response" data-idx="' . $value['idx'] . '">[답글]</a>';
  } 
?>
<dl class="story-reply-item<?=$responseClass?>" data-idx="<?=$value['idx']?>" data-parent="<?=$value['parent_idx']?>"><dt><img class="reply-response" src="/public/images/reply.png"><img class="img-profile photo-zoom" src="<?=$value['photo']?>" data-filename="<?=$value['photo']?>" data-width="<?=$value['photo_width']?>" data-height="<?=$value['photo_height']?>"></dt><dd><strong class="nickname"><?=$value['nickname']?></strong> · <span class="reply-date"><?=$date . $reply . $delete?></span><div class="reply-content" data-idx="<?=$value['idx']?>"><?=$value['content']?></div></dd></dl>
<?php endforeach; ?>