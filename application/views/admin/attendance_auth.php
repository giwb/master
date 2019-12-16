<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">백산백소 인증현황 등록</h1>
        </div>

        <form id="formAuth" method="post" action="<?=base_url()?>admin/attendance_auth_insert">
          <div class="row align-items-center mb-2">
            <div class="col-sm-1">산행 선택 <span class="required">(*)</span></div>
            <div class="col-sm-11">
              <select name="rescode" class="form-control rescode">
                <option value=''>인증하실 산행을 선택하세요</option>
                <?php foreach ($listNotice as $value): ?>
                <option value='<?=$value['idx']?>' data-name='<?=$value['mname']?>'><?=$value['startdate']?> <?=$value['mname']?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="row align-items-center mb-2">
            <div class="col-sm-1">산행명 <span class="required">(*)</span></div>
            <div class="col-sm-11"><input type="text" name="title" class="form-control"></div>
          </div>
          <div class="row align-items-center mb-2">
            <div class="col-sm-1">닉네임</div>
            <div class="col-sm-11"><input type="text" name="nickname" class="form-control search-userid"></div>
          </div>
          <div class="row align-items-center mb-2">
            <div class="col-sm-1">아이디</div>
            <div class="col-sm-11"><input type="text" name="userid" class="form-control search-userid-result"></div>
          </div>
          <div class="row align-items-center mb-2">
            <div class="col-sm-1">사진 URL</div>
            <div class="col-sm-11"><input type="text" name="photo" class="form-control"></div>
          </div>
          <div class="row align-items-center">
            <div class="col-sm-1"></div>
            <div class="col-sm-11">
              <button type="button" class="btn btn-primary btn-auth">인증 사진을 등록합니다</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <script type="text/javascript">
      $(document).on('change', '.rescode', function() {
        $('input[name=title]').val($(this).find('option:selected').data('name'));
      });
    </script>
