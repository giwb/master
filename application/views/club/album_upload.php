<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-header">사진 등록</div>
        <div class="sub-content">
          <form id="formAlbum" method="post" action="<?=base_url()?>club/album_update" enctype="multipart/form-data">
            <input type="hidden" name="page" value="album">
            <input type="hidden" name="idx" value="<?=!empty($viewAlbum['idx']) ? $viewAlbum['idx'] : ''?>">
            <div class="row align-items-center mt-2">
              <div class="col-sm-2 font-weight-bold">사진첩 제목</div>
              <div class="col-sm-10"><input type="text" name="subject" class="form-control" value="<?=!empty($viewAlbum['subject']) ? $viewAlbum['subject'] : ''?>"></div>
            </div>
            <div class="row align-items-center mt-2">
              <div class="col-sm-2 font-weight-bold">내용</div>
              <div class="col-sm-10"><textarea rows="10" name="content" class="form-control"><?=!empty($viewAlbum['content']) ? $viewAlbum['content'] : ''?></textarea></div>
            </div>
            <div class="row align-items-center mt-2">
              <div class="col-sm-2 font-weight-bold">사진</div>
              <div class="col-sm-10">
                <input type="hidden" name="photos" value="<?php foreach ($photos as $value): ?><?=$value['filename']?>,<?php endforeach; ?>"><input type="file" class="photo d-none"><button type="button" class="btn btn-secondary btn-upload-photo">사진 선택</button>
                <div class="added-files"><?php foreach ($photos as $value): ?><img src="<?=base_url()?><?=PHOTO_URL?><?=$value['filename']?>" class="btn-photo-modal" data-photo="<?=$value['filename']?>"><?php endforeach; ?></div>
              </div>
            </div>
            <div class="border-top mt-2 pt-4 text-center">
              <button type="button" class="btn btn-primary btn-album-update"><?=empty($viewAlbum['idx']) ? '등록합니다' : '수정합니다'?></button>
              <?=!empty($viewAlbum['idx']) ? '<button type="button" class="btn btn-danger btn-album-delete-modal ml-3">삭제합니다</button>' : ''?>
            </div>
          </form>
        </div>
      </div>

      <script type="text/javascript">
        $(document).on('change', '.photo', function() {
          // 사진 업로드
          var $dom = $(this);
          var baseUrl = $('input[name=baseUrl]').val();
          var formData = new FormData($('form')[0]);
          var maxSize = 20480000;
          var size = $dom[0].files[0].size;

          if (size > maxSize) {
            $dom.val('');
            $('#messageModal .modal-message').text('파일의 용량은 20MB를 넘을 수 없습니다.');
            $('#messageModal').modal('show');
            return;
          }

          // 사진 형태 추가
          formData.append('file_obj', $dom[0].files[0]);

          $.ajax({
            url: baseUrl + 'club/upload',
            processData: false,
            contentType: false,
            data: formData,
            dataType: 'json',
            type: 'post',
            beforeSend: function() {
              $('.btn-upload-photo').css('opacity', '0.5').prop('disabled', true).text('업로드중.....');
              $dom.val('');
            },
            success: function(result) {
              $('.btn-upload-photo').css('opacity', '1').prop('disabled', false).text('사진 선택');
              if (result.error == 1) {
                $.openMsgModel(result.message);
              } else {
                var $domFiles = $('input[name=photos]');
                $('.added-files').append('<img src="' + result.message + '" class="btn-photo-modal" data-photo="' + result.filename + '">');
                if ($domFiles.val() == '') {
                  $domFiles.val(result.filename);
                } else {
                  $domFiles.val($domFiles.val() + ',' + result.filename);
                }
              }
            }
          });
        }).on('click', '.btn-upload-photo', function() {
          // 사진 업로드 클릭
          $(this).prev().click();
        }).on('click', '.btn-album-update', function() {
          // 등록/수정
          $(this).css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요......');
          if ($('input[name=subject]').val() == '') {
            $.openMsgModal('제목은 꼭 입력해주세요.');
            return false;
          }
          if ($('textarea[name=content]').val() == '') {
            $.openMsgModal('내용은 꼭 입력해주세요.');
            return false;
          }
          if ($('input[name=photos]').val() == '') {
            $.openMsgModal('사진은 꼭 선택해주세요.');
            return false;
          }
          $('#formAlbum').submit();
        }).on('click', '.btn-album-delete-modal', function() {
          $('#albumDeleteModal').modal({backdrop: 'static', keyboard: false});
        }).on('click', '.btn-album-delete', function() {
          // 삭제
          var $btn = $(this);
          var baseUrl = $('input[name=baseUrl]').val();
          $.ajax({
            url: baseUrl + 'club/album_delete',
            data: 'idx=' + $('input[name=idx]').val(),
            dataType: 'json',
            type: 'post',
            beforeSend: function() {
              $btn.css('opacity', '0.5').prop('disabled', true).text('삭제중.......');
            },
            success: function(result) {
              $btn.css('opacity', '1').prop('disabled', false).text('삭제합니다');
              $('#albumDeleteModal .modal-message').text(result.message);
              if (result.error != 1) {
                $('#albumDeleteModal .close').hide();
                $('#albumDeleteModal .btn-album-delete, #albumDeleteModal .btn-close').hide();
                $('#albumDeleteModal .btn-album-list').removeClass('d-none');
              }
            }
          });
        });
      </script>

      <!-- Album Delete Modal -->
      <div class="modal fade" id="albumDeleteModal" tabindex="-1" role="dialog" aria-labelledby="albumDeleteModalLabel" aria-hidden="true">
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
              <a href="<?=base_url()?>club/album/<?=$view['idx']?>"><button type="button" class="btn btn-primary btn-album-list d-none">목록으로</button></a>
              <button type="button" class="btn btn-primary btn-album-delete">삭제합니다</button>
              <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
            </div>
          </div>
        </div>
      </div>
