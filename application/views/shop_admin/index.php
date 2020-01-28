<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

          <h1 class="h3 mb-4 text-gray-800">등록된 상품 관리</h1>

          <div class="w-100 border mt-2 mb-3 p-3">
            <form id="formList" method="post" action="<?=BASE_URL?>/shop/index" class="row align-items-center text-center">
              <input type="hidden" name="p" value="1">
              <div class="col-sm-1 pl-0 pr-0">품명 검색</div>
              <div class="col-sm-2 pl-0 pr-0"><input type="text" name="item_name" class="form-control form-item-search" value="<?=!empty($search['item_name']) ? $search['item_name'] : ''?>"></div>
              <div class="col-sm-1 pl-0 pr-0">카테고리 1차</div>
              <div class="col-sm-2 pl-0 pr-0">
                <select name="item_category1" class="form-control item-category">
                  <option value=''></option>
                  <?php if (!empty($listCategory1)): foreach ($listCategory1 as $value): ?>
                  <option<?=$search['item_category1'] == $value['idx'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                  <?php endforeach; endif; ?>
                </select>
              </div>
              <div class="col-sm-1 pl-0 pr-0">카테고리 2차</div>
              <div class="col-sm-2 pl-0 pr-0">
                <select name="item_category2" class="form-control item-category-child">
                  <option value=''></option>
                  <?php if (!empty($listCategory2)): foreach ($listCategory2 as $value): ?>
                  <option<?=$search['item_category2'] == $value['idx'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                  <?php endforeach; endif; ?>
                </select>
              </div>
              <div class="col-sm-1 text-left"><button type="button" class="btn btn-primary btn-item-search">검색</button></div>
              <div class="col-sm-1 item-category-loader text-left"></div>
            </form>
          </div>

          <?=$listItem?>

          <div class="area-append"></div>
          <button class="btn btn-page-next">다음 페이지 보기 ▼</button>

        </div>
      </div>
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
        var baseUrl = $('input[name=base_url]').val();
        var categoryIdx = $(this).val();
        if (categoryIdx != '') {
          $.ajax({
            url: '/ShopAdmin/category/' + categoryIdx,
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
      }).on('click', '.item-list', function() {
        // 상세보기
        location.href = '<?=BASE_URL?>/shop/entry/' + $(this).data('idx');
      });
    </script>