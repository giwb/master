<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="mypage mt-2">
          <h2><?=$pageTitle?></h2>
          <h3>
            ■ 예약 내역
            <div class="area-btn">
              <?php if ($userData['level'] != LEVEL_FREE): ?>
              <button type="button" class="btn btn-primary btn-mypage-payment">결제정보</button>
              <?php endif; ?>
              <button type="button" class="btn btn-danger btn-reserve-cancel">예약취소</button>
            </div>
          </h3>
          <form id="formList" method="post" action="<?=BASE_URL?>/member/reserve">
            <input type="hidden" name="p" value="1">
            <?=$userReserve?>
            <div class="area-append"></div>
            <?php if ($maxReserve['cnt'] > $perPage): ?>
            <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
            <?php endif; ?>
          </form>
        </div>
        <div class="ad-sp">
          <!-- SP_CENTER -->
          <ins class="adsbygoogle"
            style="display:block"
            data-ad-client="ca-pub-2424708381875991"
            data-ad-slot="4319659782"
            data-ad-format="auto"
            data-full-width-responsive="true"></ins>
          <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
          </script>
        </div>
      </div>

      <!-- 결제정보 작성 -->
      <div class="modal fade" id="reservePaymentModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="smallmodalLabel">결제정보 입력</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body text-center">
              <div class="area-reserve">
                <dl>
                  <dt>합계금액</dt>
                  <dd><span class="reserveCost"></span><input type="hidden" name="reserveCost"></dd>
                </dl>
                <dl>
                  <dt>결제금액</dt>
                  <dd><strong class="paymentCost text-danger"></strong><input type="hidden" name="paymentCost"><input type="hidden" name="originCost"></dd>
                </dl>
                <dl>
                  <dt>포인트 사용</dt>
                  <dd>
                    총 <span class="myPoint"><?=number_format($viewMember['point'])?></span> 포인트 중
                    <input type="number" name="usingPoint" class="using-point form-control form-control-sm"> 포인트 사용<br>
                    <label class="mb-0"><input type="checkbox" class="using-point-all"> 포인트 전액 사용</label>
                    <input type="hidden" name="userPoint" value="<?=$viewMember['point']?>">
                  </dd>
                </dl>
                <dl>
                  <dt>입금은행</dt>
                  <dd>국민은행 / 288001-04-154630 / 경인웰빙산악회 (김영미)</dd>
                </dl>
                <dl>
                  <dt>입금자명</dt>
                  <dd><input type="text" name="depositName" class="form-control form-control-sm"></dd>
                </dl>
              </div>
              <div class="area-shop">
                <dl class="border-top-0">
                  <dt>인수할 산행</dt>
                  <dd>
                    <select name="noticeIdx" class="form-control form-control-sm">
                      <option value=''>예약된 산행 보기</option>
                      <option value=''>-------------</option>
                      <?php foreach ($listMemberReserve as $value): ?>
                      <option value='<?=$value['idx']?>'><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['mname']?></option>
                      <?php endforeach; ?>
                    </select>
                  </dd>
                </dl>
              </div>
            </div>
            <div class="error-message"></div>
            <div class="modal-footer">
              <input type="hidden" name="paymentType">
              <button type="button" class="btn btn-primary btn-reserve-payment">입력완료</button>
              <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
            </div>
          </div>
        </div>
      </div>

      <script>
        $(document).on('click', '.btn-mypage-payment', function() {
          // 예약 결제정보 입력 모달
          var reserveIdx = new Array();
          var reserveStatus = 0;
          var reserveCost = 0;
          var paymentCost = 0;
          var message = '';
          $('.check-reserve:checked').each(function() {
            reserveIdx.push( $(this).val() );
            reserveCost += Number($(this).data('reserve-cost'));
            paymentCost += Number($(this).data('payment-cost'));
            if ($(this).data('status') == 1) {
              reserveStatus = $(this).data('status');
            }
          });

          if (reserveStatus == <?=RESERVE_PAY?>) {
            $.openMsgModal('이미 입금완료된 좌석이 포함되어 있습니다.');
            return false;
          }

          if (reserveIdx.length > 0) {
            <?php if ($viewMember['level'] == LEVEL_LIFETIME): // 평생회원은 5천원 할인 ?>
            message = ' (평생회원 할인)';
            <?php endif; ?>
            $('#reservePaymentModal .area-shop').hide();
            $('#reservePaymentModal input[name=paymentType]').val(1); // 결제 형식은 예약
            $('#reservePaymentModal input[name=reserveCost]').val(reserveCost);
            $('#reservePaymentModal .reserveCost').text($.setNumberFormat(reserveCost) + '원');
            $('#reservePaymentModal input[name=paymentCost]').val(paymentCost);
            $('#reservePaymentModal input[name=originCost]').val(paymentCost);
            $('#reservePaymentModal .paymentCost').text($.setNumberFormat(paymentCost) + '원' + message);
            $('#reservePaymentModal').modal({backdrop: 'static', keyboard: false});
          } else {
            $.openMsgModal('결제정보를 입력할 예약 내역을 선택해주세요.');
          }
        }).on('click', '.btn-reserve-payment', function() {
          // 결제정보 입력 처리
          var $btn = $(this);
          var $dom = $('#formList');
          var formData = new FormData($dom[0]);
          var paymentType = $('input[name=paymentType]').val();
          formData.append('clubIdx', $('input[name=clubIdx]').val());
          formData.append('paymentType', $('input[name=paymentType]').val());
          formData.append('depositName', $('input[name=depositName]').val());
          formData.append('usingPoint', $('input[name=usingPoint]').val());
          formData.append('paymentCost', $('input[name=paymentCost]').val());
          if (typeof $('select[name=noticeIdx]').val() != 'undefined' && $('select[name=noticeIdx]').val() != '') {
            formData.append('noticeIdx', $('select[name=noticeIdx]').val());
          }

          $.ajax({
            url: '/reserve/payment',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            type: 'post',
            beforeSend: function() {
              $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
            },
            success: function(result) {
              if (result.error == 1) {
                $('.error-message').text(result.message);
              } else {
                location.reload();
              }
            }
          });
        }).on('blur', '.using-point', function() {
          // 포인트 사용
          var result = 0;
          var message = '';
          var point = Number($(this).val());
          var userPoint = Number($('input[name=userPoint]').val());
          var originCost = Number($('#reservePaymentModal input[name=originCost]').val());
          var paymentType = $('input[name=paymentType]').val();
          <?php if ($viewMember['level'] == LEVEL_LIFETIME): // 평생회원은 5천원 할인 ?>
          if (paymentType == 1) message = ' (평생회원 할인)'; // 예약 결제일 경우에만 적용
          <?php endif; ?>

          if (point > userPoint) {
            $.openMsgModal('보유한 포인트만 사용할 수 있습니다.');
            $(this).val('');
          } else {
            if (originCost > point) {
              result = originCost - point;
            }

            $('#reservePaymentModal input[name=paymentCost]').val(result);
            $('#reservePaymentModal .paymentCost').html($.setNumberFormat(result) + '원' + message);
          }
        }).on('click', '.using-point-all', function() {
          // 포인트 전액 사용
          var result = 0;
          var message = '';
          var userPoint = Number($('input[name=userPoint]').val());
          var originCost = Number($('#reservePaymentModal input[name=originCost]').val());
          var paymentType = $('input[name=paymentType]').val();
          <?php if ($viewMember['level'] == LEVEL_LIFETIME): // 평생회원은 5천원 할인 ?>
          if (paymentType == 1) message = ' (평생회원 할인)'; // 예약 결제일 경우에만 적용
          <?php endif; ?>

          if (originCost > userPoint) {
            result = originCost - userPoint;
          } else {
            userPoint = originCost;
          }

          if ($(this).is(':checked') == true) {
            $('#reservePaymentModal input[name=usingPoint]').val(userPoint);
            $('#reservePaymentModal input[name=paymentCost]').val(result);
            $('#reservePaymentModal .paymentCost').html($.setNumberFormat(result) + '원' + message);
          } else {
            $('#reservePaymentModal input[name=usingPoint]').val('');
            $('#reservePaymentModal input[name=paymentCost]').val(originCost);
            $('#reservePaymentModal .paymentCost').html($.setNumberFormat(originCost) + '원' + message);
          }
        });
      </script>
