<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <input type="hidden" name="base_url" value="<?=base_url()?>">

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
          <form id="loginForm" method="post">
          <dl>
            <dt>아이디</dt>
            <dd><input type="text" name="userid" class="input-login"></dd>
          </dl>
          <dl>
            <dt>비밀번호</dt>
            <dd><input type="password" name="password" class="input-login"></dd>
          </dl>
          </form>
          <div class="error-message"></div>
        </div>
        <div class="modal-footer">
          <div class="modal-footer-left">
            <a href="<?=base_url()?>member/entry"><button type="button" class="btn btn-primary">회원가입</button></a>
            <a href="<?=base_url()?>member/forgot"><button type="button" class="btn btn-secondary">아이디/비밀번호 찾기</button></a>
          </div>
          <div class="modal-footer-right">
            <button type="button" class="btn btn-primary btn-login">로그인</button>
          </div>
        </div>
      </div>
    </div>
  </div>

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
          <button type="button" class="btn btn-primary btn-list">목록으로</button>
          <button type="button" class="btn btn-primary btn-refresh">새로고침</button>
          <button type="button" class="btn btn-primary btn-delete">삭제합니다</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Photo Modal -->
  <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="smallmodalLabel">사진 미리보기</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <p class="modal-message"></p>
        </div>
        <div class="modal-footer">
          <input type="hidden" class="photo" value="">
          <button type="button" class="btn btn-primary btn-list">목록으로</button>
          <button type="button" class="btn btn-primary btn-refresh">새로고침</button>
          <button type="button" class="btn btn-primary btn-delete">삭제합니다</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <footer id="footer">
    <div class="text-center">
      Copyright &copy; <script>document.write(new Date().getFullYear());</script> <strong>SayHome</strong>. All Rights Reserved.
    </div>
  </footer>
  <!-- /FOOTER -->

</body>
</html>
