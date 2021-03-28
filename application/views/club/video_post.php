<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <script type="text/javascript" src="/public/se2/js/HuskyEZCreator.js" charset="utf-8"></script>
  <main id="club">
    <div class="container-fluid club-main">
      <div class="row">
        <div class="col-xl-8 col-md-12">
          <section class="section extra-margins listing-section">
          <div class="row align-items-center">
            <div class="col-5"><h4 class="font-weight-bold">여행기 - 동영상</h4></div>
            <?php if (!empty($userData['idx'])): ?>
            <div class="col-7 text-right">
              <a href="<?=BASE_URL?>/club/video" class="btn-custom btn-giwbgray">목록</a>
            </div>
            <?php endif; ?>
          </div>
          <hr class="text-default mt-2">

            <div class="mt-4 pt-2">
              <form id="postVideo" method="post" enctype="multipart/form-data">
              <input type="hidden" name="idx" value="<?=!empty($viewVideo['idx']) ? $viewVideo['idx'] : ''?>">
              <input type="hidden" name="club_idx" value="<?=!empty($view['idx']) ? $view['idx'] : ''?>">
              <div class="card shadow mb-3">
                <div class="card-body">
                  <div class="row no-gutters align-items-center bg-light border">
                    <div class="col-sm-2 font-weight-bold text-center p-2">제목</div>
                    <div class="col-sm-10 bg-white p-2"><input type="text" name="subject" class="form-control" value="<?=!empty($viewVideo['subject']) ? $viewVideo['subject'] : ''?>"></div>
                  </div>
                  <div class="row no-gutters align-items-center bg-light border">
                    <div class="col-sm-2 font-weight-bold text-center p-2">동영상 주소</div>
                    <div class="col-sm-10 bg-white p-2"><input type="text" name="video_link" class="form-control" value="<?=!empty($viewVideo['video_link']) ? $viewVideo['video_link'] : ''?>"><div class="small text-danger">※ YouTube 영상의 URL 주소를 입력해주세요.</div></div>
                  </div><!--
                  <div class="row no-gutters align-items-center bg-light border">
                    <div class="col-sm-12 bg-white p-2"><textarea name="content" rows="10" cols="100" id="videoContent" class="se-content"><?=!empty($viewVideo['content']) ? $viewVideo['content'] : ''?></textarea></div>
                  </div>-->
                  <div class="error-message text-center"></div>
                </div>
              </div>
              <div class="text-center pb-5">
                <button type="button" class="btn btn-primary btn-post-video"><?=!empty($viewVideo['idx']) ? '수정' : '등록'?>하기</button>
              </div>
              </form>
            </div>
          </section>
        </div>

        <script type="text/javascript">
            var oEditors = [];
            nhn.husky.EZCreator.createInIFrame({
                oAppRef: oEditors,
                elPlaceHolder: 'videoContent',
                sSkinURI: '/public/se2/SmartEditor2Skin.html',
                fCreator: 'createSEditor2',
            });
            $(document).on('click', '.btn-post-video', function() {
              // 기사 등록
              oEditors.getById['videoContent'].exec('UPDATE_CONTENTS_FIELD', []);
              var $btn = $(this);
              var btnText = $btn.text();
              var formData = new FormData($('#postVideo')[0]);
              var content = $('#videoContent').val();
              formData.append('content', content);

              if ($('input[name=title]').val() == '') {
                $('.error-message').text('제목은 꼭 입력해주세요.').slideDown();
                setTimeout(function() { $('.error-message').text('').slideUp(); }, 2000);
                return false;
              }
              if ($('input[name=video_link]').val() == '') {
                $('.error-message').text('동영상 주소는 꼭 입력해주세요.').slideDown();
                setTimeout(function() { $('.error-message').text('').slideUp(); }, 2000);
                return false;
              }

              $.ajax({
                processData: false,
                contentType: false,
                url: '/club/video_update',
                data: formData,
                dataType: 'json',
                type: 'post',
                beforeSend: function() {
                  $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
                },
                success: function(result) {
                  if (result.error == 1) {
                    $btn.css('opacity', '1').prop('disabled', false).text(btnText);
                    $('.error-message').text(result.message);
                    setTimeout(function() { $('.error-message').text(''); }, 2000);
                  } else {
                    location.replace($('input[name=baseUrl]').val() + '/club/video/');
                  }
                }
              });
            });
        </script>
