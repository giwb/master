<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        </div>
        <div class="col-xl-4 col-md-12 widget-column mt-0 mb-5 pb-0">
          <?php if (!empty($listNoticeSchedule)): ?>
          <section class="section mb-5">
            <h4 class="font-weight-bold"><strong>월간 일정</strong></h4>
            <hr class="text-default" style="margin-bottom: 33px;">
            <div class="card">
              <div class="view overlay">
                <div id="calendar"></div>
              </div>
            </div>
          </section>
          <script>
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
                    foreach ($listNoticeSchedule as $value):
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
                  <?php else: ?>
                  {
                    title: '<?=$value['status'] != STATUS_PLAN ? $value['starttime'] . "\\n" : "[계획]\\n"?><?=$value['mname']?>',
                    start: new Date('<?=date('Y', $startDate)?>/<?=date('m', $startDate)?>/<?=date('d', $startDate)?>/00:00:01'),
                    end: new Date('<?=date('Y', $endDate)?>/<?=date('m', $endDate)?>/<?=date('d', $endDate)?>/23:59:59'),
                    url: '<?=BASE_URL?>/admin/main_view_progress/<?=$value['idx']?>',
                    className: 'notice-status<?=$value['status']?>'
                  },
                  <?php
                      endif;
                    endforeach;
                  ?>
                ],
              });
            });
          </script>
          <?php endif; ?>

          <section class="section">
            <h4 class="font-weight-bold"><strong>현재 진행중인 여행</strong></h4>
            <hr class="text-default">
            <div class="card">
              <div class="view overlay pt-2 pb-3">
                <?php if (!empty($listNoticeFooter)): ?>
                <?php foreach ($listNoticeFooter as $key => $value): $week = calcWeek($value['startdate']); ?>
                <div class="row no-gutters mt-2<?=$key != 0 ? ' pt-2' : ''?>">
                  <div class="col-4 col-sm-4 pl-3 pr-3"><a href="<?=BASE_URL?>/admin/main_view_progress/<?=$value['idx']?>"><?php if (!empty($value['photo']) && file_exists(PHOTO_PATH . 'thumb_' . $value['photo'])): ?><img class="w-100" src="<?=PHOTO_URL . 'thumb_' . $value['photo']?>"><?php else: ?><img class="w-100" src="/public/images/nophoto.png"><?php endif; ?></a></div>
                  <div class="col-8 col-sm-8">
                    <a href="<?=BASE_URL?>/admin/main_view_progress/<?=$value['idx']?>" class="<?=$week == '일' ? 'text-giwbred' : 'text-giwbblue'?>"><strong><?=viewStatus($value['status'])?> <?=$value['subject']?></strong></a><br>
                    <small><?=$value['startdate']?> (<?=$week?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 / <?=cntRes($value['idx'])?>명<br>
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

          <section class="section mt-5">
            <h4 class="font-weight-bold"><strong>최신 댓글</strong></h4>
            <hr class="text-default">
            <div class="card">
              <div class="view overlay p-4">
                <?php if (!empty($listFooterReply)): ?>
                <?php foreach ($listFooterReply as $key => $value): ?>
                <div class="mb-3"><a href="<?=$value['url']?>"><?=ksubstr($value['content'], 35)?></a><br><small><?=$value['nickname']?> · <?=calcStoryTime($value['created_at'])?></small></div>
                <?php endforeach; ?>
                <?php else: ?><div class="text-center pt-5 pb-5">등록된 여행 정보가 없습니다.</div>
                <?php endif; ?>
              </div>
            </div>
          </section>

          <?php if (!empty($cntTotalMember)): ?>
          <section class="mt-5">
            <h4 class="font-weight-bold"><strong>산악회 통계</strong></h4>
            <hr class="text-default">
            <div class="row">
              <div class="col-xl-6 col-md-6 mb-4">
                <div class="card shadow py-2" onClick="location.href=('<?=BASE_URL?>/admin/member_list')">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">현재 회원수</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$cntTotalMember['CNT']?>명</div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-user fa-2x text-gray-300 text-primary"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 mb-4">
                <div class="card shadow py-2" onClick="location.href=('<?=BASE_URL?>/admin/main_list_progress')">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">다녀온 산행횟수</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$cntTotalTour['CNT']?>회</div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-mountain fa-2x text-gray-300 text-success"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 mb-4">
                <div class="card shadow py-2" onClick="location.href=('<?=BASE_URL?>/admin/main_list_closed')">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">다녀온 산행 인원수</div>
                        <div class="row no-gutters align-items-center">
                          <div class="col-auto">
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?=$cntTotalCustomer['CNT']?>명</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300 text-info"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 mb-4">
                <div class="card shadow py-2" onClick="location.href=('<?=BASE_URL?>/admin/log_visitor')">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">오늘 방문자수</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$cntTodayVisitor['CNT']?>명</div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-walking fa-2x text-gray-300 text-danger"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </main>

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
  <footer class="page-footer stylish-color-dark mt-4 pb-4">
    <main id="footer" class="row no-gutters white-text small align-items-center">
      <div class="col-sm-2 text-center"><img width="45" src="/public/images/icon.png" class="logo-img"><span class="logo">경인웰빙</span></div>
      <div class="col-sm-5 logo-text">
        사업자등록번호 : 568-45-00657 / 관광사업등록번호 : 제 2021-000001호<br>
        서울 금천구 가산디지털 1로 137, 19층 1901호 (가산동, IT 캐슬 2차)<br>
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
  <!-- /FOOTER -->

  <!-- Back to Top -->
  <a class="scroll-to-top rounded" href="javascript:;">
    <i class="fa fa-angle-up"></i>
  </a>

</body>
</html>
