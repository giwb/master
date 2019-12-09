<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-right">
        <h3><i class="fa fa-calendar" aria-hidden="true"></i> 현재 진행중인 산행</h3>
        <div class="list-schedule">
<?php foreach ($listNotice as $value): ?>
          <a href="<?=base_url()?>reserve/<?=$value['club_idx']?>?n=<?=$value['idx']?>"><?=viewStatus($value['status'])?> <strong><?=$value['subject']?></strong><br><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 / <?=cntRes($value['idx'])?>명</a>
<?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <input type="hidden" name="baseUrl" value="<?=base_url()?>">
  <input type="hidden" name="clubIdx" value="<?=!empty($view['idx']) ? $view['idx'] : ''?>">
  <input type="hidden" name="userIdx" value="<?=!empty($userData['idx']) ? $userData['idx'] : ''?>">
  <input type="hidden" name="redirectUrl" value="<?=!empty($_SERVER['REQUEST_URI']) ? base_url() . substr($_SERVER['REQUEST_URI'], 1) : ''?>">

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
            <dd><input type="text" name="userid" class="form-control input-login"></dd>
          </dl>
          <dl>
            <dt>비밀번호</dt>
            <dd><input type="password" name="password" class="form-control input-login"></dd>
          </dl>
          </form>
          <div class="error-message"></div>
        </div>
        <div class="modal-footer">
          <div class="modal-footer-left">
            <a href="<?=base_url()?>login/entry/<?=$view['idx']?>"><button type="button" class="btn btn-primary">회원가입</button></a>
            <a href="<?=base_url()?>login/forgot/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">아이디/비밀번호 찾기</button></a>
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
          <input type="hidden" name="action" value="">
          <input type="hidden" name="deleteIdx" value="">
          <a href="<?=base_url()?><?=$view['idx']?>"><button type="button" class="btn btn-primary btn-top">메인 화면으로</button></a>
          <button type="button" class="btn btn-primary btn-list">목록으로</button>
          <button type="button" class="btn btn-primary btn-refresh">새로고침</button>
          <button type="button" class="btn btn-primary btn-delete">삭제합니다</button>
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
          <input type="hidden" class="photo" value="">
          <button type="button" class="btn btn-primary btn-list">목록으로</button>
          <button type="button" class="btn btn-primary btn-refresh">새로고침</button>
          <button type="button" class="btn btn-primary btn-delete">삭제합니다</button>
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

  <!-- 예약좌석 취소 -->
  <div class="modal fade" id="reserveCancelModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="smallmodalLabel">예약좌석 취소</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <p class="modal-message">정말로 취소하시겠습니까?</p>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="resIdx">
          <button type="button" class="btn btn-primary btn-reserve-cancel-confirm">승인</button>
          <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 결제정보 작성 -->
  <div class="modal fade" id="reservePaymentModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="smallmodalLabel">결제정보 입력</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <dl>
            <dt>합계금액</dt>
            <dd><span class="reserveCost"></span><input type="hidden" name="reserveCost"></dd>
          </dl>
          <dl>
            <dt>결제금액</dt>
            <dd><strong class="paymentCost text-danger"></strong><input type="hidden" name="paymentCost"></dd>
          </dl>
          <dl>
            <dt>포인트 사용</dt>
            <dd>
              총 <span class="myPoint"><?=number_format($userData['point'])?></span> 포인트 중
              <input type="number" name="usingPoint" class="using-point form-control form-control-sm"> 포인트 사용<br>
              <label class="mb-0"><input type="checkbox" class="using-point-all"> 포인트 전액 사용</label>
              <input type="hidden" name="userPoint" value="<?=$userData['point']?>">
            </dd>
          </dl>
          <dl>
            <dt>입금은행</dt>
            <dd>국민은행 / 288001-04-154630 / 경인웰빙산악회 (김영미)</dd>
          </dl>
          <dl>
            <dt>입금자명</dt>
            <dd><input type="text" name="depositName" class="form-control form-control-sm"></dd>
          </dl>
        </div>
        <div class="error-message"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-reserve-payment">입력완료</button>
          <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
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

  <script type="text/javascript" src="/public/vendors/chart.js/dist/Chart.bundle.min.js"></script>

  <?php if (ENVIRONMENT == 'production'): ?>
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
