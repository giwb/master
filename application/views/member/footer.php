<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  </section>

  <ul id="nav-footer">
    <li><a href="<?=BASE_URL?>/reserve/schedule"><i class="fa fa-calendar" aria-hidden="true"></i><br>일정</a></li>
    <li><a href="<?=BASE_URL?>/shop"><i class="fa fa-shopping-cart" aria-hidden="true"></i><br>구매</a></li>
    <li><a href="<?=BASE_URL?>/album"><i class="fa fa-camera-retro" aria-hidden="true"></i><br>사진</a></li>
    <li><a href="<?=BASE_URL?>/club/about?p=top"><i class="fa fa-sitemap" aria-hidden="true"></i><br>소개</a></li>
    <?php if (!empty($userData['idx'])): ?>
    <li><a href="<?=BASE_URL?>/member"><i class="fa fa-user-circle" aria-hidden="true"></i><br>내정보</a></li>
    <?php else: ?>
    <li><a href="javascript:;" class="login-popup"><i class="fa fa-user-circle" aria-hidden="true"></i><br>로그인</a></li>
    <?php endif; ?>
  </ul>

  <input type="hidden" name="baseUrl" value="<?=BASE_URL?>">
  <input type="hidden" name="clubIdx" value="<?=!empty($view['idx']) ? $view['idx'] : ''?>">
  <input type="hidden" name="userIdx" value="<?=!empty($userData['idx']) ? $userData['idx'] : ''?>">
  <input type="hidden" name="redirectUrl" value="<?=$redirectUrl?>">

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
            <a href="<?=BASE_URL?>/login/check"><button type="button" class="btn btn-<?=$view['main_color']?>">회원가입</button></a>
            <a href="<?=BASE_URL?>/login/forgot"><button type="button" class="btn btn-secondary">아이디/비밀번호 찾기</button></a>
          </div>
          <div class="modal-footer-right">
            <button type="button" class="btn btn-<?=$view['main_color']?> btn-login">로그인</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

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
          <p class="modal-message mb-4"></p>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="action" value="">
          <input type="hidden" name="deleteIdx" value="">
          <a href="<?=BASE_URL?>"><button type="button" class="btn btn-<?=$view['main_color']?> btn-top">메인 화면으로</button></a>
          <button type="button" class="btn btn-<?=$view['main_color']?> btn-list">목록으로</button>
          <button type="button" class="btn btn-<?=$view['main_color']?> btn-refresh">새로고침</button>
          <button type="button" class="btn btn-<?=$view['main_color']?> btn-delete">삭제합니다</button>
          <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
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
          <input type="hidden" name="photo_name" value="">
          <button type="button" class="btn btn-<?=$view['main_color']?> btn-photo-delete">삭제합니다</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Share Modal -->
  <div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="smallmodalLabel">공유하기</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Cancel -->
  <div class="modal fade" id="reserveCancelModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="smallmodalLabel">취소</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <p class="modal-message"></p>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="resIdx">
          <input type="hidden" name="resType">
          <button type="button" class="btn btn-<?=$view['main_color']?> btn-reserve-cancel-confirm">승인</button>
          <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Story -->
  <div class="modal fade" id="storyModal" tabindex="-1" role="dialog" aria-labelledby="storyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="smallmodalLabel">글쓰기</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="your-story-form" method="post" action="/story/insert">
        <input type="hidden" name="clubIdx" value="<?=!empty($view['idx']) ? $view['idx'] : ''?>">
        <input type="hidden" name="userIdx" value="<?=!empty($userData['idx']) ? $userData['idx'] : ''?>">
        <input type="hidden" name="page" value="story">
        <div class="modal-body text-center">
          <textarea id="club-story-content" rows="10" class="form-control" placeholder="당신의 이야기를 들려주세요~"></textarea>
          <div class="error-message"></div>
        </div>
        <div class="area-photo"></div>
        <div class="row align-items-center pl-3 pr-3">
          <div class="col-5 pr-0">
            <input type="file" class="file d-none">
            <button type="button" class="btn btn-photo"><i class="fa fa-camera" aria-hidden="true"></i> <span class="text">사진추가</span></button>
          </div>
          <div class="col-7 text-right">
            <button type="button" class="btn btn-<?=$view['main_color']?> btn-post">전송</button>
            <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Photo Swipe -->
  <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>
    <div class="pswp__scroll-wrap">
      <div class="pswp__container">
        <div class="pswp__item"></div>
        <div class="pswp__item"></div>
        <div class="pswp__item"></div>
      </div>
      <div class="pswp__ui pswp__ui--hidden">
        <div class="pswp__top-bar">
          <div class="pswp__counter"></div>
          <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
          <button class="pswp__button pswp__button--share" title="Share"></button>
          <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
          <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
          <div class="pswp__preloader">
            <div class="pswp__preloader__icn">
              <div class="pswp__preloader__cut">
                <div class="pswp__preloader__donut"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
          <div class="pswp__share-tooltip"></div> 
        </div>
        <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
        <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
        <div class="pswp__caption">
          <div class="pswp__caption__center"></div>
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

  <!-- Back to Top -->
  <a class="scroll-to-top rounded" href="javascript:;">
    <i class="fa fa-angle-up"></i>
  </a>

  <script src="/public/vendors/chart.js/dist/Chart.bundle.min.js" type="text/javascript"></script>

  <?php if (ENVIRONMENT == 'production' && $_SERVER['REMOTE_ADDR'] != '49.166.0.82'): ?>
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-80490919-1', 'auto');
    ga('send', 'pageview');
  </script>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-141316550-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-141316550-1');
  </script>
  <?php endif; ?>

</body>
</html>
