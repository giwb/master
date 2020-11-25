<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<link href="/public/css/fullcalendar.css" rel="stylesheet">
<link href="/public/css/fullcalendar.print.css" rel="stylesheet">
<script src="/public/js/fullcalendar.js" type="text/javascript"></script>
<script src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js'></script>
<script>
  $(document).ready(function() {
    $('#calendar').fullCalendar({
      header: {
        left: 'prev',
        center: 'title',
        right: 'next'
      },
      <?php if (!empty($sdate)): ?>
      year: '<?=date('Y', strtotime($sdate))?>',
      month: '<?=date('m', strtotime($sdate)) - 1?>',
      <?php endif; ?>
      titleFormat: {
        month: 'yyyy년 MMMM',
        week: "yyyy년 MMMM",
        day: 'yyyy년 MMMM'
      },
      selectable: true,
      select: function(date) {
        var title = $.insertScheduleModal(date);
        var eventData;
        if (title) {
          $('#calendar').fullCalendar('renderEvent', eventData, true);
        }
        $('#calendar').fullCalendar('unselect');
      },
      events: [
        <?php
          foreach ($listCalendar as $value):
            $startDate = $endDate = strtotime($value['nowdate']);
        ?>
        {
          title: '<?=$value['dayname']?>',
          start: new Date('<?=date('Y', $startDate)?>-<?=date('m', $startDate)?>-<?=date('d', $startDate)?>T00:00:00'),
          end: new Date('<?=date('Y', $endDate)?>-<?=date('m', $endDate)?>-<?=date('d', $endDate)?>T23:59:59'),
          url: 'javascript: $.updateScheduleModal(<?=$value['idx']?>, "<?=$value['nowdate']?>", "<?=$value['dayname']?>", <?=$value['holiday']?>)',
          className: '<?=$value['holiday'] == 1 ? "holiday" : "dayname"?>'
        },
        <?php
          endforeach;
        ?>
      ]
    });
  });

  $(document).on('click', '.btn-calendar', function() {
    // 일정 등록
    var idx     = $('input[name=idx]').val();
    var nowdate = $('input[name=nowdate]').val();
    var dayname = $('input[name=dayname]').val();
    var holiday = $('input:checkbox[name=holiday]').is(':checked');
    if (holiday == true) holiday = 1; else holiday = 0;
    $('.error-message').hide();

    $.ajax({
      url: '/admin_old/setup_calendar_update',
      data: 'nowdate=' + nowdate + '&dayname=' + dayname + '&holiday=' + holiday + '&idx=' + idx,
      dataType: 'json',
      type: 'post',
      success: function(result) {
        if (result.error == 1) {
          $('.error-message').text(result.message).slideDown();
        } else {
          location.replace('/admin_old/setup_calendar?d=' + nowdate);
        }
      }
    });
  }).on('click', '.btn-calendar-delete', function() {
    var nowdate = $('input[name=nowdate]').val();

    $.ajax({
      url: '/admin_old/setup_calendar_delete',
      data: 'idx=' + $('input[name=idx]').val(),
      dataType: 'json',
      type: 'post',
      success: function(result) {
        if (result.error == 1) {
          $('.error-message').text(result.message).slideDown();
        } else {
          location.replace('/admin_old/setup_calendar?d=' + nowdate);
        }
      }
    });
  });

  // 산행계획 등록
  $.insertScheduleModal = function(nowdate) {
    var nowdate = $.changeDate(nowdate);
    $('.error-message').hide();
    $('#scheduleModal input[name=idx]').val('');
    $('#scheduleModal input[name=nowdate]').val(nowdate);
    $('#scheduleModal input[name=dayname]').val('');
    $('#scheduleModal input:checkbox[name=holiday]').prop('checked', false);
    $('#scheduleModal .btn-calendar-delete').hide();
    $('#scheduleModal .btn-calendar').text('등록');
    $('#scheduleModal').modal('show');
  }

  // 산행계획 수정
  $.updateScheduleModal = function(idx, nowdate, dayname, holiday) {
    $('.error-message').hide();
    $('#scheduleModal input[name=idx]').val(idx);
    $('#scheduleModal input[name=nowdate]').val(nowdate);
    $('#scheduleModal input[name=dayname]').val(dayname);
    $('#scheduleModal input:checkbox[name=holiday]').prop('checked', holiday);
    $('#scheduleModal .btn-calendar-delete').show();
    $('#scheduleModal .btn-calendar').text('수정');
    $('#scheduleModal').modal('show');
  }

  $.changeDate = function(date) {
    var y = date.getFullYear().toString();
    var m = (date.getMonth() + 1).toString();
    var d = date.getDate().toString();
    return y + '-' + (m[1] ? m : '0' + m[0]) + '-' + (d[1] ? d : '0' + d[0]);
  }
</script>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">달력관리</h1>
        </div>
      </div>

      <div id="calendar" class="setup-schedule mb-5"></div>

      <!-- Calendar Modal -->
      <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="smallmodalLabel">달력 일정 등록</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row align-items-center mb-2">
                <div class="col-sm-3">일자</div>
                <div class="col-sm-9"><input type="text" name="nowdate" class="form-control form-control-sm"></div>
              </div>
              <div class="row align-items-center">
                <div class="col-sm-3">일정명</div>
                <div class="col-sm-9"><input type="text" name="dayname" class="form-control form-control-sm"></div>
              </div>
              <div class="row align-items-center pt-2 pb-2">
                <div class="col-sm-3">휴일여부</div>
                <div class="col-sm-9"><label><input type="checkbox" name="holiday"> 휴일</label></div>
              </div>
              <div class="text-center error-message"></div>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="idx" value="">
              <button type="button" class="btn btn-primary btn-calendar">등록</button>
              <button type="button" class="btn btn-danger btn-calendar-delete">삭제</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
            </div>
          </div>
        </div>
      </div>
