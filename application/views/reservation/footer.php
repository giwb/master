<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <footer id="footer" class="subpage">
    <div class="container">
      <div class="copyright">
        Copyright &copy; 2010~<?=date("Y")?> <strong>경인웰빙</strong>.<span class="spbr"> All Rights Reserved.</span>
      </div>
    </div>
  </footer>

  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
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
          <button type="button" class="btn btn-primary btn-login">로그인</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
        </div>
      </div>
    </div>
  </div>

  <script src="/public/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/public/lib/easing/easing.min.js"></script>
  <script src="/public/lib/superfish/hoverIntent.js"></script>
  <script src="/public/lib/superfish/superfish.min.js"></script>
  <script src="/public/lib/wow/wow.min.js"></script>
  <script src="/public/lib/waypoints/waypoints.min.js"></script>
  <script src="/public/lib/counterup/counterup.min.js"></script>
  <script src="/public/lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="/public/lib/isotope/isotope.pkgd.min.js"></script>
  <script src="/public/lib/lightbox/js/lightbox.min.js"></script>
  <script src="/public/lib/touchSwipe/jquery.touchSwipe.min.js"></script>
  <script src="/public/js/main.js"></script>

</body>
</html>
