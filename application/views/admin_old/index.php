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
                  foreach ($listNotice as $value):
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
                  url: '/admin_old/main_view_progress/<?=$value['idx']?>',
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

        <div class="admin-main">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">대시보드</h1>
          </div>

          <!-- Content Row -->
          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-6 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">현재 회원수</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$cntTotalMember['CNT']?>명</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-user fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-6 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">다녀온 산행횟수</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$cntTotalTour['CNT']?>회</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-mountain fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-6 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">다녀온 산행 인원수</div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?=$cntTotalCustomer['CNT']?>명</div>
                        </div>
                        <div class="col">
                          <div class="progress progress-sm mr-2">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-6 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">오늘 방문자수</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$cntTodayVisitor['CNT']?>명</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-walking fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Row -->
          <div id="calendar"></div>

          <div class="row align-items-center border-top border-bottom mb-3 pt-4 pb-3">
            <div class="col-6">■ 최신 댓글</div>
            <div class="col-6 text-right small"><a href="/admin_old/log_reply">[더보기]</a></div>
          </div>
          <div class="story-reply mb-5">
            <?=$listReply?>
          </div>
        </div>

      </div>
    </div>

    <script type="text/javascript" src="/public/vendors/chart.js/dist/Chart.bundle.min.js"></script>
