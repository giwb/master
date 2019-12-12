<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="mypage">
          <h2>마이페이지</h2>
          <b><?=$userData['nickname']?></b>님은 현재 <?=$userLevel['levelName']?> 이십니다.<br>
          현재 산행 횟수 <?=number_format(count($userVisitCount))?>회, 예약 횟수 <?=number_format($userData['rescount'])?>회, 취소 페널티 <?=number_format($userData['penalty'])?>점, 현재 레벨은 <?=number_format($userData['rescount'] - $userData['penalty'])?>점 입니다.

          <h3>
            ■ 예약 내역
            <div class="area-btn">
              <button type="button" class="btn btn-primary btn-mypage-payment">결제정보입력</button>
              <button type="button" class="btn btn-secondary btn-reserve-cancel">예약좌석취소</button>
            </div>
          </h3>
          <form id="reserveForm" method="post">
<?php foreach ($userReserve as $key => $value): ?>
            <dl>
              <dt><input type="checkbox" id="cr<?=$key?>" name="checkReserve[]" class="check-reserve" value="<?=$value['idx']?>" data-cost="<?=$value['cost_total'] == 0 ? $value['cost'] : $value['cost_total']?>" data-status="<?=$value['status']?>"><label for="cr<?=$key?>"></label></dt>
              <dd>
                <?=viewStatus($value['notice_status'])?> <a href="<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$value['resCode']?>"><?=$value['subject']?></a> - <?=checkDirection($value['seat'], $value['bus'], $value['notice_bustype'], $value['notice_bus'])?>번 좌석<br>
                <small>
                  일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 
                  분담금 : <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 /
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
                <?=$value['subject']?><br>
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
                <?=$value['subject']?> - <?=checkDirection($value['seat'], $value['bus'], $value['notice_bustype'], $value['notice_bus'])?>번 좌석<br>
                <small>
                  일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 
                  분담금 : <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원
                </small>
              </dd>
            </dl>
<?php endforeach; ?>

          <h3>■ 포인트 내역 <small>- 잔액 <?=number_format($userData['point'])?> 포인트</small></h3>
          <ul>
<?php foreach ($userPoint as $value): ?>
<?php
  switch ($value['action']):
    case LOG_POINTUP:
?>
              <li><strong><span class="text-primary">[포인트추가]</span> <?=number_format($value['point'])?> 포인트 추가</strong>
<?php
    break;
    case LOG_POINTDN:
?>
              <li><strong><span class="text-danger">[포인트감소]</span> <?=number_format($value['point'])?> 포인트 감소</strong>
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
              <li><strong><span class="text-danger">[페널티추가]</span> <?=number_format($value['point'])?> 페널티 추가</strong>
<?php
    break;
    case LOG_PENALTYDN:
?>
              <li><strong><span class="text-primary">[페널티감소]</span> <?=number_format($value['point'])?> 페널티 감소</strong>
<?php
    break;
  endswitch;
?>
              <small>일시 : <?=date('Y-m-d, H:i:s', $value['regdate'])?></small></li>
<?php endforeach; ?>
          </ul>
        </div>
      </div>

      <script>
        $(document).on('click', '.btn-mypage-payment', function() {
          // 결제정보 입력 모달
          var reserveIdx = new Array();
          var reserveCost = 0;
          var reserveStatus = 0;
          var reducedCost = 0;
          $('.check-reserve:checked').each(function() {
            reserveIdx.push( $(this).val() );
            reserveCost += Number($(this).data('cost'));
            if ($(this).data('status') == 1) {
              reserveStatus = $(this).data('status');
            }
          });

          if (reserveStatus == 1) {
            $.openMsgModal('이미 입금완료된 좌석이 포함되어 있습니다.');
            return false;
          }

          if (reserveIdx.length > 0) {
            $('#reservePaymentModal input[name=reserveCost]').val(reserveCost);
            $('#reservePaymentModal .reserveCost').text($.setNumberFormat(reserveCost) + '원');
            <?php if ($userData['level'] == LEVEL_LIFETIME): // 평생회원은 5천원 할인 ?>
            reducedCost = Number(reserveCost) - 5000;
            $('#reservePaymentModal input[name=paymentCost]').val(reducedCost);
            $('#reservePaymentModal .paymentCost').html('<s>' + $.setNumberFormat(reserveCost) + '원</s> → ' + $.setNumberFormat(reducedCost) + '원 (평생회원 할인)');
            <?php elseif ($userData['level'] == LEVEL_FREE): // 무료회원은 무료 ?>
            reducedCost = 0;
            $('#reservePaymentModal input[name=paymentCost]').val(reducedCost);
            $('#reservePaymentModal .paymentCost').html('<s>' + $.setNumberFormat(reserveCost) + '원</s> → ' + $.setNumberFormat(reducedCost) + '원 (무료회원 할인)');
            <?php else: // 일반회원 ?>
            $('#reservePaymentModal input[name=paymentCost]').val(reserveCost);
            $('#reservePaymentModal .paymentCost').text($.setNumberFormat(reserveCost) + '원');
            <?php endif; ?>
            $('#reservePaymentModal').modal({backdrop: 'static', keyboard: false});
          } else {
            $.openMsgModal('결제정보를 입력할 예약 내역을 선택해주세요.');
          }
        }).on('click', '.btn-reserve-payment', function() {
          // 결제정보 입력 처리
          var $btn = $(this);
          var formData = new FormData($('#reserveForm')[0]);
          formData.append('depositName', $('input[name=depositName]').val());
          formData.append('usingPoint', $('input[name=usingPoint]').val());
          formData.append('paymentCost', $('input[name=paymentCost]').val());

          $.ajax({
            url: $('input[name=baseUrl]').val() + 'reserve/payment/' + $('input[name=clubIdx]').val(),
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
          var point = Number($(this).val());
          var userPoint = Number($('input[name=userPoint]').val());
          var reserveCost = Number($('#reservePaymentModal input[name=reserveCost]').val());
          <?php if ($userData['level'] == LEVEL_LIFETIME): // 평생회원은 5천원 할인 ?>
          var reducedCost = reserveCost - 5000;
          var message = ' (평생회원 할인)';
          <?php elseif ($userData['level'] == LEVEL_FREE): // 무료회원은 무료 ?>
          var reducedCost = 0;
          var message = ' (무료회원 할인)';
          <?php else: // 일반회원 ?>
          var reducedCost = reserveCost;
          var message = '';
          <?php endif; ?>

          if (point > userPoint) {
            $.openMsgModal('보유한 포인트만 사용할 수 있습니다.');
            $(this).val('');
          } else {
            if (reducedCost > point) {
              result = reducedCost - point;
            }

            $('#reservePaymentModal input[name=paymentCost]').val(result);
            $('#reservePaymentModal .paymentCost').html('<s>' + $.setNumberFormat(reserveCost) + '원</s> → ' + $.setNumberFormat(result) + '원' + message);
          }
        }).on('click', '.using-point-all', function() {
          // 포인트 전액 사용
          var result = 0;
          var userPoint = Number($('input[name=userPoint]').val());
          var reserveCost = Number($('#reservePaymentModal input[name=reserveCost]').val());
          <?php if ($userData['level'] == LEVEL_LIFETIME): // 평생회원은 5천원 할인 ?>
          var reducedCost = reserveCost - 5000;
          var message = ' (평생회원 할인)';
          <?php elseif ($userData['level'] == LEVEL_FREE): // 무료회원은 무료 ?>
          var reducedCost = 0;
          var message = ' (무료회원 할인)';
          <?php else: // 일반회원 ?>
          var reducedCost = reserveCost;
          var message = '';
          <?php endif; ?>

          if (reserveCost > userPoint) {
            result = reducedCost - userPoint;
          } else {
            userPoint = reducedCost;
          }

          if ($(this).is(':checked') == true) {
            $('#reservePaymentModal input[name=usingPoint]').val(userPoint);
            $('#reservePaymentModal input[name=paymentCost]').val(result);
            $('#reservePaymentModal .paymentCost').html('<s>' + $.setNumberFormat(reserveCost) + '원</s> → ' + $.setNumberFormat(result) + '원' + message);
          } else {
            $('#reservePaymentModal input[name=usingPoint]').val('');
            $('#reservePaymentModal input[name=paymentCost]').val(reducedCost);
            $('#reservePaymentModal .paymentCost').html('<s>' + $.setNumberFormat(reserveCost) + '원</s> → ' + $.setNumberFormat(reducedCost) + '원' + message);
          }
        });
      </script>
