<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main class="club_main memberForm">
    <div class="container-fluid">
      <div class="row align-items-center mt-2 mb-2">
        <div class="col-1 col-lg-2"></div>
        <div class="col-lg-8">
          <h2 class="mt-2 text-center font-weight-bold">로그인</h2>
          <hr class="bg-danger mb-5">

          <form class="loginForm row" method="post">
            <div class="col-1 col-lg-3"></div>
            <div class="col-lg-6">
              <div class="row align-items-center mb-2">
                <div class="col-3 font-weight-bold">아이디</div>
                <div class="col-9"><input type="text" name="login_userid" class="form-control input-login" value="<?=get_cookie('cookie_userid')?>"></div>
              </div>
              <div class="row align-items-center mb-2">
                <div class="col-3 font-weight-bold">비밀번호</div>
                <div class="col-9"><input type="password" name="login_password" class="form-control input-login" value="<?=get_cookie('cookie_passwd')?>"></div>
              </div>
              <div class="row align-items-center mb-2">
                <div class="col-3"></div>
                <div class="col-9"><label class="small"><input type="checkbox" name="save"> 아이디/비밀번호 저장</label></div>
              </div>
              <div class="error-message text-danger text-center"></div>
              <div class="text-center mt-3 mb-2">
                <input type="hidden" name="redirectUrl" value="<?=$redirect_url?>">
                <button type="button" class="btn btn-danger btn-login pl-5 pr-5">로그인</button>
              </div>
              <div class="text-center mr-3 ml-3">
                <a href="<?=BASE_URL?>/login/check"><button type="button" class="btn btn-info">회원가입</button></a>
                <a href="<?=BASE_URL?>/login/forgot"><button type="button" class="btn btn-secondary">아이디/비밀번호 찾기</button></a>
              </div>
            </div>
            <div class="col-1 col-lg-3"></div>
          </form>

        </div>
      </div>
    </div>
  </main>