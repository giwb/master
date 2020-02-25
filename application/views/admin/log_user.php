<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <div class="w-100 border mt-3 mb-3 p-3">
            <form id="formList" method="get" action="<?=$pageUrl?>" class="m-0">
              <input type="hidden" name="p" value="1">
              <div class="row align-items-center w-100 text-center">
                <div class="col-3 col-sm-1 p-0">형태</div>
                <div class="col-9 col-sm-5">
                  <select name="action" class="form-control form-control-sm">
                    <option value=""></option>
                    <?php if ($pageType == 'log'): ?>
                    <option<?=$action[0] == 1 ? ' selected' : ''?> value="1">회원가입</option>
                    <option<?=$action[0] == 2 ? ' selected' : ''?> value="2">산행예약</option>
                    <option<?=$action[0] == 3 ? ' selected' : ''?> value="3">산행취소</option>
                    <option<?=$action[0] == 4 ? ' selected' : ''?> value="4">포인트 적립</option>
                    <option<?=$action[0] == 5 ? ' selected' : ''?> value="5">포인트 감소</option>
                    <option<?=$action[0] == 6 ? ' selected' : ''?> value="6">페널티 추가</option>
                    <option<?=$action[0] == 7 ? ' selected' : ''?> value="7">페널티 감소</option>
                    <option<?=$action[0] == 8 ? ' selected' : ''?> value="8">관리자 예약</option>
                    <option<?=$action[0] == 9 ? ' selected' : ''?> value="9">관리자 취소</option>
                    <option<?=$action[0] == 10 ? ' selected' : ''?> value="10">관리자 입금확인</option>
                    <option<?=$action[0] == 11 ? ' selected' : ''?> value="11">관리자 입금취소</option>
                    <option<?=$action[0] == 12 ? ' selected' : ''?> value="12">비회원 환불기록</option>
                    <?php elseif ($pageType == 'bus'): ?>
                    <option<?=$action[0] == 51 ? ' selected' : ''?> value="51">버스 변경기록</option>
                    <?php elseif ($pageType == 'buy'): ?>
                    <option<?=$action[0] == 21 ? ' selected' : ''?> value="21">구매내역</option>
                    <option<?=$action[0] == 22 ? ' selected' : ''?> value="22">결제내역</option>
                    <option<?=$action[0] == 23 ? ' selected' : ''?> value="23">취소내역</option>
                    <?php endif; ?>
                  </select>
                </div>
                <div class="w-100 d-block d-sm-none pt-2"></div>
                <div class="col-3 col-sm-1 p-0">상태</div>
                <div class="col-9 col-sm-5">
                  <select name="status" class="form-control form-control-sm">
                    <option<?=$status == 0 ? ' selected' : ''?> value="0">미확인</option>
                    <option<?=$status == 1 ? ' selected' : ''?> value="1">확인</option>
                  </select>
                </div>
              </div>
              <div class="row align-items-center w-100 pt-2 text-center">
                <div class="col-3 col-sm-1 p-0">닉네임</div>
                <div class="col-9 col-sm-9"><input type="text" name="nickname" class="form-control form-control-sm form-search" value="<?=!empty($nickname) ? $nickname : ''?>"></div>
                <div class="w-100 d-block d-sm-none pt-2"></div>
                <div class="col-sm-2 text-left"><button class="btn btn-sm btn-<?=$viewClub['main_color']?> w-100 btn-member-search">검색</button></div>
              </div>
            </form>
          </div>
          <?=$listHistory?>
          <div class="area-append"></div>
          <?php if ($maxLog['cnt'] > $perPage): ?>
          <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
          <?php endif; ?>
        </div>
