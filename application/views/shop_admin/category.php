<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

          <h1 class="h3 mb-5 text-gray-800">분류 관리</h1>

          <div class="row w-100 align-items-top mt-5 ml-0">
            <div class="col-sm-2 border bg-white p-2 mt-2 category-parent">
              <div class="text-center border-bottom font-weight-bold pb-2">1차 분류</div>
              <div class="mt-3 mb-3 text-center">
                <div class="category-input"><input type="text" name="category_name" size="15" class="form-control form-control-sm" value="<?=!empty($view['name']) ? $view['name'] : ''?>"></div>
                <div class="category-input"><input type="hidden" name="category_idx" value=""><button type="button" class="btn btn-sm btn-primary btn-category-entry" data-parent="">등록</button> <button type="button" class="btn btn-sm btn-danger btn-category-delete d-none" data-type="parent">삭제</button></div>
              </div>
              <div class="list-category m-3">
                <?php foreach ($listCategory as $value): ?>
                <a href="javascript:;" class="item-category" data-idx="<?=$value['idx']?>" data-type="parent"><?=$value['name']?></a>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-2 border bg-white p-2 mt-2 category-child d-none">
              <div class="text-center border-bottom font-weight-bold pb-2">2차 분류</div>
              <div class="mt-3 mb-3 text-center">
                <div class="category-input"><input type="text" name="category_name" size="15" class="form-control form-control-sm" value="<?=!empty($view['name']) ? $view['name'] : ''?>"></div>
                <div class="category-input"><input type="hidden" name="category_idx" value=""><input type="hidden" name="category_parent" value=""><button type="button" class="btn btn-sm btn-primary btn-category-entry">등록</button> <button type="button" class="btn btn-sm btn-danger btn-category-delete d-none" data-type="child">삭제</button></div>
              </div>
              <div class="list-category m-3"></div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <script type="text/javascript">
      $(document).on('click', '.btn-category-entry', function() {
        // 분류 등록/수정
        var $btn = $(this);
        var baseUrl = $('input[name=base_url]').val();
        var categoryIdx = $(this).parent().parent().find('input[name=category_idx]').val();
        var categoryName = $(this).parent().parent().find('input[name=category_name]').val();
        var categoryParent = $(this).parent().parent().find('input[name=category_parent]').val();
        if (categoryName == '') {
          $.openMsgModal('분류명은 꼭 입력해주세요.');
          return false;
        }
        $.ajax({
          url: baseUrl + 'shop/category_update',
          data: 'category_name=' + categoryName + '&category_idx=' + categoryIdx + '&category_parent=' + categoryParent,
          dataType: 'json',
          type: 'post',
          beforeSend: function() {
            $btn.css('opacity', '0.5').prop('disabled', true);
          },
          success: function(result) {
            if (typeof categoryParent != 'undefined' && categoryParent != '') {
              var $dom = $('.category-child');
              var categoryType = 'child';
            } else {
              var $dom = $('.category-parent');
              var categoryType = 'parent';
              $('.category-child').addClass('d-none');
            }
            $btn.css('opacity', '1').prop('disabled', false);

            // 수정값 초기화
            $('input[name=category_idx]', $dom).val('');
            $('input[name=category_name]', $dom).val('');
            $('.btn-category-delete', $dom).addClass('d-none');

            if (result.error == 1) {
              $.openMsgModal(result.message);
            } else {
              if (categoryIdx != '') {
                // 수정
                $('.item-category[data-idx="' + result.message + '"]', $dom).text(categoryName);
              } else {
                // 등록
                $('.list-category', $dom).append('<a href="javascript:;" class="item-category" data-idx="' + result.message + '" data-type="' + categoryType + '">' + categoryName + '</a>');
              }
              $btn.text('등록');
            }
          }
        });
      }).on('click', '.item-category', function() {
        // 분류 선택
        var baseUrl = $('input[name=base_url]').val();
        var categoryType = $(this).data('type');
        var categoryIdx = $(this).data('idx');
        var $dom = $('.category-' + categoryType);
        $('input[name=category_idx]', $dom).val(categoryIdx);
        $('input[name=category_name]', $dom).val($(this).text());
        $('.btn-category-entry', $dom).text('수정');
        $('.btn-category-delete', $dom).removeClass('d-none');

        if (categoryType == 'parent') {
          // 부모일 경우 자식 초기화
          $('.category-child input[name=category_idx]').val('');
          $('.category-child input[name=category_name]').val('');
          $('.category-child input[name=category_parent]').val(categoryIdx);
          $('.category-child .list-category').empty();
          $('.category-child .btn-category-entry').text('등록');
          $('.category-child .btn-category-delete').addClass('d-none');
          $('.category-child').removeClass('d-none');

          $.ajax({
            url: baseUrl + 'shop/category/' + categoryIdx,
            dataType: 'json',
            type: 'post',
            beforeSend: function() {
              $('.category-child .list-category').html('<div class="ajax-loader text-center"><img src="/public/images/ajax-loader.gif"></div>');
            },
            success: function(result) {
              $('.ajax-loader').remove();
              $.each(result, function(i, v) {
                $('.category-child .list-category').append('<a href="javascript:;" class="item-category" data-idx="' + v.idx + '" data-type="child">' + v.name + '</a>');
              });
            }
          });
        }
      }).on('click', '.btn-category-delete', function() {
        // 분류 삭제
        var $btn = $(this);
        var baseUrl = $('input[name=base_url]').val();
        var categoryType = $(this).data('type');
        var categoryIdx = $('.category-' + categoryType + ' input[name=category_idx]').val();
        $.ajax({
          url: baseUrl + 'shop/category_delete',
          data: 'category_idx=' + categoryIdx,
          dataType: 'json',
          type: 'post',
          beforeSend: function() {
            $btn.css('opacity', '0.5').prop('disabled', true);
          },
          success: function(result) {
            var $dom = $('.category-' + categoryType)
            $btn.css('opacity', '1').prop('disabled', false);
            $('input[name=category_idx]', $dom).val('');
            $('input[name=category_name]', $dom).val('');
            $('.btn-category-delete', $dom).addClass('d-none');
            if (result.error == 1) {
              $.openMsgModal(result.message);
            } else {
              $('.item-category[data-idx="' + categoryIdx + '"]', $dom).remove();
              $('.btn-category-entry', $dom).text('등록');
            }
          }
        });
      });
    </script>