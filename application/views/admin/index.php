<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <script>
      $(document).ready(function() {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        /* initialize the calendar
        -----------------------------------------------------------------*/
        var calendar =  $('#calendar').fullCalendar({
          header: {
            left: 'prev',
            center: 'title',
            right: 'next'
          },
          editable: false,
          selectable: false,
          defaultView: 'month',
          axisFormat: 'h:mm',
          columnFormat: {
                    month: 'ddd',
                    week: 'ddd d',
                    day: 'dddd M/d',
                    agendaDay: 'dddd d'
                },
                titleFormat: {
                    month: 'yyyy년 MMMM',
                    week: "yyyy년 MMMM",
                    day: 'yyyy년 MMMM'
                },
          allDaySlot: false,
          selectHelper: true,
          select: function(start, end, allDay) {
            var title = prompt('Event Title:');
            if (title) {
              calendar.fullCalendar('renderEvent',
                {
                  title: title,
                  start: start,
                  end: end,
                  allDay: allDay
                },
                true // make the event "stick"
              );
            }
            calendar.fullCalendar('unselect');
          },
          droppable: false, // this allows things to be dropped onto the calendar !!!
          drop: function(date, allDay) { // this function is called when something is dropped

            // retrieve the dropped element's stored Event Object
            var originalEventObject = $(this).data('eventObject');

            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);

            // assign it the date that was reported
            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;

            // render the event on the calendar
            // the last `true` argument determines if the event "sticks" (https://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
              // if so, remove the element from the "Draggable Events" list
              $(this).remove();
            }
          },
          events: [
<?php
  foreach ($listMonthNotice as $value) {
    $startDate = strtotime($value['startdate']);
    $endDate = calcEndDate($value['startdate'], $value['schedule']);
    $viewNoticeStatus = viewNoticeStatus($value['status'])
?>
            {
              title: '<?=$viewNoticeStatus?><?=$value['mname']?>',
              start: new Date(y, m, <?=date('j', $startDate)?>),
              end: new Date(y, m, <?=date('j', $endDate)?>),
              url: '<?=base_url()?>admin/list_progress/<?=$value['idx']?>',
              className: 'notice-status<?=$value['status']?>'
            },
<?php
  }
?>
          ],
        });
      });
    </script>

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">대시보드</h1>
            <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
          </div>

          <!-- Content Row -->
          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
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
            <div class="col-xl-3 col-md-6 mb-4">
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

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
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
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">오늘 방문자수</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">12명</div>
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
        </div>
      </div>
    </div>

    <script type="text/javascript" src="/public/vendors/chart.js/dist/Chart.bundle.min.js"></script>
    <script type="text/javascript" src="/public/js/widgets.js"></script>
