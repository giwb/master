<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  if (empty($clubIdx)) $baseUrl = base_url(); else $baseUrl = BASE_URL . '/';
?>

<main>
  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-8 col-md-12">
        <?php if (empty($viewArticle['idx'])): ?>
        <div class="row mb-5 pb-3 mx-2">
          <div class="card card-body mb-4">
            <div class="post-data pt-5 pb-5">
              <div class="pt-5 pb-5 text-center">검색된 데이터가 없습니다.</div>
            </div>
          </div>
        </div>
        <?php else: ?>
        <div class="row mb-5 pb-3 mx-2">
          <div class="card card-body mb-4">
            <div class="post-data mb-4">
              <h6 class="mt-3 pl-3 pr-3 text-secondary">
                <?php if (empty($clubIdx)): ?>
                <a href="<?=base_url()?>">한국여행</a> > 
                <?php else: ?>
                <a href="<?=BASE_URL?>">경인웰빙</a> > 
                <?php endif; ?>
                <?=!empty($categoryParent['name']) ? $categoryParent['name'] . ' > ' : ''?>
                <?=!empty($category['name']) ? '<a href="' . $baseUrl . 'club/search/?code=' . $viewArticle['category'] . '">' . $category['name'] . '</a>' : ''?>
              </h6>
              <h2 class="font-weight-bold pl-3 pr-3">
                <span class="article-title"><?=$viewArticle['title']?></span>
              </h2>
              <hr class="red title-hr">
              <div class="post-article">
                <?=nl2br(reset_html_escape($viewArticle['content']))?>
              </div>
              <?php if ($userData['idx'] == $viewArticle['created_by']): ?>
              <div class="text-right">
                <a href="<?=$baseUrl?>club/article_post/<?=$viewArticle['idx']?>"><button type="button" class="btn btn-secondary pt-2 pb-2 pl-3 pr-3">수정</button></a>
                <button type="button" class="btn btn-danger btn-article-delete-modal pt-2 pb-2 pl-3 pr-3">삭제</button>
              </div>
              <?php endif; ?>
              <hr>
              <div class="row">
                <div class="area-refer col-md-6 mt-4 pl-4">
                  <h5 class="font-weight-bold text-dark">
                    <i class="far fa-lg fa-newspaper mr-2"></i>
                    <strong><?=$refer['cnt']?></strong>명이 봤어요
                    <a href="javascript:;" class="text-dark btn-liked" data-idx="<?=$viewArticle['idx']?>" title="좋아요!"><i class="fas fa-heart ml-4 mr-2<?=!empty($checkLiked) ? ' text-danger' : ''?>"></i>
                    <strong class="cnt-liked"><?=$liked['cnt']?></strong>명이 좋아해요</a>
                  </h5>
                </div>
                <div class="area-sns col-md-6 mt-2 d-flex">
                  <a type="button" class="btn-floating btn-small btn-fb waves-effect waves-light" onClick="shareOnFB();">
                      <i class="fab fa-facebook-f"></i>
                  </a>
                  <a type="button" class="btn-floating btn-small btn-tw waves-effect waves-light" onClick="shareOnTwitter();">
                      <i class="fab fa-twitter"></i>
                  </a>
                  <a type="button" class="btn-floating btn-small btn-nv waves-effect waves-light" onClick="shareOnNaver();">
                      <img src="/public/images/tripkorea/icon_naver.png">
                  </a>
                  <a type="button" class="btn-floating btn-small btn-ko waves-effect waves-light" onClick="shareOnKakao();">
                      <img src="/public/images/tripkorea/icon_kakao.png">
                  </a>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" class="article-url" value="<?=base_url()?>article/<?=$viewArticle['idx']?>">
          <input type="hidden" class="article-content" value="<?=articleContent($viewArticle['content'], '100')?>">

          <section class="text-left w-100 mb-3">
            <div class="card card-body">
              <div class="row align-items-center">
                <div class="col-2 col-sm-1 ml-3 p-0">
                  <img src="<?=file_exists(PHOTO_PATH . $viewArticle['user_idx']) ? PHOTO_URL . $viewArticle['user_idx'] : '/public/images/user.png'?>" class="img-fluid rounded-circle icon-avatar" alt="">
                </div>
                <div class="col-9 col-sm-10">
                  <p><strong>글쓴이 <?=$viewArticle['nickname']?></strong></p>
                </div>
              </div>
            </div>
          </section>

          <h5 class="font-weight-bold mt-3">
            <strong>댓글</strong> <span class="badge indigo reply-cnt"><?=$cntReply['cnt']?></span>
          </h5><hr width="100%" class="red title-hr">

          <section>
            <div class="comments-list text-left">
              <div class="list-reply mt-4">
                <?php foreach ($listReply as $value): ?>
                <div class="item-reply media mb-4" data-idx="<?=$value['idx']?>">
                  <img class="d-flex rounded-circle avatar z-depth-1-half mr-3" src="<?=file_exists(PHOTO_PATH . $value['user_idx']) ? PHOTO_URL . $value['user_idx'] : '/public/images/user.png'?>">
                  <div class="media-body">
                    <h5 class="mt-0 font-weight-bold"><?=$value['nickname']?></h5>
                    <p class="dark-grey-text article"><?=$value['content']?></p>
                    <p class="small text-muted">
                      <?=date('Y-m-d H:i', $value['created_at'])?>
                      <?php if (!empty($userData['idx'])): ?>
                      <a class="text-info ml-2 btn-reply-thread" data-idx="<?=$value['idx']?>">[댓글]</a>
                      <?php endif; ?>
                      <?php if (!empty($userData['idx']) && ($userData['idx'] == $value['created_by']) || ($userData['idx'] == 1)): ?>
                      <a class="text-danger ml-2 btn-reply-delete-modal" data-idx="<?=$value['idx']?>" data-article-idx="<?=$viewArticle['idx']?>">[삭제]</a>
                      <?php endif; ?>
                    </p>
                    <?php foreach ($value['listReplyThread'] as $thread): ?>
                    <div class="item-reply media mb-4" data-idx="<?=$thread['idx']?>">
                      <img class="d-flex rounded-circle avatar z-depth-1-half mr-3" src="<?=file_exists(PHOTO_PATH . $thread['user_idx']) ? PHOTO_URL . $thread['user_idx'] : '/public/images/user.png'?>">
                      <div class="media-body">
                        <h5 class="mt-0 font-weight-bold"><?=$thread['nickname']?></h5>
                        <p class="dark-grey-text article"><?=$thread['content']?></p>
                        <p class="small text-muted">
                          <?=date('Y-m-d H:i', $thread['created_at'])?>
                          <?php if (!empty($userData['idx']) && ($userData['idx'] == $thread['created_by']) || ($userData['idx'] == 1)): ?>
                          <a class="text-danger ml-2 btn-reply-delete-modal" data-idx="<?=$thread['idx']?>" data-article-idx="<?=$viewArticle['idx']?>">[삭제]</a></p>
                          <?php endif; ?>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
              <?php if (!empty($userData['idx'])): ?>
              <div class="row reply-input mt-2" data-idx='0'>
                <div class="col-8 col-sm-10 pr-0"><input type="hidden" name="idx" class="reply-idx" value="0"><input type="hidden" name="nickname" class="reply-nickname" value="<?=$userData['nickname']?>"><textarea cols="100" class="form-control reply-content" rows="3" placeholder="댓글을 입력해주세요."></textarea></div>
                <div class="col-4 col-sm-2 pl-0"><button type="button" data-article-idx="<?=$viewArticle['idx']?>" class="btn btn-primary btn-reply pt-4 pb-4 pl-4 pr-4">등록</button></div>
              </div>
              <?php endif; ?>
            </div>
          </section>
        </div>
        <?php endif; ?>
      </div>

      <?php if ($userData['idx'] == $viewArticle['created_by']): ?>
      <div class="modal fade" id="articleDeleteModal" tabindex="-1" role="dialog" aria-labelledby="articleDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="smallmodalLabel">메시지</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body text-center">
              <p class="modal-message mt-3">정말로 삭제하시겠습니까?</p>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="idx" value="<?=$viewArticle['idx']?>">
              <input type="hidden" name="code" value="<?=$viewArticle['category']?>">
              <button type="button" class="btn btn-danger btn-article-delete-submit">삭제</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>
      <div class="modal fade" id="replyDeleteModal" tabindex="-1" role="dialog" aria-labelledby="replyDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="smallmodalLabel">메시지</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body text-center">
              <p class="modal-message mt-3">정말로 삭제하시겠습니까?</p>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="idx">
              <input type="hidden" name="idx_article" value="<?=$viewArticle['idx']?>">
              <button type="button" class="btn btn-danger btn-reply-delete-submit">삭제</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
            </div>
          </div>
        </div>
      </div>

      <script id="javascript-sdk" src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
      <script type="text/javascript">
        Kakao.init('ca4fd5fe8f8fcc1b5b6daf03c371b3e8');
        Kakao.isInitialized();
        var title = $('.article-title').text();
        var content = $('.article-content').val();
        var url = $('.article-url').val();

        (function (d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s);
          js.id = id;
          js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        function shareOnFB() {
          window.open("https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(url), "shareOnFB", "width=600,height=400,resizable=yes,scrollbars=yes");
        }

        function shareOnTwitter() {
          window.open("https://twitter.com/intent/tweet" +
            "?text=" + encodeURIComponent(title) +
            "&url=" + encodeURIComponent(url),
            "shareOnTwitter",
            'width=600,height=400,resizable=yes,scrollbars=yes'
          );
        }

        function shareOnNaver() {
          window.open("https://share.naver.com/web/shareView.nhn?url=" + url + "&title=" + title, "shareOnNaver", "width=550,height=600,left=center,top=center,location=no");
        }

        function shareOnKakao() {
          Kakao.Link.sendDefault({
            objectType: 'feed',
            content: {
              title: title,
              description: content,
              imageUrl: '<?=getThumbnail($viewArticle['content'])?>',
              link: {
                webUrl: url,
              },
            },
            buttons: [{
              title: '웹으로 보기',
              link: {
                mobileWebUrl: url,
                webUrl: url,
              },
            }],
          })
        }
      </script>
