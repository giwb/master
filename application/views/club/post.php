<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <script type="text/javascript" src="/public/se2/js/HuskyEZCreator.js" charset="utf-8"></script>
  <main id="club">
    <div class="container-fluid club-main">
      <div class="row">
        <div class="col-xl-8 col-md-12">
          <section class="section extra-margins listing-section">
            <h4 class="font-weight-bold mb-0">여행기 작성</h4>
            <hr class="red">

            <div class="mt-4 pt-2">
                <?php if (!empty($idx) && empty($viewArticle['category'])): ?>
                <div class="card shadow mb-3">
                    <div class="card-body text-center pt-5 pb-5">
                      잘못된 접근입니다.
                    </div>
                </div>
                <?php else: ?>
                <form id="postArticle" method="post" enctype="multipart/form-data">
                <input type="hidden" name="idx" value="<?=!empty($viewArticle['idx']) ? $viewArticle['idx'] : ''?>">
                <input type="hidden" name="club_idx" value="<?=!empty($view['idx']) ? $view['idx'] : ''?>">
                <input type="hidden" name="user_idx" value="<?=!empty($userData['idx']) ? $userData['idx'] : ''?>">
                <input type="hidden" name="code" value="<?=!empty($viewArticle['category']) ? $viewArticle['category'] : ''?>">
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
                                          <label class="mr-4"><input type="radio" name="category" value="<?=!empty($viewArticle['category']) ? $viewArticle['category'] : 'news'?>"<?=!empty($viewArticle['category']) && $viewArticle['category'] == 'news' ? ' checked' : ''?>> 여행소식</label>
                                          <label><input type="radio" name="category" value="<?=!empty($viewArticle['category']) ? $viewArticle['category'] : 'review'?>"<?=!empty($viewArticle['category']) && $viewArticle['category'] == 'review' ? ' checked' : ''?>> 여행후기</label>
                                        </td>
                                    </tr>
                                    <tr valign="middle">
                                      <th bgcolor="#d9d9d9" class="font-weight-bold text-center">제목</th>
                                        <td><input type="text" name="title" class="form-control" value="<?=!empty($viewArticle['title']) ? $viewArticle['title'] : ''?>"></td>
                                    </tr>
                                    <tr>
                                        <th bgcolor="#d9d9d9" class="font-weight-bold text-center">내용</th>
                                        <td><textarea name="content" rows="10" cols="100" id="articleContent"><?=!empty($viewArticle['content']) ? $viewArticle['content'] : ''?></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="error-message text-center"></div>
                    </div>
                </div>
                <div class="text-center pb-5">
                    <button type="button" class="btn btn-primary btn-post-article"><?=!empty($viewArticle['idx']) ? '수정' : '등록'?>하기</button>
                </div>
                </form>
                <?php endif; ?>

            </div>
          </section>
        </div>

        <script type="text/javascript">
            var oEditors = [];
            nhn.husky.EZCreator.createInIFrame({
                oAppRef: oEditors,
                elPlaceHolder: 'articleContent',
                sSkinURI: '/public/se2/SmartEditor2Skin.html',
                fCreator: 'createSEditor2',
            });
            $(document).on('click', '.btn-post-article', function() {
              // 기사 등록
              oEditors.getById['articleContent'].exec('UPDATE_CONTENTS_FIELD', []);
              var $btn = $(this);
              var btnText = $btn.text();
              var formData = new FormData($('#postArticle')[0]);
              var content = $('#articleContent').val();
              formData.append('content', content);

              if ($('input:radio[name=category]').is(':checked') == false) {
                $('.error-message').text('분류는 꼭 선택해주세요.').slideDown();
                setTimeout(function() { $('.error-message').text('').slideUp(); }, 2000);
                return false;
              }
              if ($('input[name=title]').val() == '') {
                $('.error-message').text('제목은 꼭 입력해주세요.').slideDown();
                setTimeout(function() { $('.error-message').text('').slideUp(); }, 2000);
                return false;
              }
              if (content == '') {
                $('.error-message').text('내용은 꼭 입력해주세요.').slideDown();
                setTimeout(function() { $('.error-message').text('').slideUp(); }, 2000);
                return false;
              }

              $.ajax({
                processData: false,
                contentType: false,
                url: '/club/article_update',
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
                    location.replace($('input[name=baseUrl]').val() + '/club/search/?code=' + result.message);
                  }
                }
              });
            });
        </script>
