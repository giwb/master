<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="memberForm">
          <h2>로그인</h2>

          <form class="loginForm" method="post" class="mt-5 mr-5 ml-5">
            <dl>
              <dt>아이디</dt>
              <dd><input type="text" name="userid" class="form-control input-login"></dd>
            </dl>
            <dl>
              <dt>비밀번호</dt>
              <dd><input type="password" name="password" class="form-control input-login"></dd>
            </dl>
            <div class="error-message text-danger text-center"></div>
            <hr>
            <div class="row mr-3 ml-3">
              <div class="col-sm-8">
                <a href="<?=base_url()?>login/entry/<?=$view['idx']?>"><button type="button" class="btn btn-primary">회원가입</button></a>
                <a href="<?=base_url()?>login/forgot/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">아이디/비밀번호 찾기</button></a>
              </div>
              <div class="col-sm-4 text-right">
                <input type="hidden" name="redirectUrl" value="<?=$redirect_url?>">
                <button type="button" class="btn btn-primary btn-login">로그인</button>
              </div>
            </div>
          </form>
        </div>
      </div>
