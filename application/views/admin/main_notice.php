<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <h1 class="h3 mb-0 text-gray-800">신규 산행 등록</h1>
    <div class="border-top border-bottom mt-4 mb-4 pt-3 pb-3 row align-items-center">
      <div class="col-sm-9 area-btn">
        <a href="/admin/main_entry/<?=$view['idx']?>"><button type="button" class="btn btn-primary">수정</button></a>
        <a href="/admin/main_notice/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">공지</button></a>
        <a href="/admin/main_view_progress/<?=$view['idx']?>"><button type="button" class="btn btn-primary">예약</button></a>
        <a href="/admin/main_view_boarding/<?=$view['idx']?>"><button type="button" class="btn btn-primary">승차</button></a>
        <a href="/admin/main_view_sms/<?=$view['idx']?>"><button type="button" class="btn btn-primary">문자</button></a>
        <a href="/admin/main_view_adjust/<?=$view['idx']?>"><button type="button" class="btn btn-primary">정산</button></a>
      </div>
      <div class="col-sm-3">
        <?php if (!empty($view['status'])): ?>
        <select name="status" class="form-control form-control-sm change-status">
          <option value="">산행 상태</option>
          <option value="">------------</option>
          <option<?=$view['status'] == STATUS_PLAN ? ' selected' : ''?> value="<?=STATUS_PLAN?>">계획</option>
          <option<?=$view['status'] == STATUS_ABLE ? ' selected' : ''?> value="<?=STATUS_ABLE?>">예정</option>
          <option<?=$view['status'] == STATUS_CONFIRM ? ' selected' : ''?> value="<?=STATUS_CONFIRM?>">확정</option>
          <option<?=$view['status'] == STATUS_CANCEL ? ' selected' : ''?> value="<?=STATUS_CANCEL?>">취소</option>
          <option<?=$view['status'] == STATUS_CLOSED ? ' selected' : ''?> value="<?=STATUS_CLOSED?>">종료</option>
        </select>
        <?php endif;?>
      </div>
    </div>
    <form id="myForm" method="post" action="/admin/main_notice_update" enctype="multipart/form-data" class="mb-5 pb-5">
      <input type="hidden" name="noticeIdx" value="<?=$view['idx']?>">
      <div class="row align-items-center">
        <div class="col-sm-9">
          <h4>■ 산행 공지</h4>
        </div>
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
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-1 font-weight-bold">산행 제목</div>
        <div class="col-sm-11"><?=$view['subject']?></div>
      </div>
      <div id="sortable" class="area-notice">
        <?php foreach ($listNoticeDetail as $key => $value): ?>
        <div class="item-notice row align-items-center border-top mt-3 pt-3">
          <div class="col-sm-2"><input type="hidden" name="idx[]" value="<?=$value['idx']?>"><input type="text" name="title[]" value="<?=$value['title']?>" class="form-control"></div>
          <div class="col-sm-9"><textarea name="content[]" rows="10" cols="100" id="content_<?=$key?>" class="content"><?=$value['content']?></textarea></div>
          <div class="col-sm-1"><button type="button" class="btn btn-danger btn-delete-notice pl-2 pr-2">삭제</button></div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="area-button">
        <button type="button" class="btn btn-secondary btn-add-notice mr-2">항목 추가</button>
        <button type="submit" class="btn btn-primary ml-2 mr-4">공지 저장</button>
      </div>
    </form>

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
        var content = '<div class="item-notice row align-items-center border-top mt-3 pt-3"><div class="col-sm-1 font-weight-bold"><input type="text" name="title[]" class="form-control"></div><div class="col-sm-11"><textarea name="content[]" rows="10" cols="100" id="content_' + cnt + '" class="content"></textarea></div></div>';
        $('.area-notice').append(content);
        CKEDITOR.replace('content_' + cnt);
        $('html, body').animate({ scrollTop: $(document).height() }, 800);
      }).on('click', '.btn-delete-notice', function() {
        // 항목 삭제
        var html = '<div class="w-100 text-center">저장 후 삭제됩니다.</div>';
        $(this).closest('.item-notice').animate({ height: 50 }, 'slow').html(html);
      });
    </script>
