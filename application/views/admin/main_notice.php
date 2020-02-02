<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <?php if ($view['status'] != STATUS_PLAN): ?>
          <div class="row align-items-center border-bottom text-center mb-4 pt-3 pb-3">
            <div class="col"><a href="<?=BASE_URL?>/admin/main_entry/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">수정</button></a></div>
            <div class="col"><a href="<?=BASE_URL?>/admin/main_notice/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-secondary w-100">공지</button></a></div>
            <div class="col"><a href="<?=BASE_URL?>/admin/main_view_progress/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">예약</button></a></div>
            <div class="col"><a href="<?=BASE_URL?>/admin/main_view_boarding/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">승차</button></a></div>
            <div class="col"><a href="<?=BASE_URL?>/admin/main_view_sms/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">문자</button></a></div>
            <div class="col"><a href="<?=BASE_URL?>/admin/main_view_adjust/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">정산</button></a></div>
          </div>
          <?php endif; ?>
          <div class="sub-contents">
            <div class="border-bottom pb-4">
              <h2><b><?=viewStatus($view['status'])?></b> <?=$view['subject']?></h2>
              산행일시 : <?=$view['startdate']?> (<?=calcWeek($view['startdate'])?>) <?=$view['starttime']?><br>
              참가비용 : <?=number_format($view['cost_total'] == 0 ? $view['cost'] : $view['cost_total'])?>원 (<?=calcTerm($view['startdate'], $view['starttime'], $view['enddate'], $view['schedule'])?>)<br>
              예약인원 : <?=cntRes($view['idx'])?>명<br>
            </div>
            <form id="myForm" method="post" action="/admin/main_notice_update" enctype="multipart/form-data" class="mb-0">
              <input type="hidden" name="noticeIdx" value="<?=$view['idx']?>">
              <div class="row align-items-center">
                <div class="col-sm-3 pb-2 text-right">
                  <!--
                  <select class="form-control form-control-sm search-notice">
                    <option value="">▼ 다른 산행 공지 불러오기</option>
                    <?php foreach ($listNotice as $value): ?>
                    <option value='<?=$value['idx']?>'><?=$value['subject']?></option>
                    <?php endforeach; ?>
                  </select>
                  -->
                </div>
              </div>
              <div class="area-notice">
                <?php foreach ($listNoticeDetail as $key => $value): ?>
                <div class="item-notice pt-3">
                  <div class="row align-items-center mb-2">
                    <div class="col-sm-11 p-0 pr-2"><input type="hidden" name="idx[]" value="<?=$value['idx']?>"><input type="text" name="title[]" value="<?=$value['title']?>" class="form-control form-control-sm"></div>
                    <div class="col-sm-1 p-0 text-right"><button type="button" class="btn btn-sm btn-danger btn-delete-notice pl-2 pr-2">삭제</button></div>
                  </div>
                  <textarea name="content[]" rows="10" cols="100" id="content_<?=$key?>" class="content"><?=$value['content']?></textarea>
                </div>
                <?php endforeach; ?>
              </div>
              <div class="area-button">
                <button type="button" class="btn btn-secondary btn-add-notice mr-2">항목 추가</button>
                <button type="submit" class="btn btn-primary ml-2 mr-4">공지 저장</button>
              </div>
            </form>
          </div>
        </div>

        <script type="text/javascript">
          CKEDITOR.replaceAll();
          $('#sortable').disableSelection().sortable();
          $(document).on('change', '.search-notice', function() {
            $.ajax({
              url: '/admin/main_entry_notice',
              data: 'idx=' + $(this).val(),
              dataType: 'json',
              type: 'post',
              success: function(result) {
                CKEDITOR.instances.plan.setData(result.plan);
                CKEDITOR.instances.point.setData(result.point);
                CKEDITOR.instances.intro.setData(result.intro);
                CKEDITOR.instances.timetable.setData(result.timetable);
                CKEDITOR.instances.information.setData(result.information);
                CKEDITOR.instances.course.setData(result.course);
              }
            });
          }).on('click', '.btn-add-notice', function() {
            // 항목 추가
            var cnt = 0;
            $('.content').each(function() { cnt++; });
            var content = '<div class="item-notice pt-3"><div class="row align-items-center mb-2"><div class="col-sm-11 p-0 pr-2"><input type="text" name="title[]" class="form-control form-control-sm"></div><div class="col-sm-1 p-0 text-right"><button type="button" class="btn btn-sm btn-danger btn-delete-notice pl-2 pr-2">삭제</button></div></div><textarea name="content[]" rows="10" cols="100" id="content_' + cnt + '" class="content"></textarea></div>';
            $('.area-notice').append(content);
            CKEDITOR.replace('content_' + cnt);
            $('html, body').animate({ scrollTop: $(document).height() }, 800);
          }).on('click', '.btn-delete-notice', function() {
            // 항목 삭제
            var html = '<div class="w-100 text-center">저장 후 삭제됩니다.</div>';
            $(this).closest('.item-notice').animate({ height: 50 }, 'slow').html(html);
          });
        </script>
