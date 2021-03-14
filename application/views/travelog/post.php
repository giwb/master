<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <script type="text/javascript" src="/public/se2/js/HuskyEZCreator.js" charset="utf-8"></script>
  <main id="club">
    <div class="container-fluid club-main">
      <div class="row">
        <div class="col-xl-8 col-md-12">
          <section class="section extra-margins listing-section">
            <div class="row">
              <div class="col-6"><h4 class="font-weight-bold mb-0"><strong>여행기 작성</strong></h4></div>
              <div class="col-6 text-right pr-4"><a href="<?=BASE_URL?>/travelog/post"><span class="text-dark"><i class="fas fa-th-list"></i> 목록</a></span></div>
            </div>
            <hr class="red">
            <div class="mt-4 pt-2">

                <form id="postTravelog" method="post" action="/travelog_update" enctype="multipart/form-data">
                <input type="hidden" name="idx" value="<?=!empty($viewTravelog['idx']) ? $viewTravelog['idx'] : ''?>">
                <input type="hidden" name="club_idx" value="<?=!empty($view['idx']) ? $view['idx'] : ''?>">
                <input type="hidden" name="user_idx" value="<?=!empty($userData['idx']) ? $userData['idx'] : ''?>">
                <div class="card shadow mb-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <colgroup>
                                    <col width="10%">
                                    <col width="90%">
                                </colgroup>
                                <tbody>
                                    <tr valign="middle">
                                        <th bgcolor="#d9d9d9" class="font-weight-bold text-center">분류</th>
                                        <td>
                                          <label class="mr-4"><input type="radio" name="category" value="<?=!empty($viewTravelog['type']) ? $viewTravelog['type'] : 'news'?>"<?=$type == 'news' ? ' checked' : ''?>> 여행정보</label>
                                          <label><input type="radio" name="category" value="<?=!empty($viewTravelog['type']) ? $viewTravelog['type'] : 'logs'?>"<?=$type == 'logs' ? ' checked' : ''?>> 여행후기</label>
                                        </td>
                                    </tr>
                                    <tr valign="middle">
                                      <th bgcolor="#d9d9d9" class="font-weight-bold text-center">제목</th>
                                        <td><input type="text" name="title" class="form-control" value="<?=!empty($viewTravelog['title']) ? $viewTravelog['title'] : ''?>"></td>
                                    </tr>
                                    <tr>
                                        <th bgcolor="#d9d9d9" class="font-weight-bold text-center">내용</th>
                                        <td><textarea name="content" rows="10" cols="100" id="travelogContent"><?=!empty($viewTravelog['content']) ? $viewTravelog['content'] : ''?></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="error-message text-center"></div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-primary btn-post-travelog"><?=!empty($viewTravelog['idx']) ? '수정' : '등록'?>하기</button>
                </div>
                </form>


            </div>
          </section>
<!--
          <div class="text-center mt-3 mb-5">
            <a href="javascript:alert('클릭하면 하단에 다음 기사들이 주루룩 나오는 형태입니다');" class="btn btn-info" rel="nofollow">더 보기</a>
          </div>
-->
        </div>

        <script type="text/javascript">
            var oEditors = [];
            nhn.husky.EZCreator.createInIFrame({
                oAppRef: oEditors,
                elPlaceHolder: 'travelogContent',
                sSkinURI: '/public/se2/SmartEditor2Skin.html',
                fCreator: 'createSEditor2'
            });
            $(document).on('click', '.btn-post-travelog', function() {
              // 기사 등록
              oEditors.getById['travelogContent'].exec('UPDATE_CONTENTS_FIELD', []);
              var $btn = $(this);
              var formData = new FormData($('#postTravelog')[0]);
              var content = $('#travelogContent').val();
              formData.append('content', content);
              $.ajax({
                processData: false,
                contentType: false,
                url: '/travelog/update',
                data: formData,
                dataType: 'json',
                type: 'post',
                beforeSend: function() {
                  $btn.css('opacity', '0.5').prop('disabled', true);
                },
                success: function(result) {
                  $btn.css('opacity', '1').prop('disabled', false);
                  if (result.error == 1) {
                    $('.error-message').text(result.message);
                    setTimeout(function() { $('.error-message').text(''); }, 2000);
                  } else {
                    location.replace('<?=BASE_URL?>/travelog');
                  }
                }
              });
            });
        </script>
