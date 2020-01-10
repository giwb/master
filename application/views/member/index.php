<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="mypage mt-2">
          <h2>마이페이지</h2>
          <b><?=$viewMember['nickname']?></b>님은 현재 <?=$userLevel['levelName']?> 이십니다.<br>
          현재 산행 횟수 <?=number_format(count($userVisitCount))?>회, 예약 횟수 <?=number_format($viewMember['rescount'])?>회, 취소 페널티 <?=number_format($viewMember['penalty'])?>점, 현재 레벨은 <?=number_format($viewMember['rescount'] - $viewMember['penalty'])?>점 입니다.
<?php if (!empty($userData['admin']) && $userData['admin'] == 1): ?>
          <h3>
            ■ 구매 내역
            <div class="area-btn">
              <button type="button" class="btn btn-primary btn-shop-payment">결제정보</button>
              <button type="button" class="btn btn-danger btn-shop-cancel">구매취소</button>
              <a href="<?=base_url()?>member/shop/<?=$clubIdx?>"><button type="button" class="btn btn-secondary">더보기</button></a>
            </div>
          </h3>
          <form id="shopForm" method="post" action="<?=base_url()?>club/shop_payment/<?=$clubIdx?>">
            <?=$listPurchase?>
          </form>
<?php endif; ?>
          <h3>
            ■ 예약 내역
            <div class="area-btn">
              <?php if ($userData['level'] != LEVEL_FREE): ?>
              <button type="button" class="btn btn-primary btn-mypage-payment">결제정보</button>
              <?php endif; ?>
              <button type="button" class="btn btn-danger btn-reserve-cancel">예약취소</button>
              <a href="<?=base_url()?>member/reserve/<?=$clubIdx?>"><button type="button" class="btn btn-secondary">더보기</button></a>
            </div>
          </h3>
          <form id="reserveForm" method="post" action="<?=base_url()?>reserve/payment/<?=$clubIdx?>">
            <?php foreach ($userReserve as $key => $value): ?>
            <dl>
              <dt><input type="checkbox" id="cr<?=$key?>" name="checkReserve[]" class="check-reserve" value="<?=$value['idx']?>" data-reserve-cost="<?=$value['cost_total']?>" data-payment-cost="<?=$value['real_cost']?>" data-status="<?=$value['status']?>" data-penalty="<?=$value['penalty']?>"><label for="cr<?=$key?>"></label></dt>
              <dd>
                <?=viewStatus($value['notice_status'])?> <a href="<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$value['resCode']?>"><?=$value['subject']?></a> - <?=checkDirection($value['seat'], $value['bus'], $value['notice_bustype'], $value['notice_bus'])?>번 좌석<br>
                <small>
                  일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 
                  요금 : <?=$value['view_cost']?> /
                  <?=!empty($value['status']) && $value['status'] == STATUS_ABLE ? '입금완료' : '입금대기'?>
                  <?=!empty($value['depositname']) ? ' / 입금자 : ' . $value['depositname'] : ''?>
                </small>
              </dd>
            </dl>
            <?php endforeach; ?>
          </form>

          <h3>
            ■ 예약취소 내역
            <div class="area-btn">
              <a href="<?=base_url()?>member/reserve_cancel/<?=$clubIdx?>"><button type="button" class="btn btn-secondary">더보기</button></a>
            </div>
          </h3>
          <?php foreach ($userReserveCancel as $value): ?>
          <dl>
            <dd>
              <?=viewStatus($value['notice_status'])?> <a href="<?=base_url()?>reserve/<?=$clubIdx?>?n=<?=$value['resCode']?>"><?=$value['subject']?></a><br>
              <small>
                취소일시 : <?=date('Y-m-d', $value['regdate'])?> (<?=calcWeek(date('Y-m-d', $value['regdate']))?>) <?=date('H:i', $value['regdate'])?>
              </small>
            </dd>
          </dl>
          <?php endforeach; ?>

          <h3>
            ■ 산행 내역
            <div class="area-btn">
              <a href="<?=base_url()?>member/reserve_past/<?=$clubIdx?>"><button type="button" class="btn btn-secondary">더보기</button></a>
            </div>
          </h3>
          <?php foreach ($userVisit as $value): ?>
          <dl>
            <dd>
              <?=viewStatus($value['notice_status'])?> <a href="<?=base_url()?>reserve/<?=$clubIdx?>?n=<?=$value['resCode']?>"><?=$value['subject']?></a> - <?=checkDirection($value['seat'], $value['bus'], $value['notice_bustype'], $value['notice_bus'])?>번 좌석<br>
              <small>
                일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 
                요금 : <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원
              </small>
            </dd>
          </dl>
          <?php endforeach; ?>

          <h3>
            ■ 포인트 내역 <small>- 잔액 <?=number_format($viewMember['point'])?> 포인트</small>
            <div class="area-btn">
              <a href="<?=base_url()?>member/point/<?=$clubIdx?>"><button type="button" class="btn btn-secondary">더보기</button></a>
            </div>
          </h3>
          <ul>
            <?php foreach ($userPoint as $value): ?>
            <?php
              switch ($value['action']):
                case LOG_POINTUP:
            ?>
            <li><strong><span class="text-primary">[포인트추가]</span> <?=!empty($value['subject']) ? $value['subject'] . ' - ' : ''?><?=number_format($value['point'])?> 포인트 추가</strong>
            <?php
                  break;
                case LOG_POINTDN:
            ?>
            <li><strong><span class="text-danger">[포인트감소]</span> <?=!empty($value['subject']) ? $value['subject'] . ' - ' : ''?><?=number_format($value['point'])?> 포인트 감소</strong>
            <?php
                  break;
              endswitch;
            ?>
              <small>일시 : <?=date('Y-m-d, H:i:s', $value['regdate'])?></small></li>
            <?php endforeach; ?>
          </ul>

          <h3>
            ■ 페널티 내역
            <div class="area-btn">
              <a href="<?=base_url()?>member/penalty/<?=$clubIdx?>"><button type="button" class="btn btn-secondary">더보기</button></a>
            </div>
          </h3>
          <ul>
            <?php foreach ($userPenalty as $value): ?>
            <?php
              switch ($value['action']):
                case LOG_PENALTYUP:
            ?>
            <li><strong><span class="text-danger">[페널티추가]</span> <?=!empty($value['subject']) ? $value['subject'] . ' - ' : ''?><?=number_format($value['point'])?> 페널티 추가</strong>
            <?php
                  break;
                case LOG_PENALTYDN:
            ?>
            <li><strong><span class="text-primary">[페널티감소]</span> <?=!empty($value['subject']) ? $value['subject'] . ' - ' : ''?><?=number_format($value['point'])?> 페널티 감소</strong>
            <?php
                  break;
              endswitch;
            ?>
              <small>일시 : <?=date('Y-m-d, H:i:s', $value['regdate'])?></small></li>
            <?php endforeach; ?>
          </ul>
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
        }).on('click', '.btn-shop-payment', function() {
          // 구매 결제정보 입력 모달
          var reserveIdx = new Array();
          var reserveStatus = 0;
          var reserveCost = 0;
          var paymentCost = 0;
          var usingPoint = 0;
          var depositName = '';
          var noticeIdx = '';
          var message = '';
          $('.check-purchase:checked').each(function() {
            reserveIdx.push( $(this).val() );
            reserveCost += Number($(this).data('reserve-cost'));
            paymentCost += Number($(this).data('payment-cost'));
            usingPoint += Number($(this).data('using-point'));
            if ($(this).data('status') == <?=ORDER_PAY?>) {
              reserveStatus = $(this).data('status');
            }
            depositName = $(this).data('deposit-name');
            noticeIdx = $(this).data('notice-idx');
          });
          /*
          if (reserveStatus == <?=ORDER_PAY?>) {
            $.openMsgModal('이미 입금확인된 내역이 포함되어 있습니다.');
            return false;
          }
          */

          if (reserveIdx.length > 0) {
            $('#reservePaymentModal .area-shop').show();
            $('#reservePaymentModal input[name=paymentType]').val(2); // 결제 형식은 구매
            $('#reservePaymentModal input[name=reserveCost]').val(reserveCost);
            $('#reservePaymentModal .reserveCost').text($.setNumberFormat(reserveCost) + '원');
            $('#reservePaymentModal input[name=paymentCost]').val(paymentCost - usingPoint);
            $('#reservePaymentModal input[name=originCost]').val(paymentCost);
            $('#reservePaymentModal .paymentCost').text($.setNumberFormat(paymentCost - usingPoint) + '원' + message);
            $('#reservePaymentModal input[name=usingPoint]').val(usingPoint);
            $('#reservePaymentModal input[name=depositName]').val(depositName);
            $('#reservePaymentModal select[name=noticeIdx]').val(noticeIdx);
            $('#reservePaymentModal').modal({backdrop: 'static', keyboard: false});
          } else {
            $.openMsgModal('결제정보를 입력할 구매 내역을 선택해주세요.');
          }
        }).on('click', '.btn-reserve-payment', function() {
          // 결제정보 입력 처리
          var $btn = $(this);
          var paymentType = $('input[name=paymentType]').val();
          if (paymentType == 1) $dom = $('#reserveForm'); else $dom = $('#shopForm');
          var formData = new FormData($dom[0]);
          formData.append('paymentType', $('input[name=paymentType]').val());
          formData.append('depositName', $('input[name=depositName]').val());
          formData.append('usingPoint', $('input[name=usingPoint]').val());
          formData.append('paymentCost', $('input[name=paymentCost]').val());
          if (typeof $('select[name=noticeIdx]').val() != 'undefined' && $('select[name=noticeIdx]').val() != '') {
            formData.append('noticeIdx', $('select[name=noticeIdx]').val());
          }

          $.ajax({
            url: $dom.attr('action'),
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
        }).on('click', '.btn-shop-cancel', function() {
          // 구매 취소 모달
          var $dom;
          var resIdx = new Array();
          $dom = $('.check-purchase:checked'); // 마이페이지
          $dom.each(function() {
            resIdx.push( $(this).val() );
          });

          if (resIdx.length > 0) {
            $('#reserveCancelModal input[name=resIdx]').val(resIdx);
            $('#reserveCancelModal input[name=resType]').val(2); // 결제 형식은 구매
            $('#reserveCancelModal .modal-message').text('정말로 구매를 취소하시겠습니까?');
            $('#reserveCancelModal').modal({backdrop: 'static', keyboard: false});
          } else {
            $.openMsgModal('취소할 예약 내역을 선택해주세요.');
          }
        });
      </script>
