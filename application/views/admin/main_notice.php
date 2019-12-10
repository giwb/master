<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">신규 산행 등록</h1>
        </div>
      </div>
    </div>

    <div class="sub-contents">
      <form id="myForm" method="post" action="<?=base_url()?>admin/main_notice_update" enctype="multipart/form-data">
        <input type="hidden" name="idx" value="<?=$view['idx']?>">
        <input type="hidden" name="notice" value="1">
        <h2>■ 산행 공지</h2>
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
    </script>
