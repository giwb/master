<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="col-xl-4 col-md-12 widget-column mt-0">
          <section class="section">
            <h4 class="font-weight-bold"><strong>월간 일정</strong></h4>
            <hr class="text-default" style="margin-bottom: 33px;">
            <div class="card">
              <div class="view overlay">
                <div id="calendar"></div>
              </div>
            </div>
          </section>

          <section class="section mt-4">
            <h4 class="row font-weight-bold">
              <div class="col-6"><strong>안부 인사</strong></div>
              <div class="col-6 text-right"><!--<a href="javascript:;" class="btn btn-default pt-2 pb-2 pl-4 pr-4 m-0">더 보기</a>--></div>
            </h4>
            <div class="card">
              <div id="club-story">
              <?php foreach($listStory as $value): ?>
                <div class="row pl-3 pr-3 pt-3">
                  <div class="col-2"><img src="<?=$value['avatar']?>" class="story-photo"></div>
                  <div class="col-10 pl-0 text-justify"><b><?=$value['user_nickname']?></b> <?=$value['content']?> <span class="small grey-text"><?=calcStoryTime($value['created_at'])?></span></div>
                </div>
              <?php endforeach; ?>
              </div>
              <?php if (!empty($userData['idx'])): ?>
              <div class="row border-bottom no-gutters pt-3 pb-2 pl-4 pr-3">
                <div class="col-10 pl-0 pr-1"><textarea id="club-story-content" rows="3" class="form-control form-control-sm"></textarea></div>
                <div class="col-2 pt-0 pl-0"><button type="button" class="btn btn-default btn-comment pt-4 pb-4 pl-3 pr-3 w-100">등록</button></div>
              </div>
              <?php endif; ?>
            </div>
          </section>

          <section class="section mt-4">
            <h4 class="row font-weight-bold">
              <div class="col-6"><strong>백산백소 인증현황</strong></div>
              <div class="col-6 text-right"><!--<a href="javascript:;" class="btn btn-default pt-2 pb-2 pl-4 pr-4 m-0">더 보기</a>--></div>
            </h4>
            <div class="card pb-3">
              <div class="row pl-3 pr-3 pt-3">
                <div class="col-4"><img src="/public/images/medal1.png" align="left"> 스마일찐이님</div>
                <div class="col-8 pl-0"><div class="auth-progress-bar"><div id="medal1" class="auth-gauge" cnt="42">42회</div></div></div>
              </div>
              <div class="row pl-3 pr-3 pt-2">
                <div class="col-4"><img src="/public/images/medal2.png" align="left"> 미운사랑님</div>
                <div class="col-8 pl-0"><div class="auth-progress-bar"><div id="medal2" class="auth-gauge" cnt="37">37회</div></div></div>
              </div>
              <div class="row pl-3 pr-3 pt-2">
                <div class="col-4"><img src="/public/images/medal3.png" align="left"> 맑음님</div>
                <div class="col-8 pl-0"><div class="auth-progress-bar"><div id="medal3" class="auth-gauge" cnt="36">36회</div></div></div>
              </div>
              <div class="row pl-3 pr-3 pt-2">
                <div class="col-4"><img src="/public/images/medal4.png" align="left"> 야나두님</div>
                <div class="col-8 pl-0"><div class="auth-progress-bar"><div id="medal4" class="auth-gauge" cnt="32">32회</div></div></div>
              </div>
              <div class="row pl-3 pr-3 pt-2">
                <div class="col-4"><img src="/public/images/medal5.png" align="left"> 명산님</div>
                <div class="col-8 pl-0"><div class="auth-progress-bar"><div id="medal5" class="auth-gauge" cnt="27">27회</div></div></div>
              </div>
            </div>
          </section>

          <!-- 애드핏 -->
          <section class="section mb-4">
            <div class="card">
              <ins class="kakao_ad_area" style="display:none;" data-ad-unit    = "DAN-CMBlCe8nHsLwMdHn" data-ad-width   = "320" data-ad-height  = "100"></ins>
              <script type="text/javascript" src="//t1.daumcdn.net/kas/static/ba.min.js" async></script>
            </div>
          </section>

          <!-- 구글 광고 -->
          <section class="section">
            <div class="card text-center">
              <!-- GOOGLE ADSENSE -->
              <?php if (ENVIRONMENT == 'production' && $_SERVER['REMOTE_ADDR'] != '49.166.0.82'): ?>
              <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-2424708381875991" data-ad-slot="1285643193" data-ad-format="auto" data-full-width-responsive="true"></ins>
              <script> (adsbygoogle = window.adsbygoogle || []).push({}); </script>
              <?php endif; ?>
            </div>
          </section>
        </div>
      </div>
    </div>
  </main>

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
            <a href="<?=BASE_URL?>/login/check"><button type="button" class="btn btn-info pl-3 pr-3">회원가입</button></a>
            <a href="<?=BASE_URL?>/login/forgot"><button type="button" class="btn btn-secondary pl-3 pr-3">아이디/비밀번호 찾기</button></a>
          </div>
          <div class="modal-footer-right">
            <button type="button" class="btn btn-<?=$view['main_color']?> btn-login pl-3 pr-3">로그인</button>
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
          <!--<textarea id="club-story-content" rows="10" class="form-control" placeholder="당신의 이야기를 들려주세요~"></textarea>-->
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

  <!-- Back to Top -->
  <a class="scroll-to-top rounded" href="javascript:;">
    <i class="fa fa-angle-up"></i>
  </a>

  <footer class="page-footer stylish-color-dark mt-4 text-center p-4">
    <div class="white-text">
      Copyright© 2021 경인웰빙투어, All Rights Reserved.<br>
      <a href="<?=BASE_URL?>/club/page?type=agreement">이용약관</a> |
      <a href="<?=BASE_URL?>/club/page?type=personal">개인정보 취급방침</a>
    </div>
  </footer>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#calendar').fullCalendar({
        header: {
          left: 'prev',
          center: 'title',
          right: 'next'
        },
        titleFormat: {
          month: 'yyyy년 MMMM',
          week: "yyyy년 MMMM",
          day: 'yyyy년 MMMM'
        },
        events: [
          <?php
            foreach ($listNoticeCalendar as $value):
              $startDate = strtotime($value['startdate']);
              $value['mname'] = htmlspecialchars_decode($value['mname']);
              if (!empty($value['enddate'])): $endDate = calcEndDate($value['startdate'], $value['enddate']);
              else: $endDate = calcEndDate($value['startdate'], $value['schedule']);
              endif;
              if ($value['status'] == 'schedule'):
          ?>
          {
            title: '<?=$value['mname']?>',
            start: new Date('<?=date('Y', $startDate)?>-<?=date('m', $startDate)?>-<?=date('d', $startDate)?>T00:00:00'),
            end: new Date('<?=date('Y', $endDate)?>-<?=date('m', $endDate)?>-<?=date('d', $endDate)?>T23:59:59'),
            url: 'javascript:;',
            className: '<?=$value['class']?>'
          },
          <?php
              else:
                if ($value['status'] >= 1):
                  $url = BASE_URL . '/reserve/index/' . $value['idx'];
                else:
                  $url = 'javascript:;';
                endif;
          ?>
          {
            title: '<?=$value['status'] != STATUS_PLAN ? $value['starttime'] . "\\n" : "[계획]\\n"?><?=$value['mname']?>',
            start: new Date('<?=date('Y', $startDate)?>-<?=date('m', $startDate)?>-<?=date('d', $startDate)?>T00:00:01'),
            end: new Date('<?=date('Y', $endDate)?>-<?=date('m', $endDate)?>-<?=date('d', $endDate)?>T23:59:59'),
            url: '<?=$url?>',
            className: 'notice-status<?=$value['status']?>'
          },
          <?php
              endif;
            endforeach;
          ?>
        ]
      });

      // 백산백소 인증 프로그래스바
      $('.auth-gauge').each(function(i) {
        var elemId = $(this).attr('id');
        var maxWidth = $(this).attr('cnt');
        move(i, elemId, maxWidth);
      });
      function move(i, elemId, maxWidth) {
        i = 1;
        var elem = document.getElementById(elemId);
        var width = 1;
        var id = setInterval(frame, 10);
        function frame() {
          if (width >= Number(maxWidth * 2)) {
            clearInterval(id);
            i = 0;
          } else {
            width++;
            elem.style.width = width + "%";
          }
        }
      }
    });
  </script>

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
