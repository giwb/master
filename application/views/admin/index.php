<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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
                  url: '/admin/main_view_progress/<?=$value['idx']?>',
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

        <h2 class="sub-header mb-4">대시보드</h2>
        <div class="row mt-2">
          <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow py-2" onClick="location.href=('<?=BASE_URL?>/admin/member_list')">
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
            <div class="card border-left-success shadow py-2" onClick="location.href=('<?=BASE_URL?>/admin/main_list_progress')">
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
        </div>
        <div class="row">
          <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-info shadow py-2" onClick="location.href=('<?=BASE_URL?>/admin/main_list_closed')">
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
            <div class="card border-left-warning shadow py-2" onClick="location.href=('<?=BASE_URL?>/admin/log_visitor')">
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
        <div id="calendar"></div>
        <script type="text/javascript" src="/public/vendors/chart.js/dist/Chart.bundle.min.js"></script>
