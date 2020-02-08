<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <div class="w-100 border mt-3 mb-3 p-3">
            <form id="formList" method="get" action="<?=BASE_URL?>/ShopAdmin/index" class="m-0">
              <input type="hidden" name="p" value="1">
              <div class="row align-items-center w-100 text-center">
                <div class="col-3 col-sm-1 p-0">대분류</div>
                <div class="col-9 col-sm-5">
                  <select name="item_category1" class="form-control form-control-sm item-category">
                    <option value=''></option>
                    <?php if (!empty($listCategory1)): foreach ($listCategory1 as $value): ?>
                    <option<?=$search['item_category1'] == $value['idx'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                    <?php endforeach; endif; ?>
                  </select>
                </div>
                <div class="w-100 d-block d-sm-none pt-2"></div>
                <div class="col-3 col-sm-1 p-0">소분류</div>
                <div class="col-9 col-sm-5">
                  <select name="item_category2" class="form-control form-control-sm item-category-child">
                    <option value=''></option>
                    <?php if (!empty($listCategory2)): foreach ($listCategory2 as $value): ?>
                    <option<?=$search['item_category2'] == $value['idx'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                    <?php endforeach; endif; ?>
                  </select>
                </div>
              </div>
              <div class="row align-items-center w-100 pt-2 text-center">
                <div class="col-3 col-sm-1 p-0">품명</div>
                <div class="col-9 col-sm-9"><input type="text" name="item_name" class="form-control form-control-sm form-item-search" value="<?=!empty($search['item_name']) ? $search['item_name'] : ''?>"></div>
                <div class="w-100 d-block d-sm-none pt-2"></div>
                <div class="col-sm-2 text-left"><button class="btn btn-sm btn-default w-100 btn-item-search">검색</button></div>
              </div>
            </form>
          </div>
          <?=$listItem?>
          <div class="area-append"></div>
          <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
        </div>

        <script type="text/javascript">
          $(document).on('click', '.btn-item-search', function() {
            // 검색
            $('input[name=p]').val('');
            $('#formList').submit();
          }).on('keypress', '.form-item-search', function(e) {
            // 검색 엔터
            if (e.keyCode == 13) {
              $('input[name=p]').val('');
              $('#formList').submit();
            }
          }).on('change', '.item-category', function() {
            // 카테고리 검색
            var categoryIdx = $(this).val();
            if (categoryIdx != '') {
              $.ajax({
                url: '<?=BASE_URL?>/ShopAdmin/category/' + categoryIdx,
                dataType: 'json',
                type: 'post',
                beforeSend: function() {
                  $('.item-category-loader').html('<img src="/public/images/ajax-loader-sm.gif">');
                },
                success: function(result) {
                  $('.item-category-child, .item-category-loader').empty();
                  $('.item-category-child').append('<option value=""></option>');
                  $.each(result, function(i, v) {
                    $('.item-category-child').append('<option value="' + v.idx + '">' + v.name + '</option>');
                  });
                }
              });
            }
          }).on('click', '.btn-item-visible', function() {
            // 상품 보이기/숨기기
            var $btn = $(this);
            $.ajax({
              url: '<?=BASE_URL?>/ShopAdmin/change_visible/',
              data: 'idx=' + $(this).parent().parent().data('idx'),
              dataType: 'json',
              type: 'post',
              beforeSend: function() {
                $btn.css('opacity', '0.5').prop('disabled', true);
              },
              success: function(result) {
                $btn.css('opacity', '1').prop('disabled', false);
                if (result.error == 1) {
                  $.openMsgModal(result.message);
                } else {
                  if (result.message == 'Y') {
                    $btn.text('숨김').removeClass('btn-default').addClass('btn-secondary');
                  } else {
                    $btn.text('공개').removeClass('btn-secondary').addClass('btn-default');
                  }
                }
              }
            });
          }).on('click', '.item-list', function() {
            // 상세보기
            location.href = '<?=BASE_URL?>/ShopAdmin/entry/' + $(this).data('idx');
          });
        </script>
