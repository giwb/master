<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

          <section class="mb-3">
            <h4 class="font-weight-bold"><?=$pageTitle?></h4>
            <hr class="text-default">

            <div id="content">
              <div class="w-100 mt-3">
                <form id="formAuth" method="post" action="<?=BASE_URL?>/admin/attendance_auth_update" class="m-0">
                  <input type="hidden" name="idx" value="<?=!empty($viewAuth['idx']) ? $viewAuth['idx'] : ''?>">
                  <div class="row align-items-center mb-2">
                    <div class="col-sm-3">산행 선택 <span class="required">(*)</span></div>
                    <div class="col-sm-9">
                      <select name="rescode" class="form-control rescode">
                        <option value=''>인증하실 산행을 선택하세요</option>
                        <?php foreach ($listAttendanceNotice as $value): ?>
                        <option<?=!empty($viewAuth['rescode']) && $viewAuth['rescode'] == $value['idx'] ? ' selected' : ''?> value='<?=$value['idx']?>' data-name='<?=$value['mname']?>'><?=$value['startdate']?> <?=$value['mname']?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="row align-items-center mb-2">
                    <div class="col-sm-3">산행명 <span class="required">(*)</span></div>
                    <div class="col-sm-9"><input type="text" name="title" class="form-control" value="<?=!empty($viewAuth['title']) ? $viewAuth['title'] : ''?>"></div>
                  </div>
                  <div class="row align-items-center mb-2">
                    <div class="col-sm-3">닉네임</div>
                    <div class="col-sm-9"><input type="text" name="nickname" class="form-control search-userid" value="<?=!empty($viewAuth['nickname']) ? $viewAuth['nickname'] : ''?>"></div>
                  </div>
                  <div class="row align-items-center mb-2">
                    <div class="col-sm-3">아이디</div>
                    <div class="col-sm-9"><input type="text" name="userid" class="form-control search-userid-result" value="<?=!empty($viewAuth['userid']) ? $viewAuth['userid'] : ''?>"></div>
                  </div>
                  <div class="row align-items-center mb-2">
                    <div class="col-sm-3">사진 URL</div>
                    <div class="col-sm-9"><input type="text" name="photo" class="form-control" value="<?=!empty($viewAuth['photo']) ? $viewAuth['photo'] : ''?>"></div>
                  </div>
                  <div class="row align-items-center">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                      <button type="button" class="btn-custom btn-giwbblue btn-auth pt-2 pb-2 pl-5 pr-5">인증 사진을 <?=!empty($viewAuth['idx']) ? '수정' : '등록'?>합니다</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <h4 class="font-weight-bold mt-5">목록</h4>
            <hr class="text-default">

            <div class="d-none d-sm-block">
              <div class="row no-gutters align-items-center bg-secondary text-white p-2">
                <div class="col-sm-1">번호</div>
                <div class="col-sm-2">아이디</div>
                <div class="col-sm-2">닉네임</div>
                <div class="col-sm-2">산행명</div>
                <div class="col-sm-4">사진</div>
                <div class="col-sm-1">편집</div>
              </div>
            </div>
            <?php $max = count($listAuth); foreach ($listAuth as $key => $value): ?>
            <div class="row no-gutters align-items-center p-2 border-bottom small">
              <div class="col-sm-1"><?=$max - $key?></div>
              <div class="col-sm-2"><?=$value['userid']?></div>
              <div class="col-sm-2"><?=$value['nickname']?></div>
              <div class="col-sm-2"><?=$value['title']?></div>
              <div class="col-sm-4"><a target="_blank" href="<?=$value['photo']?>"><?=$value['photo']?></a></div>
              <div class="col-sm-1" data-idx="<?=$value['idx']?>"><button type="button" class="btn-custom btn-gray btn-auth-update p-1">수정</button> <button type="button" class="btn-custom btn-giwbred btn-auth-delete-modal p-1">삭제</button></div>
            </div>
            <?php endforeach; ?>
          </section>

          <div class="modal fade" id="authDeleteModal" tabindex="-1" role="dialog" aria-labelledby="authDeleteLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="smallmodalLabel">메세지</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body text-center">
                  <p class="modal-message mb-4">정말로 삭제하시겠습니까?</p>
                </div>
                <div class="modal-footer">
                  <input type="hidden" name="idx" value="">
                  <button type="button" class="btn btn-danger btn-auth-delete">삭제합니다</button>
                  <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
                </div>
              </div>
            </div>
          </div>


          <script type="text/javascript">
            $(document).on('change', '.rescode', function() {
              $('input[name=title]').val($(this).find('option:selected').data('name'));
            }).on('click', '.btn-auth-update', function() {
              location.replace('<?=BASE_URL?>/admin/attendance_auth/?n=' + $(this).parent().data('idx'));
            }).on('click', '.btn-auth-delete-modal', function() {
              $('#authDeleteModal input[name=idx]').val($(this).parent().data('idx'));
              $('#authDeleteModal').modal('show');
            }).on('click', '.btn-auth-delete', function() {
              var $btn = $(this);
              $.ajax({
                url: '/admin/attendance_auth_delete',
                data: 'idx=' + $('#authDeleteModal input[name=idx]').val(),
                dataType: 'json',
                type: 'post',
                beforeSend: function() {
                  $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
                },
                success: function(result) {
                  if (result.error == 1) {
                    $btn.css('opacity', '1').prop('disabled', false).text('수정합니다');
                    $.openMsgModal(result.message)
                  } else {
                    location.reload();
                  }
                }
              });
            }).on('click', '.btn-auth', function() {
              // 백산백소 인증현황 등록
              var $btn = $(this);
              var $dom = $('#formAuth');
              var formData = new FormData($dom[0]);

              if ($('select[name=rescode] option:selected').val() == '') {
                $.openMsgModal('산행은 꼭 선택해주세요.');
                return false;
              }
              if ($('input[name=title]').val() == '') {
                $.openMsgModal('산행명은 꼭 입력해주세요.');
                return false;
              }

              $.ajax({
                url: $dom.attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                type: 'post',
                beforeSend: function() {
                  $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
                },
                success: function(result) {
                  location.replace('<?=BASE_URL?>/admin/attendance_auth');
                }
              });
            });
          </script>
