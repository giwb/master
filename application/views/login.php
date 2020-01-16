<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="memberForm">
          <h2>로그인</h2>

          <form class="loginForm" method="post" class="mt-5 mr-5 ml-5">
            <dl>
              <dt>아이디</dt>
              <dd><input type="text" name="userid" class="form-control input-login" value="<?=get_cookie('cookie_userid')?>"></dd>
            </dl>
            <dl>
              <dt>비밀번호</dt>
              <dd><input type="password" name="password" class="form-control input-login" value="<?=get_cookie('cookie_passwd')?>"></dd>
            </dl>
            <dl>
              <dt></dt>
              <dd><label class="small"><input type="checkbox" name="save"> 아이디/비밀번호 저장</label></dd>
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
          <?php if ($_SERVER['REMOTE_ADDR'] == '49.166.0.82'): ?>
          <div class="text-center mt-5">
            <a target="_blank" href="<?=base_url()?>login/oauth/?provider=kakao"><img src="https://developers.kakao.com/assets/img/about/logos/kakaologin/kr/kakao_account_login_btn_medium_narrow.png"></a>
          </div>
          <?php endif; ?>
        </div>
        <div class="ad-sp">
          <!-- SP_CENTER -->
          <ins class="adsbygoogle"
            style="display:block"
            data-ad-client="ca-pub-2424708381875991"
            data-ad-slot="4319659782"
            data-ad-format="auto"
            data-full-width-responsive="true"></ins>
          <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
          </script>
        </div>
      </div>
