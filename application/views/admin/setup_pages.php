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
            <div class="row align-items-center mt-3 pt-3">
              <div class="col-sm-12 font-weight-bold mb-2">■ 경인웰빙 100대명산</div>
              <div class="col-sm-12"><textarea name="mountain" id="mountain" rows="10" cols="100"><?=!empty($view['mountain']) ? reset_html_escape($view['mountain']) : ''?></textarea></div>
            </div>
            <div class="row align-items-center mt-3 pt-3">
              <div class="col-sm-12 font-weight-bold mb-2">■ 경인웰빙 100대명소</div>
              <div class="col-sm-12"><textarea name="place" id="place" rows="10" cols="100"><?=!empty($view['place']) ? reset_html_escape($view['place']) : ''?></textarea></div>
            </div>
            <div class="row align-items-center mt-3 pt-3">
              <div class="col-sm-12 font-weight-bold mb-2">■ 이용약관</div>
              <div class="col-sm-12"><textarea name="agreement" id="agreement" rows="10" cols="100"><?=!empty($view['agreement']) ? reset_html_escape($view['agreement']) : ''?></textarea></div>
            </div>
            <div class="row align-items-center mt-3 mb-5 pt-3 pb-5">
              <div class="col-sm-12 font-weight-bold mb-2">■ 개인정보 취급방침</div>
              <div class="col-sm-12"><textarea name="personal" id="personal" rows="10" cols="100"><?=!empty($view['personal']) ? reset_html_escape($view['personal']) : ''?></textarea></div>
            </div>
            <div class="area-button">
              <input type="hidden" name="base_url" value="<?=BASE_URL?>">
              <input type="hidden" name="club_idx" value="<?=$clubIdx?>">
              <button type="submit" class="btn btn-primary">확인합니다</button>
            </div>
          </form>
        </div>
        <script type="text/javascript">
          CKEDITOR.replace('about');
          CKEDITOR.replace('guide');
          CKEDITOR.replace('howto');
          CKEDITOR.replace('mountain');
          CKEDITOR.replace('place');
          CKEDITOR.replace('agreement');
          CKEDITOR.replace('personal');
        </script>
