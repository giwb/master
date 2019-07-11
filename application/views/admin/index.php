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
          {
            title: 'Click for Google',
            start: new Date(y, m, 28),
            end: new Date(y, m, 29),
            url: 'https://google.com/',
            className: 'success'
          }
        ],
      });
    });
  </script>

  <div id="right-panel" class="right-panel">
      <div class="breadcrumbs">
          <div class="col-sm-4">
              <div class="page-header float-left">
                  <div class="page-title">
                      <h1>대시보드</h1>
                  </div>
              </div>
          </div>
      </div>
      <div class="content mt-3">
          <div class="col-sm-6 col-lg-3">
              <div class="card text-white bg-flat-color-1">
                  <div class="card-body pb-0">
                      <h4 class="mb-0">
                          <span class="count"><?=$cntTotalMember['CNT']?></span>
                      </h4>
                      <p class="text-light">현재 회원수</p>
                      <div class="chart-wrapper px-0">
                          <canvas id="widgetChart1"></canvas>
                      </div>
                  </div>

              </div>
          </div>
          <!--/.col-->

          <div class="col-sm-6 col-lg-3">
              <div class="card text-white bg-flat-color-2">
                  <div class="card-body pb-0">
                      <h4 class="mb-0">
                          <span class="count"><?=$cntTotalTour['CNT']?></span>
                      </h4>
                      <p class="text-light">다녀온 산행 횟수</p>
                      <div class="chart-wrapper px-0">
                          <canvas id="widgetChart2"></canvas>
                      </div>

                  </div>
              </div>
          </div>
          <!--/.col-->

          <div class="col-sm-6 col-lg-3">
              <div class="card text-white bg-flat-color-3">
                  <div class="card-body pb-0">
                      <h4 class="mb-0">
                          <span class="count"><?=$cntTotalCustomer['CNT']?></span>
                      </h4>
                      <p class="text-light">다녀온 산행 인원수</p>

                  </div>

                  <div class="chart-wrapper px-0">
                      <canvas id="widgetChart3"></canvas>
                  </div>
              </div>
          </div>
          <!--/.col-->

          <div class="col-sm-6 col-lg-3">
              <div class="card text-white bg-flat-color-4">
                  <div class="card-body pb-0">
                      <h4 class="mb-0">
                          <span class="count">12</span>
                      </h4>
                      <p class="text-light">오늘 방문자수</p>

                      <div class="chart-wrapper px-3">
                          <canvas id="widgetChart4"></canvas>
                      </div>

                  </div>
              </div>
          </div>

          <div id="calendar"></div>
      </div>
  </div>

  <script type="text/javascript" src="/public/vendors/chart.js/dist/Chart.bundle.min.js"></script>
  <script type="text/javascript" src="/public/js/widgets.js"></script>
