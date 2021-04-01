<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  $uri = $_SERVER['REQUEST_URI'];
?>

    <?php if (!strstr($uri, 'login')): ?>
    <div class="col-xl-4 col-md-12 widget-column mt-0">
      <section class="section mb-4">
        <h4 class="font-weight-bold"><strong>
          <?php if (strstr($_SERVER['REQUEST_URI'], 'place')): ?>
          여행정보 분류
          <?php elseif (strstr($_SERVER['REQUEST_URI'], 'schedule')): ?>
          월간 여행일정
          <?php else: ?>
          분류별 기사
          <?php endif; ?>
        </strong></h4>
        <hr class="red">
        <?php if (strstr($_SERVER['REQUEST_URI'], 'schedule')): ?>
        <div class="card">
          <div class="view overlay">
            <div id="calendar"></div>
          </div>
        </div>
        <link href="/public/css/fullcalendar.css" rel="stylesheet">
        <link href="/public/css/fullcalendar.print.css" rel="stylesheet">
        <script src="/public/js/fullcalendar.js" type="text/javascript"></script>
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
                <?php foreach ($listNoticeSchedule as $value): $startDate = strtotime($value['startdate']); ?>
                {
                  title: '<?=$value['count']?>',
                  start: new Date('<?=date('Y', $startDate)?>-<?=date('m', $startDate)?>-<?=date('d', $startDate)?>T00:00:00'),
                  end: new Date('<?=date('Y', $startDate)?>-<?=date('m', $startDate)?>-<?=date('d', $startDate)?>T23:59:59'),
                  url: '<?=base_url()?>schedule?sdate=<?=$startDate?>',
                  className: 'schedule-count'
                },
                <?php endforeach; ?>
              ]
            });
          });
        </script>
        <?php elseif (strstr($_SERVER['REQUEST_URI'], 'place')): ?>
        <ul class="list-group z-depth-1 mt-4 mb-5">
          <?php foreach ($listPlaceCategory as $value): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <a href="<?=base_url()?>place?code=<?=$value['code']?>"><?=$value['name']?></a>
            <span class="badge badge-danger badge-pill"><?=$value['cnt']?>건</span>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <ul class="list-group z-depth-1 mt-4 mb-5">
          <?php foreach ($listArticleCategory as $value): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <a href="<?=base_url()?>search/?code=<?=$value['code']?>"><?=$value['name']?></a>
            <span class="badge badge-danger badge-pill"><?=$value['cnt']?>건</span>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </section>

      <section class="section mb-5">
        <div class="card text-center">
          <img src="/public/images/tripkorea/ad.jpg">
          <!--
          <ins class="kakao_ad_area" style="display: none;" data-ad-unit="DAN-vmKgrkeNJQjRcNJm" data-ad-width="320" data-ad-height="100"></ins>
          <script type="text/javascript" src="//t1.daumcdn.net/kas/static/ba.min.js" async></script>-->
        </div>
      </section>
<!--
      <section class="section mb-5">
        <h4 class="row font-weight-bold">
          <div class="col-6"><strong>월간 산악회 랭킹</strong></div>
          <div class="col-6 text-right"><a href="javascript:;" class="btn btn-default pt-2 pb-2 pl-4 pr-4 m-0">더 보기</a></div>
        </h4>
        <div class="card pb-3">
          <div class="row pl-3 pr-3 pt-3">
            <div class="col-4"><img src="/public/images/tripkorea/medal1.png" align="left"> 경인웰빙</div>
            <div class="col-8 pl-0"><img width="68%" height="10" src="/public/images/tripkorea/dot.png"> 192점</div>
          </div>
          <div class="row pl-3 pr-3 pt-2">
            <div class="col-4"><img src="/public/images/tripkorea/medal2.png" align="left"> 한국여행</div>
            <div class="col-8 pl-0"><img width="60%" height="10" src="/public/images/tripkorea/dot.png"> 154점</div>
          </div>
          <div class="row pl-3 pr-3 pt-2">
            <div class="col-4"><img src="/public/images/tripkorea/medal3.png" align="left"> 좋은사람들</div>
            <div class="col-8 pl-0"><img width="55%" height="10" src="/public/images/tripkorea/dot.png"> 148점</div>
          </div>
          <div class="row pl-3 pr-3 pt-2">
            <div class="col-4"><img src="/public/images/tripkorea/medal4.png" align="left"> 나쁜사람들</div>
            <div class="col-8 pl-0"><img width="32%" height="10" src="/public/images/tripkorea/dot.png"> 58점</div>
          </div>
          <div class="row pl-3 pr-3 pt-2">
            <div class="col-4"><img src="/public/images/tripkorea/medal5.png" align="left"> 보통사람들</div>
            <div class="col-8 pl-0"><img width="10%" height="10" src="/public/images/tripkorea/dot.png"> 27점</div>
          </div>
        </div>
      </section>
-->
      <section class="section widget-content">
        <h4 class="row font-weight-bold">
          <div class="col-6"><strong>여행일정</strong></div>
          <div class="col-6 text-right"><a href="<?=base_url()?>schedule" class="btn btn-default pt-2 pb-2 pl-4 pr-4 m-0">더 보기</a></div>
        </h4>
        <hr class="red mb-4">
        <div class="card card-body">
          <?php $max = count($listFooterNotice); foreach ($listFooterNotice as $key => $value): ?>
          <div class="single-post<?=$max-1 > $key ? ' pb-4 mb-4' : ''?>">
            <h6 class="mt-0 mb-3"><a target="_blank" href="<?=$value['url']?>"><strong>[<?=$value['club_name']?>] <?=$value['subject']?></strong></a></h6>
            <div class="row">
              <div class="col-4">
                <div class="view overlay">
                  <img width="126" height="84" src="<?=$value['photo']?>">
                  <a href="<?=base_url()?>schedule">
                    <div class="mask waves-light"></div>
                  </a>
                </div>
              </div>
              <div class="col-8 pl-0 pr-0">
                <div class="post-data">
                  <p class="font-small mb-0">
                    ・일시 : <?=$value['startdate']?> <?=$value['starttime']?><br>
                    ・지역 : <?php foreach ($value['sido'] as $key => $area): if ($key != 0): ?>, <?php endif; ?><?=$area?><?=!empty($area['gugun'][$key]) ? $area['gugun'][$key] : ''?><?php endforeach; ?><br>
                    ・요금 : <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원<br>
                    ・예약 : <?=cntRes($value['idx'])?>명
                  </p>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </section>
    </div>
    <?php endif; ?>
  </div>
</main>

<footer class="page-footer stylish-color-dark mt-4 text-center p-4">
  <div class="white-text">
    Copyright &copy; <script>document.write(new Date().getFullYear());</script> <strong>한국여행</strong>. All Rights Reserved.<br>
    <div class="small mt-1">
      <a href="#">이용약관</a> |
      <a href="#">개인정보 취급방침</a>
    </div>
  </div>
</footer>
<input type="hidden" name="redirectUrl" value="<?=base_url()?>">

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
        <button type="button" class="btn btn-danger btn-login">로그인</button><br>
        <a href="<?=base_url()?>login/entry"><button type="button" class="btn btn-info">회원가입</button></a>
        <a href="<?=base_url()?>login/forgot"><button type="button" class="btn btn-secondary">아이디/비밀번호 찾기</button></a>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if (ENVIRONMENT == 'production' && $_SERVER['REMOTE_ADDR'] != '49.166.0.82'): ?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-GWVDQCB17D"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-GWVDQCB17D');
</script>
<?php endif; ?>

</body>
</html>
