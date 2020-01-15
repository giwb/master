<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

          <h1 class="h3 mb-5 text-gray-800">신규 상품 등록</h1>

          <form id="formShop" method="post" action="<?=base_url()?>shop/update" enctype="multipart/form-data">
            <input type="hidden" name="idx" value="<?=!empty($view['idx']) ? $view['idx'] : ''?>">
            <div class="row align-items-center mb-3">
              <div class="col-sm-1 font-weight-bold">품명 <span class="required">(*)</span></div>
              <div class="col-sm-11"><input type="text" name="item_name" class="form-control" value="<?=!empty($view['item_name']) ? $view['item_name'] : ''?>"></div>
            </div>
            <div class="row align-items-center mb-3">
              <div class="col-sm-1 font-weight-bold">분류 <span class="required">(*)</span></div>
              <div class="col-sm-11 row align-items-center">
                <div class="col-sm-2">
                  <select name="item_category1" class="form-control item-category">
                    <option value=''></option>
                    <?php if (!empty($listCategory1)): foreach ($listCategory1 as $value): ?>
                    <option<?=!empty($view['item_category_name'][0]) && $view['item_category_name'][0] == $value['idx'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                    <?php endforeach; endif; ?>
                  </select>
                </div>
                <div class="col-sm-2">
                  <select name="item_category2" class="form-control item-category-child">
                    <option value=''></option>
                    <?php if (!empty($listCategory2)): foreach ($listCategory2 as $value): ?>
                    <option<?=!empty($view['item_category_name'][1]) && $view['item_category_name'][1] == $value['idx'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                    <?php endforeach; endif; ?>
                  </select>
                </div>
                <div class="col-sm-1 item-category-loader"></div>
              </div>
            </div>
            <div class="row align-items-top mb-3">
              <div class="col-sm-1 font-weight-bold mt-2">가격 <span class="required">(*)</span></div>
              <div class="col-sm-11">
                <div class="w-100 row align-items-center mt-1">
                  <div class="col col-sm-2">소비자 가격</div>
                  <div class="col col-sm-2">판매 가격</div>
                </div>
                <div class="w-100 row align-items-center mt-1">
                  <div class="col col-sm-2 item-cost"><input type="text" name="item_price" maxlength="8" class="form-control" value="<?=!empty($view['item_price']) ? $view['item_price'] : ''?>"><span class="item-cost-text">원</span></div>
                  <div class="col col-sm-2 item-cost"><input type="text" name="item_cost" maxlength="8" class="form-control" value="<?=!empty($view['item_cost']) ? $view['item_cost'] : ''?>"><span class="item-cost-text">원</span></div>
                </div>
              </div>
            </div>
            <div class="row align-items-top mb-3">
              <div class="col-sm-1 font-weight-bold mt-2">옵션 <span class="required">(*)</span><br><button type="button" class="btn btn-primary btn-add-option">추가</button></div>
              <div class="col-sm-11">
                <div class="w-100 row align-items-center mt-1">
                  <div class="col-sm-2">옵션명</div>
                  <div class="col-sm-2">변동 소비자 가격</div>
                  <div class="col-sm-2">변동 판매 가격</div>
                </div>
                <div id="sortable">
                  <?php if (empty($view['added_option'])): ?>
                  <div class="w-100 row align-items-center mt-1">
                    <div class="col-sm-2"><input type="text" name="item_option[]" class="form-control"></div>
                    <div class="col-sm-2 item-cost"><input type="text" name="added_price[]" maxlength="8" class="form-control"><span class="item-cost-text">원</span></div>
                    <div class="col-sm-2 item-cost"><input type="text" name="added_cost[]" maxlength="8" class="form-control"><span class="item-cost-text">원</span></div>
                  </div>
                  <?php else: foreach ($view['added_option'] as $key => $value): ?>
                  <div class="row align-items-center w-100 mt-1 option-row">
                    <div class="col-sm-2"><input type="text" name="item_option[]" class="form-control" placeholder="옵션명" value="<?=$value?>"></div>
                    <div class="col-sm-2 item-cost"><input type="text" name="added_price[]" maxlength="8" class="form-control" value="<?=array_key_exists($key, $view['added_price']) ? $view['added_price'][$key] : '0'?>"><span class="item-cost-text">원</span></div>
                    <div class="col-sm-2 item-cost"><input type="text" name="added_cost[]" maxlength="8" class="form-control" value="<?=array_key_exists($key, $view['added_cost']) ? $view['added_cost'][$key] : '0'?>"><span class="item-cost-text">원</span></div>
                  </div>
                  <?php endforeach; endif; ?>
                </div>
              </div>
            </div>
            <div class="row align-items-center mb-2">
              <div class="col-sm-1 font-weight-bold">설명</div>
              <div class="col-sm-11"><textarea name="item_content" id="item_content" rows="10" cols="100"><?=!empty($view['item_content']) ? $view['item_content'] : ''?></textarea></div>
            </div>
            <div class="row align-items-center mb-3">
              <div class="col-sm-1 font-weight-bold">대표 사진 <span class="required">(*)</span></div>
              <div class="col-sm-11">
                <input type="file" name="photo" class="file d-none"><button type="button" class="btn btn-sm btn-info btn-upload mt-2">사진올리기</button><input type="hidden" name="filename" value="<?php foreach ($item_photo as $value): ?><?=$value?>,<?php endforeach; ?>">
                <div class="added-files mt-2">
                <?php foreach ($item_photo as $value): ?>
                  <img src="<?=base_url()?><?=PHOTO_URL?><?=$value?>" class="btn-photo-modal" data-photo="<?=$value?>">
                <?php endforeach; ?>
                </div>
              </div>
            </div>
            <div class="row align-items-center mb-2">
              <div class="col-sm-1 font-weight-bold">추천</div>
              <div class="col-sm-11"><label><input type="checkbox" name="item_recommend" value="Y"<?=!empty($view['item_recommend']) && $view['item_recommend'] == 'Y' ? ' checked' : ''?>> 추천 상품으로 등록</label></div>
            </div>
            <div class="text-center mt-5 mb-5 pb-5">
              <button type="button" class="btn btn-primary btn-item-entry"><?=empty($view['idx']) ? '등록합니다' : '수정합니다'?></button>
              <?=!empty($view['idx']) ? '<button type="button" class="btn btn-danger btn-item-delete ml-4">삭제합니다</button>' : ''?>
            </div>
          </form>

        </div>
      </div>
    </div>

    <script type="text/javascript">
      CKEDITOR.replace('item_content');

      $(document).on('change', '.file', function() {
        // 파일 업로드
        var $dom = $(this);
        var baseUrl = $('input[name=base_url]').val();
        var formData = new FormData($('#formShop')[0]);
        var maxSize = 20480000;
        var size = $dom[0].files[0].size;

        if (size > maxSize) {
          $dom.val('');
          $.openMsgModal('파일의 용량은 20MB를 넘을 수 없습니다.');
          return false;
        }

        // 사진 형태 추가
        formData.append('file_obj', $dom[0].files[0]);

        $.ajax({
          url: baseUrl + 'shop/upload',
          processData: false,
          contentType: false,
          data: formData,
          dataType: 'json',
          type: 'post',
          beforeSend: function() {
            $('.btn-upload').css('opacity', '0.5').prop('disabled', true).text('업로드중.....');
            $dom.val('');
          },
          success: function(result) {
            $('.btn-upload').css('opacity', '1').prop('disabled', false).text('사진올리기');
            if (result.error == 1) {
              $.openMsgModal(result.message);
            } else {
              var $domFiles = $('input[name=filename]');
              $('.added-files').append('<img src="' + result.message + '" class="btn-photo-modal" data-photo="' + result.filename + '">');
              if ($domFiles.val() == '') {
                $domFiles.val(result.filename);
              } else {
                $domFiles.val($domFiles.val() + ',' + result.filename);
              }
            }
          }
        });
      }).on('click', '.btn-upload', function() {
        // 사진올리기 버튼 클릭
        $(this).prev().click();
      }).on('click', '.btn-photo-modal', function() {
        // 사진 모달
        $('#photoModal .modal-body img').attr('src', $(this).attr('src'));
        $('#photoModal input[name=delete_filename]').val($(this).data('photo'))
        $('#photoModal').modal('show');
      }).on('click', '.btn-photo-delete', function() {
        // 사진 삭제
        var $btn = $(this);
        var baseUrl = $('input[name=base_url]').val();
        var filename = $('input[name=delete_filename]').val();
        $.ajax({
          url: baseUrl + 'shop/photo_delete',
          data: 'filename=' + filename,
          dataType: 'json',
          type: 'post',
          beforeSend: function() {
            $btn.css('opacity', '0.5').prop('disabled', true).text('삭제중.....');
          },
          success: function(result) {
            $btn.css('opacity', '1').prop('disabled', false).text('삭제합니다');
            if (result.error == 1) {
              $.openMsgModal(result.message);
            } else {
              // 사진 태그 삭제
              $('.btn-photo-modal[data-photo="' + filename + '"]').remove();

              var files = $('input[name=filename]').val();
              var newFiles = '';
              var file = files.split(',');
              for (var i in file) {
                if (filename != file[i] && file[i] != '') newFiles += file[i] + ',';
              }
              $('input[name=filename]').val(newFiles);

              $('#photoModal').modal('hide');
            }
          }
        });
      }).on('click', '.btn-item-entry', function() {
        // 등록/수정
        var $btn = $(this);
        var $dom = $('#formShop');
        var baseUrl = $('input[name=base_url]').val();
        var formData = new FormData($dom[0]);

        // 에디터 데이터 가져오기
        var item_content = CKEDITOR.instances.item_content.getData();
        formData.append('item_content', item_content);

        if ($('input[name=item_name]').val() == '') {
          $.openMsgModal('품명은 꼭 입력해주세요.');
          return false;
        }
        if ($('select[name=item_category1]').val() == '' || $('select[name=item_category2]').val() == '') {
          $.openMsgModal('분류는 꼭 선택해주세요.');
          return false;
        }
        if ($('input[name=item_cost]').val() == '') {
          $.openMsgModal('가격은 꼭 입력해주세요.');
          return false;
        }
        if ($('input[name=filename]').val() == '') {
          $.openMsgModal('대표 사진은 꼭 하나 이상 업로드 해주세요.');
          return false;
        }

        $.ajax({
          url: $dom.attr('action'),
          processData: false,
          contentType: false,
          data: formData,
          dataType: 'json',
          type: 'post',
          beforeSend: function() {
            $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요.....');
          },
          success: function(result) {
            $btn.css('opacity', '1').prop('disabled', false).text('확인합니다');
            if (result.error == 1) {
              $.openMsgModal(result.message);
            } else {
              $('#messageModal .modal-footer .btn').hide();
              $('#messageModal .modal-footer .btn-close, #messageModal .modal-footer .btn-list').show();
              $('#messageModal .modal-footer .btn-list').data('action', 'shop/index');
              $('#messageModal .modal-message').text(result.message);
              $('#messageModal').modal('show');
            }
          }
        });
      }).on('click', '.btn-item-delete', function() {
        // 삭제
        var $btn = $(this);
        var baseUrl = $('input[name=base_url]').val();
        $.ajax({
          url: baseUrl + 'shop/delete',
          data: 'idx=' + $('input[name=idx]').val(),
          dataType: 'json',
          type: 'post',
          beforeSend: function() {
            $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요.....');
          },
          success: function(result) {
            $btn.css('opacity', '1').prop('disabled', false).text('삭제합니다');
            if (result.error == 1) {
              $.openMsgModal(result.message);
            } else {
              $('#messageModal .modal-footer .btn').hide();
              $('#messageModal .modal-footer .btn-list').data('action', 'shop/index').show();
              $('#messageModal .modal-message').text(result.message);
              $('#messageModal').modal({backdrop: 'static', keyboard: false});
            }
          }
        });
      }).on('change', '.item-category', function() {
        // 분류 선택
        var baseUrl = $('input[name=base_url]').val();
        var categoryIdx = $(this).val();
        $.ajax({
          url: baseUrl + 'shop/category/' + categoryIdx,
          dataType: 'json',
          type: 'post',
          beforeSend: function() {
            $('.item-category-loader').html('<img src="/public/images/ajax-loader-sm.gif">');
          },
          success: function(result) {
            $('.item-category-child, .item-category-loader').empty();
            $.each(result, function(i, v) {
              $('.item-category-child').append('<option value="' + v.idx + '">' + v.name + '</option>');
            });
          }
        });
      }).on('click', '.btn-add-option', function() {
        var addedOption = '<div class="row align-items-center w-100 mt-1 option-row"><div class="col-sm-2"><input type="text" name="item_option[]" class="form-control"></div><div class="col-sm-2 item-cost"><input type="text" name="added_price[]" maxlength="8" class="form-control"><span class="item-cost-text">원</span></div><div class="col-sm-2 item-cost"><input type="text" name="added_cost[]" maxlength="8" class="form-control"><span class="item-cost-text">원</span></div></div>';
        $('#sortable').append(addedOption);
      });

      $('#sortable').disableSelection().sortable();
    </script>

    <!-- Photo Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="smallmodalLabel">사진 미리보기</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">
            <img src="" class="w-100">
          </div>
          <div class="modal-footer">
            <input type="hidden" name="delete_filename" class="photo">
            <button type="button" class="btn btn-primary btn-photo-delete">삭제합니다</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
          </div>
        </div>
      </div>
    </div>
