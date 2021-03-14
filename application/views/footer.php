<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="col-xl-4 col-md-12 widget-column mt-0">
        <section class="section mb-4">
          <h4 class="font-weight-bold"><strong>
            <?php if (strstr($_SERVER['REQUEST_URI'], 'place')): ?>
            여행정보 분류
            <?php else: ?>
            분류별 기사
            <?php endif; ?>
          </strong></h4>
          <hr class="red">
          <ul class="list-group z-depth-1 mt-4 mb-5">
            <?php foreach ($listArticleCategory as $value): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <a href="/search/?code=<?=$value['code']?>"><?=$value['name']?></a>
              <span class="badge badge-danger badge-pill"><?=$value['cnt']?>건</span>
            </li>
            <?php endforeach; ?>
          </ul>
        </section>

        <section class="section mb-5">
          <div class="card text-center">
            <img src="/public/images/tripkorea/ad.jpg">
            <!--
            <ins class="kakao_ad_area" style="display: none;" data-ad-unit="DAN-vmKgrkeNJQjRcNJm" data-ad-width="320" data-ad-height="100"></ins>
            <script type="text/javascript" src="//t1.daumcdn.net/kas/static/ba.min.js" async></script>-->
          </div>
        </section>

        <section class="section mb-5">
          <h4 class="row font-weight-bold">
            <div class="col-6"><strong>월간 산악회 랭킹</strong></div>
            <div class="col-6 text-right"><!--<a href="javascript:;" class="btn btn-default pt-2 pb-2 pl-4 pr-4 m-0">더 보기</a>--></div>
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

        <section class="section widget-content">
          <h4 class="row font-weight-bold">
            <div class="col-6"><strong>산행 정보</strong></div>
            <div class="col-6 text-right"><a href="/list" class="btn btn-default pt-2 pb-2 pl-4 pr-4 m-0">더 보기</a></div>
          </h4>
          <hr class="red mb-4">
          <div class="card card-body pb-5">
            <div class="single-post pb-4 mb-4">
              <h6 class="mt-0 mb-3"><a href="/list"><strong>[경인웰빙] 태백산 장군봉 눈꽃</strong></a></h6>
              <div class="row">
                <div class="col-4">
                  <div class="view overlay">
                    <img width="126" height="84" src="https://giwb.kr/public/uploads/editor/161248371254205.jpg">
                    <a href="/list">
                      <div class="mask waves-light"></div>
                    </a>
                  </div>
                </div>
                <div class="col-8 pl-0 pr-0">
                  <div class="post-data">
                    <p class="font-small mb-0">
                      ・일시 : 2021-02-07 (일) 06:00<br>
                      ・요금 : 31,000원 / 1인우등 41,000원<br>
                      ・거리 : 산행 8.4km + 도로 1.0km / 약 5시간<br>
                      ・예약 : 32명<br>
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="single-post pb-4 mb-4">
              <h6 class="mt-0 mb-3"><a href="/list"><strong>[한국여행] 오대산 비로봉 & 두로령 옛길</strong></a></h6>
              <div class="row">
                <div class="col-4">
                  <div class="view overlay">
                    <img width="126" height="84" src="https://giwb.kr/public/uploads/editor/161248267682782.jpg">
                    <a href="/list">
                      <div class="mask waves-light"></div>
                    </a>
                  </div>
                </div>
                <div class="col-8 pl-0 pr-0">
                  <div class="post-data">
                    <p class="font-small mb-0">
                      ・일시 : 2021-02-06 (토) 06:30<br>
                      ・요금 : 31,000원 / 1인우등 41,000원<br>
                      ・거리 : 임도포함 13.8km / 약 5~6시간 소요<br>
                      ・예약 : 29명<br>
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="single-post pb-4 mb-4">
              <h6 class="mt-0 mb-3"><a href="/list"><strong>덕유산 향적봉 눈꽃산행</strong></a></h6>
              <div class="row">
                <div class="col-4">
                  <div class="view overlay">
                    <img width="126" height="84" src="https://giwb.kr/public/uploads/editor/161050582585413.jpg">
                    <a href="/list">
                      <div class="mask waves-light"></div>
                    </a>
                  </div>
                </div>
                <div class="col-8 pl-0 pr-0">
                  <div class="post-data">
                    <p class="font-small mb-0">
                      ・일시 : 2021-01-31 (일) 06:00<br>
                      ・요금 : 32,000원 / 1인우등 42,000원<br>
                      ・거리 : 산행 9.1km + 곤돌라 2.7km / 약 5~6시간<br>
                      ・예약 : 53명<br>
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="single-post pb-4 mb-4">
              <h6 class="mt-0 mb-3"><a href="/list"><strong>소백산 비로봉 칼바람 눈꽃산행</strong></a></h6>
              <div class="row">
                <div class="col-4">
                  <div class="view overlay">
                    <img width="126" height="84" src="https://giwb.kr/public/uploads/editor/161049859367645.jpg">
                    <a href="/list">
                      <div class="mask waves-light"></div>
                    </a>
                  </div>
                </div>
                <div class="col-8 pl-0 pr-0">
                  <div class="post-data">
                    <p class="font-small mb-0">
                      ・일시 : 2021-01-30 (토) 06:30<br>
                      ・요금 : 29,000원 / 1인우등 39,000원<br>
                      ・거리 : 12.5km / 약 6시간 소요<br>
                      ・예약 : 55명<br>
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="single-post">
              <h6 class="mt-0 mb-3"><a href="/list"><strong>계방산 눈꽃산행</strong></a></h6>
              <div class="row">
                <div class="col-4">
                  <div class="view overlay">
                    <img width="126" height="84" src="https://giwb.kr/public/uploads/editor/161049470999541.jpg">
                    <a href="/list">
                      <div class="mask waves-light"></div>
                    </a>
                  </div>
                </div>
                <div class="col-8 pl-0 pr-0">
                  <div class="post-data">
                    <p class="font-small mb-0">
                      ・일시 : 2021-01-23 (토) 06:30<br>
                      ・요금 : 29,000원 / 1인우등 39,000원<br>
                      ・거리 : 11.7km / 약 5시간<br>
                      ・예약 : 33명<br>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
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
