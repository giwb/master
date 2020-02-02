<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <div class="w-100 border mt-3 mb-3 p-3">
            <form id="formList" method="get" action="<?=BASE_URL?>/ShopAdmin/index" class="m-0">
              <input type="hidden" name="p" value="1">
              <div class="row align-items-center w-100 text-center">
                <div class="col-3 col-sm-1 p-0">품명</div>
                <div class="col-9 col-sm-5"><input type="text" name="item_name" class="form-control form-control-sm form-item-search" value="<?=!empty($search['item_name']) ? $search['item_name'] : ''?>"></div>
                <div class="w-100 d-block d-sm-none pt-2"></div>
                <div class="col-3 col-sm-1 p-0">닉네임</div>
                <div class="col-9 col-sm-5"><input type="text" name="nickname" class="form-control form-control-sm form-item-search" value="<?=!empty($search['nickname']) ? $search['nickname'] : ''?>"></div>
              </div>
              <div class="row align-items-center w-100 pt-2 text-center">
                <div class="col-3 col-sm-1 p-0">산행별</div>
                <div class="col-9 col-sm-9">
                  <select name="mname" class="form-control form-control-sm">
                    <option value=''>산행명을 선택하세요</option>
                    <option value=''>--------------</option>
                    <?php foreach ($listNotice as $value): ?>
                    <option<?=$value['mname'] == $search['mname'] ? ' selected': ''?> value='<?=$value['mname']?>'><?=$value['mname']?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="w-100 d-block d-sm-none pt-2"></div>
                <div class="col-sm-2 text-left"><button class="btn btn-sm btn-default w-100 btn-item-search">검색</button></div>
              </div>
            </form>
          </div>
          <?=$listPurchase?>
          <div class="area-append"></div>
          <?php if ($maxPurchase['cnt'] > $perPage): ?>
          <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
          <?php endif; ?>
        </div>
        <div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">메세지</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-center">
                <p class="modal-message"></p>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="idx">
                <input type="hidden" name="status">
                <input type="hidden" name="action">
                <button type="button" class="btn btn-primary btn-order-status">승인</button>
                <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
              </div>
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
          }).on('click', '.btn-order-status-modal', function() {
            var $dom = $('#changeStatusModal');
            var idx = $(this).data('idx');
            var status = $(this).data('status');

            if (status == <?=ORDER_CANCEL?>) {
              $('.modal-message', $dom).text('정말로 구매를 취소하시겠습니까?');
            } else if (status == <?=ORDER_PAY?>) {
              $('.modal-message', $dom).text('정말로 입금 확인을 하시겠습니까?');
            } else if (status == <?=ORDER_END?>) {
              $('.modal-message', $dom).text('정말로 판매를 완료하시겠습니까?');
            }

            $('input[name=idx]', $dom).val(idx);
            $('input[name=status]', $dom).val(status);
            $('input[name=action]', $dom).val('<?=BASE_URL?>/ShopAdmin/change_status');
            $('.btn-order-status', $dom).show();
            $dom.modal('show');
          }).on('click', '.btn-order-status', function() {
            // 상태 변경
            var $dom = $('#changeStatusModal');
            var $btn = $(this);
            var idx = $(this).data('idx');
            var status = $(this).data('status');
            $.ajax({
              url: $('input[name=action]').val(),
              data: 'idx=' + $('input[name=idx]', $dom).val() + '&status=' + $('input[name=status]', $dom).val(),
              dataType: 'json',
              type: 'post',
              beforeSend: function() {
                $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요...');
              },
              success: function(result) {
                $btn.css('opacity', '1').prop('disabled', false).text('승인');
                if (result.error != 1) {
                  $btn.hide();
                  if (result.type == 1) {
                    if (result.status == <?=ORDER_CANCEL?>) {
                      // 구매취소
                      $('#order-' + result.idx + ' .area-status').html('<strong class="text-secondary">[구매취소]</strong>');
                      $('#order-' + result.idx + ' .btn-area').empty().append('<button type="button" class="btn btn-sm btn-dark btn-order-delete-modal" data-idx="' + result.idx + '">삭제</button>');
                    } else if (result.status == <?=ORDER_PAY?>) {
                      // 입금완료
                      $('#order-' + result.idx + ' .area-status').html('<strong class="text-info">[입금완료]</strong>');
                      $('#order-' + result.idx + ' .btn-order-status-modal[data-status=' + result.status + ']').hide();
                    } else if (result.status == <?=ORDER_END?>) {
                      // 판매완료
                      $('#order-' + result.idx + ' .area-status').html('<strong class="text-primary">[구매완료]</strong>');
                      $('#order-' + result.idx + ' .btn-area').empty().append('<button type="button" class="btn btn-sm btn-danger">판매완료</button>');
                    }
                  } else if (result.type == 2) {
                    $('#order-' + result.idx).hide();
                  }
                }
                $('.modal-message', $dom).text(result.message);
              }
            });
          }).on('click', '.btn-order-delete-modal', function() {
            // 삭제 모달
            var $dom = $('#changeStatusModal');
            var idx = $(this).data('idx');

            $('input[name=idx]', $dom).val(idx);
            $('input[name=action]', $dom).val('<?=BASE_URL?>/ShopAdmin/delete_purchase');
            $('.modal-message', $dom).text('정말로 삭제하시겠습니까?');
            $('.btn-order-status', $dom).show();
            $dom.modal('show');
          }).on('click', '.item-list', function() {
            // 상세보기
            location.href = '<?=BASE_URL?>/ShopAdmin/entry/' + $(this).data('idx');
          });
        </script>
