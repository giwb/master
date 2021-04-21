<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  $uri = $_SERVER['REQUEST_URI'];
  $uri_cut = explode('/', $uri);
?>

        <?php if ((!strstr($uri, 'login') && !strstr($uri, 'album')) || strstr($uri, 'album/best')): ?>
        <div class="col-xl-4 col-md-12 widget-column mt-0 pb-0">
          <section class="section">
            <h4 class="font-weight-bold"><strong>월간 일정</strong></h4>
            <hr class="text-default" style="margin-bottom: 33px;">
            <div class="card">
              <div class="view overlay">
                <div id="calendar"></div>
              </div>
            </div>
          </section>
          <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
              var calendarEl = document.getElementById('calendar');
              var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'ko',
                height: 'auto',
                displayEventTime: false,
                displayEventEnd: false,
                eventDisplay: 'block',
                headerToolbar: {
                  left: 'prev',
                  center: 'title',
                  right: 'next'
                },
                dayCellContent: function(e) {
                  e.dayNumberText = e.dayNumberText.replace('일','');
                },
                eventContent: function(e) {
                  return {
                    html: e.event.title.replace('\n','<br>')
                  }
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
                    start: new Date('<?=date('Y', $startDate)?>-<?=date('m', $startDate)?>-<?=date('d', $startDate)?>'),
                    end: new Date('<?=date('Y', $endDate)?>-<?=date('m', $endDate)?>-<?=date('d', $endDate)?>'),
                    url: 'javascript:;',
                    className: '<?=$value['class']?>'
                  },
                  <?php else: ?>
                  {
                    title: '<?=$value['status'] != STATUS_PLAN ? $value['starttime'] : "[계획]"?>\n<?=$value['mname']?>',
                    start: new Date('<?=date('Y', $startDate)?>-<?=date('m', $startDate)?>-<?=date('d', $startDate)?>'),
                    end: new Date('<?=date('Y', $endDate)?>-<?=date('m', $endDate)?>-<?=date('d', $endDate)?>'),
                    url: '<?=BASE_URL?>/reserve/list/<?=$value['idx']?>',
                    className: 'notice-status<?=$value['status']?>'
                  },
                  <?php
                      endif;
                    endforeach;
                  ?>
                ]
              });
              calendar.render();
            });
          </script>

          <!-- 내부 광고 -->
          <section class="section mt-4 mb-4">
            <div class="card">
              <?php //$banner = mt_rand(1, 2); if ($banner == 1): ?>
              <!--<a target="_blank" href="http://won04.co.kr"><img class="w-100" src="/public/images/banner_won04.png"></a>-->
              <?php //else: ?>
              <a target="_blank" href="https://sayhome.co.kr/?utm_source=giwb"><img class="w-100" src="/public/images/banner_sayhome.png"></a>
              <?php //endif; ?>
            </div>
          </section>

          <?php if ($uri == '/' || (empty($uri_cut[2]) && $uri_cut[1] == $view['url'])): // 안부 인사와 인증현황은 메인 페이지에서만 보이게 ?>
          <section class="section mt-5">
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
              <div class="row border-bottom no-gutters pt-3 pb-3 pl-3 pr-3">
                <div class="col-10 pl-0 pr-1"><textarea id="club-story-content" rows="3" class="form-control form-control-sm h-100"></textarea></div>
                <div class="col-2 pt-0 pl-0"><button type="button" class="btn btn-default btn-comment m-0 pt-4 pb-4 pl-3 pr-3 w-100 h-100">등록</button></div>
              </div>
              <?php endif; ?>
            </div>
          </section>

          <section class="section mt-5">
            <h4 class="row font-weight-bold">
              <div class="col-6"><strong>백산백소 인증현황</strong></div>
              <div class="col-6 text-right"><a href="<?=BASE_URL?>/club/auth" class="btn btn-default pt-2 pb-2 pl-4 pr-4 m-0">더 보기</a></div>
            </h4>
            <div class="card p-3">
              <?php foreach ($auth as $key => $value): ?>
              <div class="row no-gutters mt-2">
                <div class="col-1">
                  <?php if ($value['rank'] <= 5): ?><img src="/public/images/medal<?=$value['rank']?>.png">
                  <?php else: ?><?=$value['rank']?><?php endif; ?>
                </div>
                <div class="col-4"><?=$value['nickname']?>님</div>
                <div class="col-7 btn-open-auth" data-idx="<?=$key?>">
                  <div class="auth-progress-bar"><div id="medal<?=$key?>" class="auth-gauge" cnt="<?=$value['cnt']?>" max="<?=$auth[0]['cnt']?>"><?=$value['cnt']?>회</div></div>
                  <div class="auth-title d-none" data-idx="<?=$key?>"><?=$value['title']?></div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </section>
          <?php else: ?>
          <section class="section mt-5">
            <h4 class="font-weight-bold"><strong>현재 진행중인 여행</strong></h4>
            <hr class="text-default">
            <div class="card">
              <div class="view overlay pt-2 pb-3">
                <?php if (!empty($listNoticeFooter)): ?>
                <?php foreach ($listNoticeFooter as $key => $value): $week = calcWeek($value['startdate']); ?>
                <div class="row no-gutters align-items-center mt-2<?=$key != 0 ? ' pt-2' : ''?>">
                  <div class="col-4 pl-3 pr-2"><a href="<?=BASE_URL?>/reserve/list/<?=$value['idx']?>"><?php if (!empty($value['photo']) && file_exists(PHOTO_PATH . 'thumb_' . $value['photo'])): ?><img class="w-100 h-100 border" src="<?=PHOTO_URL . 'thumb_' . $value['photo']?>"><?php else: ?><img class="w-100 border" src="/public/images/nophoto_mid.png"><?php endif; ?></a></div>
                  <div class="col-8">
                    <a href="<?=BASE_URL?>/reserve/list/<?=$value['idx']?>" class="<?=$week == '일' ? 'text-giwbred' : 'text-giwbblue'?>"><strong><?=viewStatus($value['status'])?> <?=ksubstr($value['subject'], 20)?></strong></a><br>
                    <small><?=$value['startdate']?> (<?=$week?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원<br>
                    <i class="far fa-eye pr-1"></i>조회 <?=$value['refer']?>
                    <i class="far fa-comments pr-1 ml-2"></i>댓글 <?=cntReply($value['idx'])?>
                    <i class="far fa-calendar-check pr-1 ml-2"></i>예약 <?=cntRes($value['idx'])?></small>
                  </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?><div class="text-center pt-5 pb-5">등록된 여행 정보가 없습니다.</div>
                <?php endif; ?>
              </div>
            </div>
          </section>
          <?php endif; ?>

          <section class="section mt-4">
            <div class="card text-center p-2">
              <img class="d-none d-sm-block busmap" src="/public/images/busmap.png">
              <img class="d-block d-sm-none busmap" src="/public/images/busmap_320.png">
            </div>
          </section>

          <!-- 애드핏 -->
          <section class="section mt-4 mb-4">
            <div class="card">
              <ins class="kakao_ad_area" style="display:none;" data-ad-unit="DAN-nZvV4BAxj0QXpJeQ" data-ad-width="320" data-ad-height="100"></ins>
              <script type="text/javascript" src="//t1.daumcdn.net/kas/static/ba.min.js" async></script>
            </div>
          </section>

          <!-- 구글 광고 -->
          <section class="section">
            <div class="card text-center">
              <!-- GOOGLE ADSENSE -->
              <?php if (ENVIRONMENT == 'production'): ?>
              <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-2424708381875991" data-ad-slot="1285643193" data-ad-format="auto" data-full-width-responsive="true"></ins>
              <script> (adsbygoogle = window.adsbygoogle || []).push({}); </script>
              <?php endif; ?>
            </div>
          </section>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </main>

  <ul id="nav-footer">
    <li<?=strstr($uri, 'schedule') ? ' class="active"' : ''?>><a href="<?=BASE_URL?>/reserve/schedule"><i class="fa fa-calendar" aria-hidden="true"></i><br>일정</a></li>
    <?php if (!empty($listAbout[0]['idx'])): ?><li<?=strstr($uri, 'about') || strstr($uri, 'past') ? ' class="active"' : ''?>><a href="<?=BASE_URL?>/club/about/<?=$listAbout[0]['idx']?>"><i class="fas fa-chalkboard"></i><br>소개</a></li><?php endif; ?>
    <?php if ($view['idx'] == 1): ?><li<?=strstr($uri, 'auth') || strstr($uri, 'page') ? ' class="active"' : ''?>><a href="<?=BASE_URL?>/club/auth"><i class="fa fa-check-square" aria-hidden="true"></i><br>백산백소</a></li><?php endif; ?>
    <li<?=strstr($uri, 'album') || strstr($uri, 'search') ? ' class="active"' : ''?>><a href="<?=BASE_URL?>/album"><i class="fas fa-map-marked-alt"></i><br>여행기</a></li>
    <li<?=strstr($uri, 'ranking') ? ' class="active"' : ''?>><a href="<?=BASE_URL?>/club/ranking"><i class="fas fa-chart-line"></i><br>회원랭킹</a></li>
    <?php if ($view['idx'] == 1): ?><li<?=strstr($uri, 'shop') ? ' class="active"' : ''?>><a href="<?=BASE_URL?>/shop"><i class="fa fa-shopping-cart" aria-hidden="true"></i><br>용품샵</a></li><?php endif; ?>
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
          <form id="formLogin" method="post">
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
        <div class="border-top text-center pt-3 pb-3">
          <div>
            <button type="button" class="btn btn-<?=$view['main_color']?> btn-login">로그인</button>
          </div>
          <div>
            <a href="<?=BASE_URL?>/login/entry"><button type="button" class="btn btn-info pl-3 pr-3">회원가입</button></a>
            <a href="<?=BASE_URL?>/login/forgot"><button type="button" class="btn btn-secondary pl-3 pr-3">아이디/비밀번호 찾기</button></a>
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
          <p class="modal-message mt-2 mb-4"></p>
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

  <!-- Back to Top -->
  <a class="scroll-to-top rounded" href="javascript:;">
    <i class="fa fa-angle-up"></i>
  </a>

  <footer class="page-footer stylish-color-dark mt-4 pb-4">
    <main id="footer" class="row no-gutters white-text small align-items-center">
      <div class="col-sm-2 text-center"><img width="45" src="/public/images/icon.png" class="logo-img"><span class="logo">경인웰빙</span></div>
      <div class="col-sm-5 logo-text">
        사업자등록번호 : 568-45-00657 / 관광사업등록번호 : 제 2021-000001호<br>
        서울 금천구 가산디지털 1로 137, 19층 1901호 (가산동, IT 캐슬 2차)<br>
        입금계좌 : 국민은행 / 658101-01-783256 / 최병준(경인웰빙투어)<br>
        대표 : 최병준 / 개인정보보호책임자 : 최병성 (010-7271-3050)<br>
        Copyright© <script>document.write(new Date().getFullYear());</script> 경인웰빙투어, All Rights Reserved.
        <hr class="bg-secondary mt-2 mb-2">
        <a href="<?=BASE_URL?>/club/about/1">회사소개</a> | 
        <a href="<?=BASE_URL?>/club/page?type=agreement">이용약관</a> | 
        <a href="<?=BASE_URL?>/club/page?type=personal">개인정보 취급방침</a>
      </div>
      <div class="col-sm-5 text-right align-self-end mt-2">
        <a target="_blank" title="페이스북" href="https://www.facebook.com/giwb.kr"><img hspace="2" src="/public/images/icon_facebook.png"></a>
        <a target="_blank" title="인스타그램" href="https://www.instagram.com/giwbtour"><img hspace="2" src="/public/images/icon_instagram.png"></a>
        <a target="_blank" title="트위터" href="https://twitter.com/giwb_alpine"><img hspace="2" src="/public/images/icon_twitter.png"></a>
        <a target="_blank" title="유튜브" href="https://www.youtube.com/channel/UCH35r9R3xNjqxrm8CexwS-g"><img hspace="2" src="/public/images/icon_youtube.png"></a>
        <a target="_blank" title="다음카페" href="https://cafe.daum.net/giwb"><img hspace="2" src="/public/images/icon_cafe.png"></a>
      </div>
    </main>
  </footer>

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
