<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <h2 class="sub-header mb-4"><?=$pageTitle?></h2>
        <div id="content" class="mb-5">
          <div class="w-100 border mt-3 mb-3 p-3">
            <form id="formList" method="post" action="<?=BASE_URL?>/admin/member_list" class="m-0">
              <input type="hidden" name="p" value="1">
              <div class="row align-items-center w-100 text-center">
                <div class="col-3 col-sm-1 p-0">검색어</div>
                <div class="col-7 col-sm-9"><input type="text" name="keyword" class="form-control form-control-sm form-search" value="<?=!empty($search['keyword']) ? $search['keyword'] : ''?>"></div>
                <div class="col-2 col-sm-2 text-left"><button class="btn btn-sm btn-default w-100 btn-member-search">검색</button></div>
              </div>
              <div class="row align-items-center w-100 pt-2 text-center">
                <div class="col-3 col-sm-1 p-0">등급</div>
                <div class="col-9 col-sm-11">
                  <select name="levelType" class="form-control form-control-sm select-level-type">
                    <option value=""></option>
                    <option<?=$search['levelType'] == 1 ? ' selected' : ''?> value="1">한그루 회원</option>
                    <option<?=$search['levelType'] == 2 ? ' selected' : ''?> value="2">두그루 회원</option>
                    <option<?=$search['levelType'] == 3 ? ' selected' : ''?> value="3">세그루 회원</option>
                    <option<?=$search['levelType'] == 4 ? ' selected' : ''?> value="4">네그루 회원</option>
                    <option<?=$search['levelType'] == 5 ? ' selected' : ''?> value="5">다섯그루 회원</option>
                    <option<?=$search['levelType'] == 6 ? ' selected' : ''?> value="6">평생회원</option>
                    <option<?=$search['levelType'] == 7 ? ' selected' : ''?> value="7">무료회원</option>
                    <option<?=$search['levelType'] == 8 ? ' selected' : ''?> value="8">드라이버</option>
                    <option<?=$search['levelType'] == 9 ? ' selected' : ''?> value="9">드라이버 관리자</option>
                    <option<?=$search['levelType'] == 10 ? ' selected' : ''?> value="10">관리자</option>
                  </select>
                </div>
              </div>
            </form>
          </div>
          <?=$listMembers?>
          <div class="area-append"></div>
          <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
        </div>
