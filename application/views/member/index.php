<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="mypage">
          <h2>마이페이지</h2>
          <b><?=$userData['nickname']?></b>님은 현재 <?=$userLevel['levelName']?> 이십니다.<br>
          현재 산행 횟수 <?=$userLevel['cntNotice']?>회, 예약 횟수 <?=$userLevel['cntReserve']?>회, 취소 페널티 <?=$userData['penalty']?>점, 현재 레벨은 <?=$userLevel['level']?>점 입니다.

          <h3>
            ■ 예약 내역
            <div class="area-btn">
              <button type="button" class="btn btn-primary btn-mypage-payment">결제정보입력</button>
              <button type="button" class="btn btn-secondary btn-mypage-cancel">예약좌석취소</button>
            </div>
          </h3>
          <form id="reserveForm" method="post">
<?php foreach ($userReserve as $key => $value): ?>
            <dl>
              <dt><input type="checkbox" id="cr<?=$key?>" name="checkReserve[]" class="check-reserve" value="<?=$value['idx']?>" data-cost="<?=$value['cost']?>" data-status="<?=$value['status']?>"><label for="cr<?=$key?>"></label></dt>
              <dd>
                <?=viewNoticeStatus($value['notice_status'])?> <a href="<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$value['resCode']?>"><?=$value['subject']?></a> - <?=$value['seat']?>번 좌석<br>
                <small>
                  일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 
                  분담금 : <?=number_format($value['cost'])?>원 /
                  <?=!empty($value['status']) && $value['status'] == STATUS_ABLE ? '입금완료' : '입금대기'?>
                  <?=!empty($value['depositname']) ? ' / 입금자 : ' . $value['depositname'] : ''?>
                </small>
              </dd>
            </dl>
<?php endforeach; ?>
          </form>

          <h3>■ 산행 내역</h3>
<?php foreach ($userVisit as $value): ?>
            <dl>
              <dd>
                <?=viewNoticeStatus($value['notice_status'])?> <?=$value['subject']?> - <?=$value['seat']?>번 좌석<br>
                <small>
                  일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 
                  분담금 : <?=number_format($value['cost'])?>원
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
                <dd><strong class="paymentCost text-danger"></strong><input type="hidden" name="paymentCost"></dd>
              </dl>
              <dl>
                <dt>포인트 사용</dt>
                <dd>
                  총 <span class="myPoint"><?=number_format($userData['point'])?></span> 포인트 중
                  <input type="number" name="usingPoint" class="using-point form-control form-control-sm"> 포인트 사용<br>
                  <label class="mb-0"><input type="checkbox" class="using-point-all"> 포인트 전액 사용</label>
                  <input type="hidden" name="userPoint" value="<?=$userData['point']?>">
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

      <!-- 예약좌석 취소 -->
      <div class="modal fade" id="reserveCancelModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="smallmodalLabel">예약좌석 취소</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body text-center">
              <p class="modal-message">정말로 취소하시겠습니까?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary btn-reserve-cancel">승인</button>
              <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
            </div>
          </div>
        </div>
      </div>
