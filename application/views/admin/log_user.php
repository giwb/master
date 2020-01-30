<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <h2 class="d-none d-sm-block border-bottom mb-2 pb-3"><?=$pageTitle?></h2>
        <div class="container">
          <div class="row align-items-center text-center header-menu">
            <div class="col-2 active">회원</div>
            <div class="col-2">예약</div>
            <div class="col-2">차량</div>
            <div class="col-2">구매</div>
            <div class="col-2">댓글</div>
            <div class="col-2">방문자</div>
          </div>
        </div>
        <div class="w-100 border mt-2 mb-3 p-3">
          <form id="formList" method="post" action="<?=$pageUrl?>" class="row align-items-center text-center">
            <input type="hidden" name="p" value="1">
            <div class="col-sm-1 pl-0 pr-0">형태로 검색</div>
            <div class="col-sm-2 pl-0 pr-0">
              <select name="action" class="form-control">
                <option value=""></option>
                <?php if ($pageType == 'member'): ?>
                <option<?=$action[0] == 1 ? ' selected' : ''?> value="1">회원가입</option>
                <option<?=$action[0] == 2 ? ' selected' : ''?> value="2">산행예약</option>
                <option<?=$action[0] == 3 ? ' selected' : ''?> value="3">산행취소</option>
                <option<?=$action[0] == 4 ? ' selected' : ''?> value="4">포인트 적립</option>
                <option<?=$action[0] == 5 ? ' selected' : ''?> value="5">포인트 감소</option>
                <option<?=$action[0] == 6 ? ' selected' : ''?> value="6">페널티 추가</option>
                <option<?=$action[0] == 7 ? ' selected' : ''?> value="7">페널티 감소</option>
                <?php endif; ?>
                <?php if ($pageType == 'admin'): ?>
                <option<?=$action[0] == 8 ? ' selected' : ''?> value="8">관리자 예약</option>
                <option<?=$action[0] == 9 ? ' selected' : ''?> value="9">관리자 취소</option>
                <option<?=$action[0] == 10 ? ' selected' : ''?> value="10">관리자 입금확인</option>
                <option<?=$action[0] == 11 ? ' selected' : ''?> value="11">관리자 입금취소</option>
                <?php endif; ?>
                <?php if ($pageType == 'refund'): ?>
                <option<?=$action[0] == 12 ? ' selected' : ''?> value="12">비회원 환불기록</option>
                <?php endif; ?>
                <?php if ($pageType == 'bus'): ?>
                <option<?=$action[0] == 51 ? ' selected' : ''?> value="51">버스 변경기록</option>
                <?php endif; ?>
                <?php if ($pageType == 'buy'): ?>
                <option<?=$action[0] == 21 ? ' selected' : ''?> value="21">구매내역</option>
                <option<?=$action[0] == 22 ? ' selected' : ''?> value="22">결제내역</option>
                <option<?=$action[0] == 23 ? ' selected' : ''?> value="23">취소내역</option>
                <?php endif; ?>
                <?php if ($pageType == 'buy_admin'): ?>
                <option<?=$action[0] == 24 ? ' selected' : ''?> value="24">관리자 구매</option>
                <option<?=$action[0] == 25 ? ' selected' : ''?> value="25">관리자 취소</option>
                <option<?=$action[0] == 26 ? ' selected' : ''?> value="26">관리자 입금확인</option>
                <option<?=$action[0] == 27 ? ' selected' : ''?> value="27">관리자 입금취소</option>
                <option<?=$action[0] == 28 ? ' selected' : ''?> value="28">관리자 판매완료</option>
                <?php endif; ?>
              </select>
            </div>
            <div class="col-sm-1 pl-0 pr-0">상태로 검색</div>
            <div class="col-sm-2 pl-0 pr-0">
              <select name="status" class="form-control">
                <option<?=$status == 0 ? ' selected' : ''?> value="0">미확인 상태만 보기</option>
                <option<?=$status == 1 ? ' selected' : ''?> value="1">확인 상태만 보기</option>
              </select>
            </div>
            <div class="col-sm-1 pl-0 pr-0">닉네임으로 검색</div>
            <div class="col-sm-2 pl-0 pr-0"><input type="text" name="nickname" class="form-control form-search" value="<?=!empty($nickname) ? $nickname : ''?>"></div>
            <div class="col-sm-3 text-left"><button type="button" class="btn btn-primary btn-member-search">검색</button></div>
          </form>
        </div>

        <?=$listHistory?>

        <div class="area-append"></div>
        <?php if ($maxLog['cnt'] > $perPage): ?>
        <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
        <?php endif; ?>
      </div>
    </div>
