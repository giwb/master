<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    </div>
    <div class="club-right mb-5 pb-5">
      <h3><i class="fas fa-calendar-alt" aria-hidden="true"></i> 현재 진행중인 산행</h3>
      <div class="list-schedule">
        <?php if (empty($listNotice)): ?>
        <div class="text-center border-bottom mb-3 pt-5 pb-5">등록된 산행이 없습니다.</div>
        <?php else: foreach ($listNotice as $value): ?>
          <a href="<?=BASE_URL?>/admin/main_view_progress/<?=$value['idx']?>">
          <?php if (!empty($value['photo']) && file_exists(PHOTO_PATH . 'thumb_' . $value['photo'])): ?>
          <img class="notice-photo" align="left" src="<?=PHOTO_URL . 'thumb_' . $value['photo']?>">
          <?php else: ?>
          <img class="notice-photo" align="left" src="/public/images/nophoto.png">
          <?php endif; ?>
          <?=viewStatus($value['status'])?> <strong><?=$value['subject']?></strong><br><small><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 / <?=cntRes($value['idx'])?>명</small>
          <?php endforeach; ?>
          </a>
        <?php endif; ?>
      </div>
      <h3><i class="fas fa-reply" aria-hidden="true"></i> 최신 댓글</h3>
      <div class="list-schedule list-reply">
        <?php if (empty($listFooterReply)): ?>
        <div class="text-center border-bottom mb-3 pt-5 pb-5">등록된 댓글이 없습니다.</div>
        <?php else: foreach ($listFooterReply as $value): ?>
        <a href="<?=$value['url']?>"><span class="content"><?=ksubstr($value['content'], 35)?></span><br><?=$value['nickname']?> · <?=calcStoryTime($value['created_at'])?></a>
        <?php endforeach; endif; ?>
      </div>
      <!-- GOOGLE ADSENSE -->
      <?php if (ENVIRONMENT == 'production' && $_SERVER['REMOTE_ADDR'] != '49.166.0.82'): ?>
      <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-2424708381875991" data-ad-slot="1285643193" data-ad-format="auto" data-full-width-responsive="true"></ins>
      <script> (adsbygoogle = window.adsbygoogle || []).push({}); </script>
      <?php endif; ?>
    </div>
  </section>

  <ul id="nav-footer">
    <li><a href="<?=BASE_URL?>/admin/main_list_progress"><i class="fas fa-mountain" aria-hidden="true"></i><br>산행</a></li>
    <li><a href="<?=BASE_URL?>/ShopAdmin/order"><i class="fas fa-shopping-cart" aria-hidden="true"></i><br>구매</a></li>
    <li><a href="<?=BASE_URL?>/admin/member_list"><i class="fas fa-users" aria-hidden="true"></i><br>회원</a></li>
    <li><a href="<?=BASE_URL?>/admin/log_user"><i class="fas fa-exchange-alt" aria-hidden="true"></i><br>활동</a></li>
    <li><a href="<?=BASE_URL?>/admin/setup_information"><i class="fas fa-cog" aria-hidden="true"></i><br>설정</a></li>
  </ul>

  <input type="hidden" name="baseUrl" value="<?=BASE_URL?>">
  <input type="hidden" name="clubIdx" value="<?=!empty($viewClub['idx']) ? $viewClub['idx'] : ''?>">
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
          <input type="hidden" name="action" value="">
          <input type="hidden" name="delete_idx" value="">
          <button type="button" class="btn btn-default btn-refresh">새로고침</button>
          <button type="button" class="btn btn-default btn-delete">삭제합니다</button>
          <button type="button" class="btn btn-default btn-list" data-action="">목록으로</button>
          <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
        </div>
      </div>
    </div>
  </div>
  <!-- End of Page Wrapper -->

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
