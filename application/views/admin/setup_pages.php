<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">소개 페이지 수정</h1>
        </div>
      </div>
    </div>

    <div class="sub-contents">
      <form id="setupForm" method="post" action="<?=base_url()?>admin/setup_pages_update">
        <table class="table table-notice">
          <colgroup>
            <col width="150">
          </colgroup>
          <tbody>
            <tr>
              <th>산악회 소개</th>
              <td><textarea name="about" id="about" rows="10" cols="100"><?=!empty($view['about']) ? reset_html_escape($view['about']) : ''?></textarea></td>
            </tr>
            <tr>
              <th>등산 안내인 소개</th>
              <td><textarea name="guide" id="guide" rows="10" cols="100"><?=!empty($view['guide']) ? reset_html_escape($view['guide']) : ''?></textarea></td>
            </tr>
            <tr>
              <th>이용안내</th>
              <td><textarea name="howto" id="howto" rows="10" cols="100"><?=!empty($view['howto']) ? reset_html_escape($view['howto']) : ''?></textarea></td>
            </tr>
            <tr>
              <th>백산백소 소개</th>
              <td><textarea name="auth" id="auth" rows="10" cols="100"><?=!empty($view['auth']) ? reset_html_escape($view['auth']) : ''?></textarea></td>
            </tr>
            <tr><td colspan="2"></td></tr>
          </tbody>
        </table>
        <div class="area-button">
          <button type="submit" class="btn btn-primary">확인합니다</button>
        </div>
      </form>
    </div>

    <script type="text/javascript">
      CKEDITOR.replace('about');
      CKEDITOR.replace('guide');
      CKEDITOR.replace('howto');
      CKEDITOR.replace('auth');
    </script>
