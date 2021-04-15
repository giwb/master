<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">

          <h2 class="mt-2 text-center font-weight-bold">로그인</h2>
          <hr class="bg-danger mb-5">

          <form class="loginForm row" method="post">
            <div class="col-lg-3"></div>
            <div class="col-12 col-lg-6">
              <div class="row align-items-center mb-2">
                <div class="col-sm-3 font-weight-bold">아이디</div>
                <div class="col-sm-8"><input type="text" name="login_userid" class="form-control" value="<?=get_cookie('cookie_userid')?>"></div>
              </div>
              <div class="row align-items-center mb-2">
                <div class="col-sm-3 font-weight-bold">비밀번호</div>
                <div class="col-sm-8"><input type="password" name="login_password" class="form-control" value="<?=get_cookie('cookie_passwd')?>"></div>
              </div>
              <div class="row align-items-center mb-2">
                <div class="col-sm-3"></div>
                <div class="col-sm-8"><label class="small"><input type="checkbox" name="save"> 아이디/비밀번호 저장</label></div>
              </div>
              <div class="error-message text-danger text-center"></div>
              <div class="text-center mt-3 mb-2">
                <input type="hidden" name="redirectUrl" value="<?=$redirect_url?>">
                <button type="button" class="btn btn-danger btn-login pl-5 pr-5">로그인</button>
              </div>
              <div class="text-center mr-3 ml-3">
                <a href="<?=BASE_URL?>/login/entry"><button type="button" class="btn btn-info pl-3 pr-3">회원가입</button></a>
                <a href="<?=BASE_URL?>/login/forgot"><button type="button" class="btn btn-secondary pl-3 pr-3">아이디/비밀번호 찾기</button></a>
              </div>

              <div style="width: 482px; margin: 0 auto;">
                <!-- 애드핏 -->
                <section class="section mt-4 mb-4">
                  <div class="card">
                    <ins class="kakao_ad_area" style="display:none;" data-ad-unit="DAN-nZvV4BAxj0QXpJeQ" data-ad-width="320" data-ad-height="100"></ins>
                    <script type="text/javascript" src="//t1.daumcdn.net/kas/static/ba.min.js" async></script>
                  </div>
                </section>

                <?php if (ENVIRONMENT == 'production'): ?>
                <!-- 구글 광고 -->
                <section class="section">
                  <div class="card text-center">
                    <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-2424708381875991" data-ad-slot="1285643193" data-ad-format="auto" data-full-width-responsive="true"></ins>
                    <script> (adsbygoogle = window.adsbygoogle || []).push({}); </script>
                  </div>
                </section>
                <?php endif; ?>
              </div>

            </div>
            <div class="col-lg-3"></div>
          </form>
        </div>
        <div class="col-lg-2"></div>
      </div>
    </div>
  </main>