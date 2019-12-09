<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-contents">
          <div class="area-reservation text-center"><br>
            <h2>예약 신청이 완료되었습니다!</h2>

            <form id="reserveForm" method="post" class="border-top border-bottom text-left mt-4 mb-4 pt-3 pl-5 pr-5">
<?php foreach ($listReserve as $key => $value): ?>
              <dl>
                <dt><?=viewStatus($value['notice_status'])?> <a href="<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$value['resCode']?>"><?=$value['subject']?></a> - <?=checkDirection($value['seat'], $value['bus'], $value['notice_bustype'], $value['notice_bus'])?>번 좌석</dt>
                <dd>
                  일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 
                  분담금 : <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 /
                  <?=!empty($value['status']) && $value['status'] == STATUS_ABLE ? '입금완료' : '입금대기'?>
                  <?=!empty($value['depositname']) ? ' / 입금자 : ' . $value['depositname'] : ''?>
                  <input type="hidden" name="checkReserve[]" class="check-reserve" value="<?=$value['idx']?>" data-cost="<?=$value['cost_total'] == 0 ? $value['cost'] : $value['cost_total']?>">
                </dd>
              </dl>
<?php endforeach; ?>
            </form>

            결제정보입력은 아래 버튼을 눌러서 곧바로 진행하실 수 있으며,<br>
            추후 마이페이지에서도 입력하실 수 있습니다.<br><br><br>

            <button type="button" class="btn btn-primary btn-mypage-payment">결제정보입력</button></a>
            <a href="<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$view['noticeIdx']?>"><button type="button" class="btn btn-secondary">좌석현황보기</button></a>
          </div>
        </div>
      </div>

      <script>
        $(document).on('click', '.btn-mypage-payment', function() {
          // 결제정보 입력 모달
          var reserveIdx = new Array();
          var reserveCost = 0;
          var reserveStatus = 0;
          var reducedCost = 0;
          $('.check-reserve').each(function() {
            reserveIdx.push( $(this).val() );
            reserveCost += Number($(this).data('cost'));
          });

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
