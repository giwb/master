<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between">
          <h1 class="h3 mb-0 text-gray-800">신규 산행 등록</h1>
        </div>
      </div>
    </div>

    <div class="area-reservation border-top border-bottom w-100 mt-4 mb-4 pb-4">
      <div class="area-btn">
        <div class="float-left">
          <a href="<?=base_url()?>admin/main_entry/<?=$view['idx']?>"><button type="button" class="btn btn-primary">수정</button></a>
          <a href="<?=base_url()?>admin/main_notice/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">공지</button></a>
          <a href="<?=base_url()?>admin/main_view_progress/<?=$view['idx']?>"><button type="button" class="btn btn-primary">예약</button></a>
          <a href="<?=base_url()?>admin/main_view_boarding/<?=$view['idx']?>"><button type="button" class="btn btn-primary">승차</button></a>
          <a href="<?=base_url()?>admin/main_view_sms/<?=$view['idx']?>"><button type="button" class="btn btn-primary">문자</button></a>
          <a href="<?=base_url()?>admin/main_view_adjust/<?=$view['idx']?>"><button type="button" class="btn btn-primary">정산</button></a>
        </div>
        <div class="float-right">
          <select name="status" class="form-control form-control-sm change-status">
            <option value="">산행 상태</option>
            <option value="">------------</option>
            <option<?=$view['status'] == STATUS_PLAN ? ' selected' : ''?> value="<?=STATUS_PLAN?>">계획</option>
            <option<?=$view['status'] == STATUS_ABLE ? ' selected' : ''?> value="<?=STATUS_ABLE?>">예정</option>
            <option<?=$view['status'] == STATUS_CONFIRM ? ' selected' : ''?> value="<?=STATUS_CONFIRM?>">확정</option>
            <option<?=$view['status'] == STATUS_CANCEL ? ' selected' : ''?> value="<?=STATUS_CANCEL?>">취소</option>
            <option<?=$view['status'] == STATUS_CLOSED ? ' selected' : ''?> value="<?=STATUS_CLOSED?>">종료</option>
          </select>
        </div>
      </div>
    </div>

    <div class="sub-contents">
      <form id="myForm" method="post" action="<?=base_url()?>admin/main_notice_update" enctype="multipart/form-data">
        <input type="hidden" name="idx" value="<?=$view['idx']?>">
        <input type="hidden" name="notice" value="1">
        <div class="row">
          <div class="col-sm-9">
            <h2>■ 산행 공지</h2>
          </div>
          <div class="col-sm-3 pb-2 text-right">
            <select class="form-control form-control-sm search-notice">
              <option value="">▼ 다른 산행 공지 불러오기</option>
              <?php foreach ($listNotice as $value): ?>
              <option value='<?=$value['idx']?>'><?=$value['subject']?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <table class="table table-notice">
          <colgroup>
            <col width="150">
          </colgroup>
          <tbody>
            <tr>
              <th>산행 제목</th>
              <td><?=$view['subject']?></td>
            </tr>
            <tr>
              <th>기획의도</th>
              <td><textarea name="plan" id="plan" rows="10" cols="100"><?=$view['plan']?></textarea></td>
            </tr>
            <tr>
              <th>산행개요</th>
              <td><textarea name="point" id="point" rows="10" cols="100"><?=$view['point']?></textarea></td>
            </tr>
            <tr>
              <th>산행지 소개</th>
              <td><textarea name="intro" id="intro" rows="10" cols="100"><?=$view['intro']?></textarea></td>
            </tr>
            <tr>
              <th>일정안내</th>
              <td><textarea name="timetable" id="timetable" rows="10" cols="100"><?=$view['timetable']?></textarea></td>
            </tr>
            <tr>
              <th>산행안내</th>
              <td><textarea name="information" id="information" rows="10" cols="100"><?=$view['information']?></textarea></td>
            </tr>
            <tr>
              <th>코스안내</th>
              <td><textarea name="course" id="course" rows="10" cols="100"><?=$view['course']?></textarea></td>
            </tr>
<!--
            <tr>
              <th>산행지 사진</th>
              <td><input type="file" name="photo"></td>
            </tr>
            <tr>
              <th>산행 지도 사진</th>
              <td><input type="file" name="map"></td>
            </tr>
-->
            <tr><td colspan="2"></td></tr>
          </tbody>
        </table>

        <div class="area-button">
          <button type="submit" class="btn btn-primary">확인합니다</button>
        </div>
      </form>
    </div>

    <script type="text/javascript">
      CKEDITOR.replace('plan');
      CKEDITOR.replace('point');
      CKEDITOR.replace('intro');
      CKEDITOR.replace('timetable');
      CKEDITOR.replace('information');
      CKEDITOR.replace('course');

      $(document).on('change', '.search-notice', function() {
        $.ajax({
          url: $('input[name=base_url]').val() + 'admin/main_entry_notice',
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
      });
    </script>
