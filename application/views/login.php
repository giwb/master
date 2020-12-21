<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div class="club-main row">
      <div class="col-sm-4 d-none d-sm-block">&nbsp;</div>
      <div class="col-12 col-sm-4">
        <form class="loginForm pl-4 pr-4" method="post">
          <input type="hidden" name="redirectUrl" value="<?=$redirect_url?>">
          <h2 class="mt-4 mb-4 pb-4 border-bottom text-center">로그인</h2>
          <div class="row mb-2">
            <div class="col-sm-3">아이디</div>
            <div class="col-sm-9"><input type="text" name="login_userid" class="form-control input-login" value="<?=get_cookie('cookie_userid')?>"></div>
          </div>
          <div class="row align-items-center mb-2">
            <div class="col-sm-3">비밀번호</div>
            <div class="col-sm-9"><input type="password" name="login_password" class="form-control input-login" value="<?=get_cookie('cookie_passwd')?>"></div>
          </div>
          <div class="row align-items-center mb-2">
            <div class="col-sm-3"></div>
            <div class="col-sm-9"><label class="small"><input type="checkbox" name="save"> 아이디/비밀번호 저장</label></div>
          </div>
          <div class="error-message text-danger text-center"></div>
          <div class="text-center mt-3 mr-3 ml-3">
            <a href="<?=BASE_URL?>/login/check"><button type="button" class="btn btn-default"><span class="small">회원가입</span></button></a>
            <a href="<?=BASE_URL?>/login/forgot"><button type="button" class="btn btn-secondary"><span class="small">아이디/비밀번호 찾기</span></button></a>
          </div>
          <div class="text-center mt-3 mb-5">
            <button type="button" class="btn btn-default btn-login pl-5 pr-5 pt-2 pb-2">로그인</button>
          </div><!--
          <div class="text-center mt-3">
            <a href="<?=BASE_URL?>/login/oauth/?provider=kakao&redirectUrl=<?=BASE_URL?>/<?=API_KAKAO_URL?>"><img src="https://developers.kakao.com/assets/img/about/logos/kakaologin/kr/kakao_account_login_btn_medium_narrow.png"></a><br>
            <a href="https://kauth.kakao.com/oauth/logout?client_id=<?=API_KAKAO?>&logout_redirect_uri=<?=BASE_URL?>">카카오 로그아웃</a>
          </div>-->
        </form>
      </div>
      <div class="col-sm-4 d-none d-sm-block">&nbsp;</div>
    </div>
