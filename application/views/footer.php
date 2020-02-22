<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  </main>

  <nav class="d-block d-sm-none">
    <ul id="nav-footer">
      <li><a href="/"><i class="fas fa-home" aria-hidden="true"></i><br>HOME</a></li>
      <li><a href="/place"><i class="fa fa-bus" aria-hidden="true"></i><br>여행정보</a></li>
      <li><a href="/club"><i class="fas fa-mountain" aria-hidden="true"></i><br>산악회</a></li>
      <?php if (!empty($userData['idx'])): ?>
      <li><a href="/member"><i class="fa fa-user-circle" aria-hidden="true"></i><br>내정보</a></li>
      <?php else: ?>
      <li><a href="javascript:;" class="login-popup"><i class="fa fa-user-circle" aria-hidden="true"></i><br>로그인</a></li>
      <?php endif; ?>
    </ul>
  </nav>

  <input type="hidden" name="baseUrl" value="<?=BASE_URL?>">
  <input type="hidden" name="userIdx" value="<?=!empty($userData['idx']) ? $userData['idx'] : ''?>">
  <input type="hidden" name="redirectUrl" value="<?=$redirectUrl?>">

  <!-- Message Modal -->
  <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="smallmodalLabel">메세지</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <p class="modal-message"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-list">목록으로</button>
          <button type="button" class="btn btn-default btn-refresh">새로고침</button>
          <button type="button" class="btn btn-default btn-delete">삭제합니다</button>
          <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
        </div>
      </div>
    </div>
  </div>

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
          <form class="loginForm mb-0" method="post">
            <dl>
              <dt>아이디</dt>
              <dd><input type="text" name="userid" class="form-control input-login" value="<?=!empty($cookieUserid) ? $cookieUserid : ''?>"></dd>
            </dl>
            <dl>
              <dt>비밀번호</dt>
              <dd><input type="password" name="password" class="form-control input-login" value="<?=!empty($cookiePasswd) ? $cookiePasswd : ''?>"></dd>
            </dl>
            <label class="small pl-5"><input type="checkbox" name="save"> 아이디/비밀번호 저장</label>
          </form>
          <div class="error-message"></div>
        </div>
        <div class="modal-footer">
          <div class="modal-footer-left">
            <a href="/login/entry"><button type="button" class="btn btn-default">회원가입</button></a>
            <a href="/login/forgot"><button type="button" class="btn btn-secondary">아이디/비밀번호 찾기</button></a>
          </div>
          <div class="modal-footer-right">
            <button type="button" class="btn btn-default btn-login">로그인</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- FOOTER -->
  <footer class="p-3 text-center">
    Copyright &copy; <script>document.write(new Date().getFullYear());</script> <strong>SayHome</strong>. All Rights Reserved.
  </footer>
  <!-- /FOOTER -->

</body>
</html>
