<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <div class="area-page mt-4">
            <h4>■ 첫 페이지 사진 관리</h4><hr>
            <div class="text-center">
              <form id="formTopImage" method="post" action="<?=BASE_URL?>/admin/setup_topimage_upload" enctype="multipart/form-data" class="row no-gutters align-items-center">
                <div class="col-7 text-right"><input type="file" name="file" class="border p-2"></div>
                <div class="col-5 text-left"><button type="buton" class="btn btn-default btn-submit-topimage">사진 올리기</button></div>
              </form>
              <div class="text-danger mt-2">※ 대표사진은 1900 x 650 사이즈로 올려주세요.</div>
            </div><hr>
            <div id="sortable">
              <?php if (!empty($arrTopImage)): foreach ($arrTopImage as $value): ?>
              <div class="mb-3 pl-5 pr-5"><img class="topimages btn-delete-topimage-modal w-100" data-filename="<?=$value?>" src="<?=FRONT_URL . $value?>"></div>
              <?php endforeach; endif; ?>
            </div>
          </div>
        </div>

        <div class="modal fade" id="deleteTopImageModal" tabindex="-1" role="dialog" aria-labelledby="deleteTopImageModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">메세지</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-center">
                <p class="modal-message mb-4">이 대표사진을 삭제하시겠습니까?</p>
              </div>
              <div class="modal-footer">
                <form id="formTopImageDelete" method="post" action="<?=BASE_URL?>/admin/setup_topimage_delete">
                  <input type="hidden" name="filename" value="">
                  <button type="button" class="btn btn-default btn-delete-topimage">삭제합니다</button>
                  <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <script type="text/javascript">
          $(document).on('click', '.btn-submit-topimage', function() {
            if ($('input[name=file]').val() == '') {
              return false;
            }
            $(this).css('opacity', '0.5').prop('disabled', true).text('업로드중..');
            $('#formTopImage').submit();
          }).on('click', '.btn-delete-topimage-modal', function() {
            $('#deleteTopImageModal input[name=filename]').val($(this).data('filename'));
            $('#deleteTopImageModal').modal('show');
          }).on('click', '.btn-delete-topimage', function() {
            $(this).css('opacity', '0.5').prop('disabled', true).text('삭제중..');
            $('#formTopImageDelete').submit();
          });

          $(function() {
            $('#sortable').disableSelection().sortable({
              stop: function(event, ui) {
                var arrSort = new Array();

                $('.topimages').each(function() {
                  arrSort.push($(this).data('filename'));
                })

                $.ajax({
                  url: '<?=BASE_URL?>/admin/setup_topimage_move',
                  data: 'topimages=' + arrSort,
                  dataType: 'json',
                  type: 'post',
                  success: function() {}
                });
              }
            });
          });
        </script>
