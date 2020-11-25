<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <form id="setupForm" method="post" action="<?=BASE_URL?>/admin/setup_pages_update">
            <div class="row align-items-center mt-3 pt-3">
              <div class="col-sm-12 font-weight-bold mb-2">■ 산악회 소개</div>
              <div class="col-sm-12"><textarea name="about" id="about" rows="10" cols="100"><?=!empty($view['about']) ? reset_html_escape($view['about']) : ''?></textarea></div>
            </div>
            <div class="row align-items-center mt-3 pt-3">
              <div class="col-sm-12 font-weight-bold mb-2">■ 등산 안내인 소개</div>
              <div class="col-sm-12"><textarea name="guide" id="guide" rows="10" cols="100"><?=!empty($view['guide']) ? reset_html_escape($view['guide']) : ''?></textarea></div>
            </div>
            <div class="row align-items-center mt-3 pt-3">
              <div class="col-sm-12 font-weight-bold mb-2">■ 이용안내</div>
              <div class="col-sm-12"><textarea name="howto" id="howto" rows="10" cols="100"><?=!empty($view['howto']) ? reset_html_escape($view['howto']) : ''?></textarea></div>
            </div>
            <div class="row align-items-center mt-3 mb-5 pt-3 pb-5">
              <div class="col-sm-12 font-weight-bold mb-2">■ 백산백소 소개</div>
              <div class="col-sm-12"><textarea name="auth" id="auth" rows="10" cols="100"><?=!empty($view['auth']) ? reset_html_escape($view['auth']) : ''?></textarea></div>
            </div>
            <div class="area-button">
              <input type="hidden" name="base_url" value="<?=BASE_URL?>">
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
