<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">전체 회원 목록</h1>
        </div>
        <div class="w-100 border mt-2 mb-3 p-3">
          <form id="formList" method="post" action="/admin_old/member_list" class="row align-items-center text-center">
            <input type="hidden" name="p" value="1">
            <div class="col-sm-1 pl-0 pr-0">실명으로 검색</div>
            <div class="col-sm-2 pl-0 pr-0"><input type="text" name="realname" class="form-control form-search" value="<?=!empty($search['realname']) ? $search['realname'] : ''?>"></div>
            <div class="col-sm-1 pl-0 pr-0">닉네임으로 검색</div>
            <div class="col-sm-2 pl-0 pr-0"><input type="text" name="nickname" class="form-control form-search" value="<?=!empty($search['nickname']) ? $search['nickname'] : ''?>"></div>
            <div class="col-sm-1 pl-0 pr-0">등급으로 검색</div>
            <div class="col-sm-2 pl-0 pr-0">
              <select name="levelType" class="form-control">
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
            <div class="col-sm-3 text-left"><button type="button" class="btn btn-primary btn-member-search">검색</button></div>
          </form>
        </div>
<?php
  foreach ($listMembers as $value):
    $level = memberLevel($value['rescount'], $value['penalty'], $value['level'], $value['admin']);
?>
        <dl>
          <dt>[<?=$value['idx']?>] <a href="/admin_old/member_view/<?=$value['idx']?>"><?=$value['realname']?> / <?=$value['nickname']?> / <?=$value['userid']?> / <?=$level['levelName']?></a></dt>
          <dd><?=$value['phone']?>, <?=$value['birthday']?> <?=$value['birthday_type'] == '1' ? '(양력)' : '(음력)' ?><?=!empty($value['location']) ? ', ' . arrLocation(NULL, $value['location']) : ''?><br>등록일 : <?=date('Y-m-d, H:i:s', $value['regdate'])?>, <?=!empty($value['lastdate']) ? '최종접속일 : ' . date('Y-m-d, H:i:s', $value['lastdate']) . ', ' : ''?>접속횟수 : <?=$value['connect']?></dd>
        </dl>
<?php
  endforeach;
?>
        <div class="area-append">
        </div>
        <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
      </div>
    </div>
