<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800"><?=$pageTitle?></h1>
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
              </select>
            </div>
            <div class="col-sm-1 pl-0 pr-0">산행으로 검색</div>
            <div class="col-sm-2 pl-0 pr-0"><input type="text" name="subject" class="form-control form-search" value="<?=!empty($subject) ? $subject : ''?>"></div>
            <div class="col-sm-1 pl-0 pr-0">닉네임으로 검색</div>
            <div class="col-sm-2 pl-0 pr-0"><input type="text" name="nickname" class="form-control form-search" value="<?=!empty($nickname) ? $nickname : ''?>"></div>
            <div class="col-sm-3 text-left"><button type="button" class="btn btn-primary btn-member-search">검색</button></div>
          </form>
        </div>
        <?php foreach ($listHistory as $value): ?>
          <div class="border-bottom pt-3 pb-3 pl-2 pr-2">
            <strong><?=$value['header']?></strong> <?=$value['subject']?> - <a<?=!empty($value['userid']) ? ' target="_blank" href="' . base_url() . 'admin/member_view/' . $value['userData']['idx'] . '"' : ' href="javascript:;"'?> class="text-secondary"><strong><?=$value['userData']['nickname']?>님</strong></a>
            <div class="small"><?=calcStoryTime($value['regdate'])?> (<?=date('Y-m-d H:i:s', $value['regdate'])?>)</div>
          </div>
        <?php endforeach; ?>
        <div class="area-append">
        </div>
        <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
      </div>
    </div>
