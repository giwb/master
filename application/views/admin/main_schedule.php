<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mt-3 mb-5">
          <div id="calendar" class="mb-0"></div>

          <?php if (empty($listPlanned)): ?>
          <div class="border-bottom pt-5 pb-5 text-center">
            등록된 계획이 없습니다.
          </div>
          <?php else: foreach ($listPlanned as $value): ?>
          <div class="row align-items-center border-bottom pt-2 pb-2">
            <div class="col-9 col-sm-10">
              <b><?=viewStatus($value['status'], $value['visible'])?></b> <a href="<?=BASE_URL?>/admin/main_view_progress/<?=$value['idx']?>"><?=$value['subject']?></a><br>
              <div class="small">
                <?php if (!empty($value['sido'])): ?>
                <?php foreach ($value['sido'] as $key => $sido): ?><?=$sido?> <?=!empty($value['gugun'][$key]) ? $value['gugun'][$key] : ''?>, <?php endforeach; ?>
                <?php endif; ?>
                <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>)
              </div>
            </div>
            <div class="col-3 col-sm-2 text-right">
              <?php if ($value['visible'] == VISIBLE_ABLE): ?>
              <button type="button" class="btn btn-sm btn-secondary btn-change-visible" data-idx="<?=$value['idx']?>" data-visible="<?=VISIBLE_NONE?>">숨김</button>
              <?php else: ?>
              <button type="button" class="btn btn-sm btn-default btn-change-visible" data-idx="<?=$value['idx']?>" data-visible="<?=VISIBLE_ABLE?>">공개</button>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; endif; ?>
        </div>

        <!-- Schedule Modal -->
        <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">산행계획 등록</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row align-items-center mb-2">
                  <div class="col-sm-3">시작일</div>
                  <div class="col-sm-9"><input type="text" name="sdate" class="form-control form-control-sm"></div>
                </div>
                <div class="row align-items-center mb-2">
                  <div class="col-sm-3">종료일</div>
                  <div class="col-sm-9"><input type="text" name="edate" class="form-control form-control-sm"></div>
                </div>
                <div class="row align-items-center">
                  <div class="col-sm-3">산행명</div>
                  <div class="col-sm-9"><input type="text" name="subject" class="form-control form-control-sm"></div>
                </div>
                <div class="text-center error-message"></div>
              </div>
              <div class="past-schedule border-top small pt-2 pb-2 pl-3 pr-3">
              </div>
              <div class="modal-footer">
                <input type="hidden" name="idx" value="">
                <button type="button" class="btn btn-primary btn-schedule">등록</button>
                <button type="button" class="btn btn-danger btn-schedule-delete">삭제</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
              </div>
            </div>
          </div>
        </div>

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
              selectable: true,
              selectHelper: true,
              select: function(start, end, allDay) {
                var title = $.insertScheduleModal(start, end);
                var eventData;
                if (title) {
                  eventData = {
                    title: title,
                    start: start,
                    end: end,
                    allDay: allDay
                  };
                  $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
                }
                $('#calendar').fullCalendar('unselect');
              },
              editable: false,
              eventLimit: true, // allow "more" link when too many events
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
              events: [
                <?php
                  foreach ($listSchedule as $value):
                    $sdate = strtotime($value['startdate']);
                    $edate = strtotime($value['enddate']);

                    if ($value['status'] == 'schedule'):
                ?>
                {
                  title: '<?=$value['subject']?>',
                  start: new Date('<?=date('Y', $sdate)?>-<?=date('m', $sdate)?>-<?=date('d', $sdate)?>T00:00:00'),
                  end: new Date('<?=date('Y', $edate)?>-<?=date('m', $edate)?>-<?=date('d', $edate)?>T23:59:59'),
                  url: 'javascript:;',
                  className: '<?=$value['class']?>'
                },
                <?php else: ?>
                {
                  title: '<?=$value['subject']?>',
                  start: new Date('<?=date('Y', $sdate)?>-<?=date('m', $sdate)?>-<?=date('d', $sdate)?>T00:00:00'),
                  end: new Date('<?=date('Y', $edate)?>-<?=date('m', $edate)?>-<?=date('d', $edate)?>T23:59:59'),
                  url: 'javascript: $.updateScheduleModal(<?=$value['idx']?>)',
                  className: 'scheduled'
                },
                <?php
                    endif;
                  endforeach;
                ?>
              ]
            });
          });

          $(document).on('click', '.past-schedule a', function() {
            var subject = $(this).data('subject');
            $('.error-message').hide();
            $('input[name=subject]').val(subject);
          }).on('click', '.btn-schedule', function() {
            var sdate = $('input[name=sdate]').val();
            var edate = $('input[name=edate]').val();
            var subject = $('input[name=subject]').val();
            var idx = $('input[name=idx]').val();
            var data;
            $('.error-message').hide();

            if (idx != '') {
              data = 'sdate=' + sdate + '&edate=' + edate + '&subject=' + subject + '&idx=' + idx;
            } else {
              data = 'sdate=' + sdate + '&edate=' + edate + '&subject=' + subject;
            }

            $.ajax({
              url: '<?=BASE_URL?>/admin/main_schedule_update',
              data: data,
              dataType: 'json',
              type: 'post',
              success: function(result) {
                if (result.error == 1) {
                  $('.error-message').text(result.message).slideDown();
                } else {
                  location.replace('<?=BASE_URL?>/admin/main_schedule?d=' + sdate);
                }
              }
            });
          }).on('click', '.btn-schedule-delete', function() {
            var sdate = $('input[name=sdate]').val();

            $.ajax({
              url: '<?=BASE_URL?>/admin/main_schedule_delete',
              data: 'idx=' + $('input[name=idx]').val(),
              dataType: 'json',
              type: 'post',
              success: function(result) {
                if (result.error == 1) {
                  $('.error-message').text(result.message).slideDown();
                } else {
                  location.replace('<?=BASE_URL?>/admin/main_schedule?d=' + sdate);
                }
              }
            });
          });

          // 산행계획 등록
          $.insertScheduleModal = function(start, end) {
            var sdate = $.changeDate(start);
            var edate = $.changeDate(end);
            $('.error-message').hide();
            $('#scheduleModal .btn-schedule-delete').hide();
            $('#scheduleModal .btn-schedule').text('등록');
            $('#scheduleModal input[name=sdate]').val(sdate);
            $('#scheduleModal input[name=edate]').val(edate);
            $('#scheduleModal').modal('show');

            $.ajax({
              url: '<?=BASE_URL?>/admin/main_schedule_past',
              data: 'sdate=' + sdate + '&edate=' + edate,
              dataType: 'json',
              type: 'post',
              success: function(result) {
                $('.past-schedule').html(result.message);
              }
            });
          }

          // 산행계획 수정
          $.updateScheduleModal = function(idx) {
            $('.error-message').hide();
            $('#scheduleModal .btn-schedule-delete').show();
            $('#scheduleModal .btn-schedule').text('수정');
            $('#scheduleModal').modal('show');

            $.ajax({
              url: '<?=BASE_URL?>/admin/main_schedule_past',
              data: 'idx=' + idx,
              dataType: 'json',
              type: 'post',
              success: function(result) {
                $('#scheduleModal input[name=sdate]').val(result.sdate);
                $('#scheduleModal input[name=edate]').val(result.edate);
                $('#scheduleModal input[name=subject]').val(result.subject);
                $('#scheduleModal input[name=idx]').val(result.idx);
                $('.past-schedule').html(result.message);
              }
            });
          }

          $.changeDate = function(date) {
            var y = date.getFullYear().toString();
            var m = (date.getMonth() + 1).toString();
            var d = date.getDate().toString();
            return y + '-' + (m[1] ? m : '0' + m[0]) + '-' + (d[1] ? d : '0' + d[0]);
          }
        </script>
