<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div class="text-center">
      <ins class="kakao_ad_area" style="display: none;" data-ad-unit="DAN-vmKgrkeNJQjRcNJm" data-ad-width="320" data-ad-height="100"></ins>
      <script type="text/javascript" src="//t1.daumcdn.net/kas/static/ba.min.js" async></script>
    </div>
  </main>
</div>

<footer class="page-footer stylish-color-dark mt-4 text-center p-4">
  <div class="white-text">
    Copyright &copy; <script>document.write(new Date().getFullYear());</script> <strong>한국여행</strong>. All Rights Reserved.<br>
    <div class="small mt-1">
      <a href="#">이용약관</a> |
      <a href="#">개인정보 취급방침</a>
    </div>
  </div>
</footer>
<input type="hidden" name="redirectUrl" value="<?=BASE_URL?>">

<?php if (empty($userData['idx'])): ?>
<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="smallmodalLabel">로그인</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <form class="loginForm" method="post">
          <div class="row align-items-center">
            <div class="col-3">아이디</div>
            <div class="col-9"><input type="text" name="login_userid" class="form-control input-login" value="<?=!empty($cookieUserid) ? $cookieUserid : ''?>"></div>
          </div>
          <div class="row align-items-center pt-2">
            <div class="col-3">비밀번호</div>
            <div class="col-9"><input type="password" name="login_password" class="form-control input-login" value="<?=!empty($cookiePasswd) ? $cookiePasswd : ''?>"></div>
          </div>
          <div class="row align-items-center pt-2">
            <div class="col-3"></div>
            <div class="col-9 text-left"><label class="small"><input type="checkbox" name="save"> 아이디/비밀번호 저장</label></div>
          </div>
        </form>
        <div class="error-message"></div>
      </div>
      <div class="border-top text-center p-3">
        <a href="<?=BASE_URL?>/login/check"><button type="button" class="btn btn-default">회원가입</button></a>
        <a href="<?=BASE_URL?>/login/forgot"><button type="button" class="btn btn-secondary">아이디/비밀번호 찾기</button></a>
        <button type="button" class="btn btn-default btn-login">로그인</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

</body>
</html>
