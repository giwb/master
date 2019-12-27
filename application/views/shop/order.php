<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

          <h1 class="h3 mb-4 text-gray-800">주문 관리</h1>
<!--
          <div class="w-100 border mt-2 mb-3 p-3">
            <form id="formList" method="post" action="<?=base_url()?>shop/index" class="row align-items-center text-center">
              <input type="hidden" name="p" value="1">
              <div class="col-sm-1 pl-0 pr-0">품명 검색</div>
              <div class="col-sm-2 pl-0 pr-0"><input type="text" name="item_name" class="form-control form-item-search" value="<?=!empty($search['item_name']) ? $search['item_name'] : ''?>"></div>
              <div class="col-sm-1 pl-0 pr-0">닉네임 검색</div>
              <div class="col-sm-2 pl-0 pr-0"><input type="text" name="nickname" class="form-control form-item-search" value="<?=!empty($search['nickname']) ? $search['nickname'] : ''?>"></div>
              <div class="col-sm-1 pl-0 pr-0">산행별 검색</div>
              <div class="col-sm-2 pl-0 pr-0">
                <select name="item_category2" class="form-control item-category-child">
                  <option value=''></option>
                </select>
              </div>
              <div class="col-sm-1 text-left"><button type="button" class="btn btn-primary btn-item-search">검색</button></div>
              <div class="col-sm-1 item-category-loader text-left"></div>
            </form>
          </div>
-->
          <?=$listOrder?>

          <div class="area-append"></div>
          <?php if ($maxOrder['cnt'] > $perPage): ?>
          <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
          <?php endif; ?>

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
            url: baseUrl + 'shop/category/' + categoryIdx,
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
        location.href = $('input[name=base_url]').val() + 'shop/entry/' + $(this).data('idx');
      });
    </script>