<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-contents">
          <div class="area-reservation text-center"><br>
            <h2>예약이 완료되었습니다!</h2>

            <form id="reserveForm" method="post" class="border-top border-bottom text-left mt-4 mb-4 pt-3 pl-5 pr-5">
              <?php foreach ($listReserve as $key => $value): ?>
              <dl>
                <dt><?=viewStatus($value['notice_status'])?> <a href="<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$value['resCode']?>"><?=$value['subject']?></a> - <?=checkDirection($value['seat'], $value['bus'], $value['notice_bustype'], $value['notice_bus'])?>번 좌석</dt>
                <dd>
                  일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 
                  요금 : <?=$value['view_cost']?> /
                  <?=!empty($value['status']) && $value['status'] == STATUS_ABLE ? '입금완료' : '입금대기'?>
                  <?=!empty($value['depositname']) ? ' / 입금자 : ' . $value['depositname'] : ''?>
                  <input type="hidden" name="checkReserve[]" class="check-reserve" value="<?=$value['idx']?>" data-reserve-cost="<?=$value['cost_total']?>" data-payment-cost="<?=$value['real_cost']?>">
                </dd>
              </dl>
              <?php endforeach; ?>
            </form>

            결제정보입력은 아래 버튼을 눌러서 곧바로 진행하실 수 있으며,<br>
            추후 마이페이지에서도 입력하실 수 있습니다.<br><br><br>

            <?php if ($userData['level'] != LEVEL_FREE): ?>
            <button type="button" class="btn btn-primary btn-mypage-payment">결제정보입력</button></a>
            <?php endif; ?>
            <a href="<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$view['noticeIdx']?>"><button type="button" class="btn btn-secondary">좌석현황보기</button></a>
          </div>
        </div>
        <!--
        <?php if (!empty($listItem)): ?>
        <div class="border-top mt-5 pt-3 pl-2"><h4>■ 추천상품</h4></div>
        <div id="shop" class="sub-content pl-3 pr-3 pb-3">
          <form id="formList">
            <?=$listItem?>
          </form>
        </div>
        <?php endif; ?>
        -->
        <div class="ad-sp mt-5">
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
            <div class="error-message"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary btn-reserve-payment">입력완료</button>
              <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
            </div>
          </div>
        </div>
      </div>

      <script>
        $(document).on('click', '.btn-mypage-payment', function() {
          // 결제정보 입력 모달
          var reserveIdx = new Array();
          var reserveStatus = 0;
          var reserveCost = 0;
          var paymentCost = 0;
          var message = '';
          $('.check-reserve').each(function() {
            reserveIdx.push( $(this).val() );
            reserveCost += Number($(this).data('reserve-cost'));
            paymentCost += Number($(this).data('payment-cost'));
          });

          if (reserveIdx.length > 0) {
            <?php if ($viewMember['level'] == LEVEL_LIFETIME): // 평생회원은 5천원 할인 ?>
            message = ' (평생회원 할인)';
            <?php endif; ?>
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
          var baseUrl = $('input[name=baseUrl]').val();
          var clubIdx = $('input[name=clubIdx]').val();
          var formData = new FormData($('#reserveForm')[0]);
          formData.append('depositName', $('input[name=depositName]').val());
          formData.append('usingPoint', $('input[name=usingPoint]').val());
          formData.append('paymentCost', $('input[name=paymentCost]').val());

          $.ajax({
            url: baseUrl + 'reserve/payment/' + clubIdx,
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
                location.href = (baseUrl + 'member/' + clubIdx);
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
          <?php if ($viewMember['level'] == LEVEL_LIFETIME): // 평생회원은 5천원 할인 ?>
          message = ' (평생회원 할인)';
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
          <?php if ($viewMember['level'] == LEVEL_LIFETIME): // 평생회원은 5천원 할인 ?>
          message = ' (평생회원 할인)';
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
      <script type="text/javascript" src="<?=base_url()?>public/js/shop.js"></script>
