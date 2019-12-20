<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <h1 class="h3 mb-0 text-gray-800">소개 페이지 수정</h1>
    <form id="setupForm" method="post" action="<?=base_url()?>admin/setup_pages_update">
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-2 font-weight-bold">산악회 소개</div>
        <div class="col-sm-10"><textarea name="about" id="about" rows="10" cols="100"><?=!empty($view['about']) ? reset_html_escape($view['about']) : ''?></textarea></div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-2 font-weight-bold">등산 안내인 소개</div>
        <div class="col-sm-10"><textarea name="guide" id="guide" rows="10" cols="100"><?=!empty($view['guide']) ? reset_html_escape($view['guide']) : ''?></textarea></div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-2 font-weight-bold">이용안내</div>
        <div class="col-sm-10"><textarea name="howto" id="howto" rows="10" cols="100"><?=!empty($view['howto']) ? reset_html_escape($view['howto']) : ''?></textarea></div>
      </div>
      <div class="row align-items-center border-top mt-3 mb-5 pt-3 pb-5">
        <div class="col-sm-2 font-weight-bold">백산백소 소개</div>
        <div class="col-sm-10"><textarea name="auth" id="auth" rows="10" cols="100"><?=!empty($view['auth']) ? reset_html_escape($view['auth']) : ''?></textarea></div>
      </div>
      <div class="area-button">
        <button type="submit" class="btn btn-primary">확인합니다</button>
      </div>
    </form>

    <script type="text/javascript">
      CKEDITOR.replace('about');
      CKEDITOR.replace('guide');
      CKEDITOR.replace('howto');
      CKEDITOR.replace('auth');
    </script>
