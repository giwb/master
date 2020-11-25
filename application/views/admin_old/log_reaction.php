<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">좋아요/공유 기록</h1>
        </div>
      </div>

      <div class="story-reply">
      <?php
        foreach ($listReaction as $value):
          if ($value['reaction_type'] == REACTION_TYPE_STORY) $value['reaction_type'] = '<a target="_blank" href="/story/view/' . $value['club_idx'] . '?n=' . $value['story_idx'] . '">이야기</a>';
          elseif ($value['reaction_type'] == REACTION_TYPE_NOTICE) $value['reaction_type'] = '<a target="_blank" href="/reserve/notice/' . $value['club_idx'] . '?n=' . $value['story_idx'] . '">공지</a>';

          if ($value['reaction_kind'] == REACTION_KIND_LIKE) $value['reaction_kind'] = '좋아요';
          elseif ($value['reaction_kind'] == REACTION_KIND_SHARE) {
            if ($value['share_type'] == SHARE_TYPE_URL) $value['reaction_kind'] = 'URL 공유';
            elseif ($value['share_type'] == SHARE_TYPE_FACEBOOK) $value['reaction_kind'] = '페이스북 공유';
            elseif ($value['share_type'] == SHARE_TYPE_TWITTER) $value['reaction_kind'] = '트위터 공유';
          }

          if (file_exists(PHOTO_PATH . $value['created_by'])) $value['photo'] = PHOTO_URL . $value['created_by'];
          else $value['photo'] = '/public/images/user.png';
      ?>
        <div class="mb-2">
          <img class="img-profile" src="<?=$value['photo']?>"> <?=$value['nickname']?>님이
          <?=calcStoryTime($value['created_at'])?>
          <?=$value['reaction_type']?>에
          <?=$value['reaction_kind']?>를 하셨습니다.
          | <a href="javascript:;" data-by="<?=$value['created_by']?>" data-at="<?=$value['created_at']?>" class="btn-reaction-delete-modal">삭제</a>
        </div>
      <?php endforeach; ?>
      </div>
    </div>

    <div class="modal fade" id="deleteReactionModal" tabindex="-1" role="dialog" aria-labelledby="deleteReactionModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="smallmodalLabel">메세지</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">
            <p class="modal-message">정말로 삭제하시겠습니까?</p>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="created_by">
            <input type="hidden" name="created_at">
            <button type="button" class="btn btn-primary btn-reaction-delete">삭제합니다</button>
            <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      $(document).on('click', '.btn-reaction-delete-modal', function() {
        $('input[name=created_by]').val($(this).data('by'));
        $('input[name=created_at]').val($(this).data('at'));
        $('#deleteReactionModal').modal();
      }).on('click', '.btn-reaction-delete', function() {
        // 삭제
        var $btn = $(this);
        $.ajax({
          url: '/admin_old/log_reaction_delete',
          data: 'created_by=' + $('input[name=created_by]').val() + '&created_at=' + $('input[name=created_at]').val(),
          dataType: 'json',
          type: 'post',
          beforeSend: function() {
            $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
          },
          success: function() {
            location.reload();
          }
        });
      });
    </script>

