<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <h2 class="sub-header mb-4"><?=$pageTitle?></h2>
        <div id="content" class="mb-5">
          <div class="w-100 mt-3 mb-3">
            <form id="formAuth" method="post" action="<?=BASE_URL?>/admin/attendance_auth_insert" class="m-0">
              <div class="row align-items-center mb-2">
                <div class="col-sm-3">산행 선택 <span class="required">(*)</span></div>
                <div class="col-sm-9">
                  <select name="rescode" class="form-control rescode">
                    <option value=''>인증하실 산행을 선택하세요</option>
                    <?php foreach ($listAttendanceNotice as $value): ?>
                    <option value='<?=$value['idx']?>' data-name='<?=$value['mname']?>'><?=$value['startdate']?> <?=$value['mname']?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="row align-items-center mb-2">
                <div class="col-sm-3">산행명 <span class="required">(*)</span></div>
                <div class="col-sm-9"><input type="text" name="title" class="form-control"></div>
              </div>
              <div class="row align-items-center mb-2">
                <div class="col-sm-3">닉네임</div>
                <div class="col-sm-9"><input type="text" name="nickname" class="form-control search-userid"></div>
              </div>
              <div class="row align-items-center mb-2">
                <div class="col-sm-3">아이디</div>
                <div class="col-sm-9"><input type="text" name="userid" class="form-control search-userid-result"></div>
              </div>
              <div class="row align-items-center mb-2">
                <div class="col-sm-3">사진 URL</div>
                <div class="col-sm-9"><input type="text" name="photo" class="form-control"></div>
              </div>
              <div class="row align-items-center">
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
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
