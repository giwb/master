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
              <button type="button" class="btn btn-primary btn-shop-payment">결제정보입력</button>
              <button type="button" class="btn btn-secondary btn-shop-cancel">구매취소</button>
            </div>
          </h3>
          <form id="shopForm" method="post" action="<?=base_url()?>club/shop_payment/<?=$clubIdx?>">
            <?php foreach ($listPurchase as $key => $value): ?>
            <dl>
              <dd>
                <div class="border">
                  <div class="bg-light p-2"><input type="checkbox" id="cp<?=$key?>" name="checkPurchase[]" class="check-purchase" value="<?=$value['idx']?>" data-reserve-cost="<?=$value['cost_total']?>" data-payment-cost="<?=$value['cost_total']?>" data-status="<?=$value['status']?>"><label for="cp<?=$key?>"></label><?=!empty($value['status']) && $value['status'] == RESERVE_PAY ? '<strong>[입금완료]</strong>' : '<strong class="text-secondary">[입금대기]</strong>'?> 구매일 <?=date('Y-m-d', $value['created_at'])?> (<?=calcWeek(date('Y-m-d', $value['created_at']))?>) <?=date('H:i', $value['created_at'])?></div>
                  <div class="pt-2 pb-2 pl-3 pr-3 font-weight-normal">
                    ・구매금액 : <?=number_format($value['cost_total'])?>원 / 사용한 포인트 : <?=number_format($value['point'])?>원<br>
                    ・인수산행 : <?php if (!empty($value['startdate'])): ?><a href="<?=base_url()?>reserve/<?=$clubIdx?>?n=<?=$value['noticeIdx']?>"><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['mname']?></a><?php else: ?>미지정<?php endif; ?>
                    <?php foreach ($value['listCart'] as $key => $item): ?>
                    <div class="row align-items-center mt-2">
                      <div class="col col-sm-2"><img class="w-100" src="<?=base_url() . PHOTO_URL . $item['photo']?>"></div>
                      <div class="col col-sm-10"><a href="<?=base_url()?>club/shop_item/<?=$clubIdx?>?n=<?=$item['idx']?>"><?=$item['name']?></a><br><small><?=!empty($item['option']) ? $item['option'] . ' - ' : ''?><?=number_format($item['amount'])?>개, <?=number_format($item['cost'] * $item['amount'])?>원</small></div>
                    </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </dd>
            </dl>
            <?php endforeach; ?>
          </form>
<?php endif; ?>
          <h3>
            ■ 예약 내역
            <div class="area-btn">
              <button type="button" class="btn btn-primary btn-mypage-payment">결제정보입력</button>
              <button type="button" class="btn btn-secondary btn-reserve-cancel">예약좌석취소</button>
            </div>
          </h3>
          <form id="reserveForm" method="post" action="<?=base_url()?>reserve/payment/<?=$clubIdx?>">
            <?php foreach ($userReserve as $key => $value): ?>
            <dl>
              <dt><input type="checkbox" id="cr<?=$key?>" name="checkReserve[]" class="check-reserve" value="<?=$value['idx']?>" data-reserve-cost="<?=$value['cost_total']?>" data-payment-cost="<?=$value['real_cost']?>" data-status="<?=$value['status']?>"><label for="cr<?=$key?>"></label></dt>
              <dd>
                <?=viewStatus($value['notice_status'])?> <a href="<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$value['resCode']?>"><?=$value['subject']?></a> - <?=checkDirection($value['seat'], $value['bus'], $value['notice_bustype'], $value['notice_bus'])?>번 좌석<br>
                <small>
                  일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 
                  분담금 : <?=$value['view_cost']?> /
                  <?=!empty($value['status']) && $value['status'] == STATUS_ABLE ? '입금완료' : '입금대기'?>
                  <?=!empty($value['depositname']) ? ' / 입금자 : ' . $value['depositname'] : ''?>
                </small>
              </dd>
            </dl>
            <?php endforeach; ?>
          </form>

          <h3>■ 예약취소 내역</h3>
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

          <h3>■ 산행 내역</h3>
          <?php foreach ($userVisit as $value): ?>
          <dl>
            <dd>
              <?=viewStatus($value['notice_status'])?> <a href="<?=base_url()?>reserve/<?=$clubIdx?>?n=<?=$value['resCode']?>"><?=$value['subject']?></a> - <?=checkDirection($value['seat'], $value['bus'], $value['notice_bustype'], $value['notice_bus'])?>번 좌석<br>
              <small>
                일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 
                분담금 : <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원
              </small>
            </dd>
          </dl>
          <?php endforeach; ?>

          <h3>■ 포인트 내역 <small>- 잔액 <?=number_format($viewMember['point'])?> 포인트</small></h3>
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

          <h3>■ 페널티 내역</h3>
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

          if (reserveStatus == 1) {
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
          var message = '';
          $('.check-purchase:checked').each(function() {
            reserveIdx.push( $(this).val() );
            reserveCost += Number($(this).data('reserve-cost'));
            paymentCost += Number($(this).data('payment-cost'));
            if ($(this).data('status') == 1) {
              reserveStatus = $(this).data('status');
            }
          });

          if (reserveStatus == 1) {
            $.openMsgModal('이미 입금완료된 좌석이 포함되어 있습니다.');
            return false;
          }

          if (reserveIdx.length > 0) {
            $('#reservePaymentModal .area-shop').show();
            $('#reservePaymentModal input[name=paymentType]').val(2); // 결제 형식은 구매
            $('#reservePaymentModal input[name=reserveCost]').val(reserveCost);
            $('#reservePaymentModal .reserveCost').text($.setNumberFormat(reserveCost) + '원');
            $('#reservePaymentModal input[name=paymentCost]').val(paymentCost);
            $('#reservePaymentModal input[name=originCost]').val(paymentCost);
            $('#reservePaymentModal .paymentCost').text($.setNumberFormat(paymentCost) + '원' + message);
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
        });
      </script>
