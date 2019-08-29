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
      <form id="myForm" method="post">
        <h2>■ 산행 공지</h2>
        <table class="table form-small">
          <colgroup>
            <col width="150">
          </colgroup>
          <tbody>
            <tr>
              <th>기획의도</th>
              <td><textarea name="plan" id="plan" rows="10" cols="100"></textarea></td>
            </tr>
            <tr>
              <th>핵심안내</th>
              <td><textarea name="point" id="point" rows="10" cols="100"></textarea></td>
            </tr>
            <tr>
              <th>타임테이블</th>
              <td><textarea name="timetable" id="timetable" rows="10" cols="100"></textarea></td>
            </tr>
            <tr>
              <th>산행안내</th>
              <td><textarea name="information" id="information" rows="10" cols="100"></textarea></td>
            </tr>
            <tr>
              <th>산행코스안내</th>
              <td><textarea name="course" id="course" rows="10" cols="100"></textarea></td>
            </tr>
            <tr>
              <th>산행지 소개</th>
              <td><textarea name="intro" id="intro" rows="10" cols="100"></textarea></td>
            </tr>
            <tr>
              <th>산행지 사진</th>
              <td><input type="file" name="file"></td>
            </tr>
            <tr>
              <th>산행 지도 사진</th>
              <td><input type="file" name="map"></td>
            </tr>
            <tr><td colspan="2"></td></tr>
          </tbody>
        </table>

        <div class="text-center mb-5">
          <button type="button" class="btn btn-primary btn-entry">등록합니다</button>
        </div>
      </form>
    </div>

    <script type="text/javascript">
      var oEditors2 = [];
      nhn.husky.EZCreator.createInIFrame({
        oAppRef: oEditors2,
        elPlaceHolder: 'plan',
        sSkinURI: '/public/editor/SmartEditor2Skin.html',
        fCreator: 'createSEditor2'
      });
      var oEditors3 = [];
      nhn.husky.EZCreator.createInIFrame({
        oAppRef: oEditors3,
        elPlaceHolder: 'point',
        sSkinURI: '/public/editor/SmartEditor2Skin.html',
        fCreator: 'createSEditor2'
      });
      var oEditors4 = [];
      nhn.husky.EZCreator.createInIFrame({
        oAppRef: oEditors4,
        elPlaceHolder: 'timetable',
        sSkinURI: '/public/editor/SmartEditor2Skin.html',
        fCreator: 'createSEditor2'
      });
      var oEditors5 = [];
      nhn.husky.EZCreator.createInIFrame({
        oAppRef: oEditors5,
        elPlaceHolder: 'information',
        sSkinURI: '/public/editor/SmartEditor2Skin.html',
        fCreator: 'createSEditor2'
      });
      var oEditors6 = [];
      nhn.husky.EZCreator.createInIFrame({
        oAppRef: oEditors6,
        elPlaceHolder: 'course',
        sSkinURI: '/public/editor/SmartEditor2Skin.html',
        fCreator: 'createSEditor2'
      });
      var oEditors7 = [];
      nhn.husky.EZCreator.createInIFrame({
        oAppRef: oEditors7,
        elPlaceHolder: 'intro',
        sSkinURI: '/public/editor/SmartEditor2Skin.html',
        fCreator: 'createSEditor2'
      });
    </script>
