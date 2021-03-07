<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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
            <dl>
              <dt>아이디</dt>
              <dd><input type="text" name="login_userid" class="form-control input-login" value="<?=!empty($cookieUserid) ? $cookieUserid : ''?>"></dd>
            </dl>
            <dl>
              <dt>비밀번호</dt>
              <dd><input type="password" name="login_password" class="form-control input-login" value="<?=!empty($cookiePasswd) ? $cookiePasswd : ''?>"></dd>
            </dl>
            <label class="small pl-5"><input type="checkbox" name="save"> 아이디/비밀번호 저장</label>
          </form>
          <div class="error-message"></div>
        </div>
        <div class="modal-footer">
          <div class="modal-footer-left">
            <a href="<?=BASE_URL?>/login/check"><button type="button" class="btn btn-default">회원가입</button></a>
            <a href="<?=BASE_URL?>/login/forgot"><button type="button" class="btn btn-secondary">아이디/비밀번호 찾기</button></a>
          </div>
          <div class="modal-footer-right">
            <button type="button" class="btn btn-default btn-login">로그인</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

</body>
</html>
