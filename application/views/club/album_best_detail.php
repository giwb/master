<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <script type="text/javascript" src="/public/js/masonry.pkgd.min.js"></script>
  <script type="text/javascript" src="/public/js/imagesloaded.pkgd.min.js"></script>
  <script type="text/javascript" src="/public/js/album.js?<?=time()?>"></script>
  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-xl-8 col-md-12 mb-5">
          <h4 class="font-weight-bold"><?=$pageTitle?></h4>
          <hr class="text-default">

          <div class="d-block d-sm-none">
            <div class="header-menu mt-3">
              <div class="header-menu-item"><a href="<?=BASE_URL?>/album">사진첩</a></div>
              <div class="header-menu-item active"><a href="<?=BASE_URL?>/album/best">추천사진</a></div>
              <div class="header-menu-item"><a href="<?=BASE_URL?>/club/video">동영상</a></div>
              <div class="header-menu-item"><a href="<?=BASE_URL?>/club/search/?code=news">여행정보</a></div>
              <div class="header-menu-item"><a href="<?=BASE_URL?>/club/search/?code=review">여행후기</a></div>
            </div>
          </div>

          <div class="card card-body mt-4 mb-4">
            <div class="post-data mb-3">
              <h6 class="mt-3 pl-3 pr-3 text-secondary">
                <a href="<?=BASE_URL?>"><?=$view['title']?></a> > <a href="<?=BASE_URL?>/album/best">추천 사진</a>
              </h6>
              <h2 class="font-weight-bold pl-3 pr-3">
                <span class="article-title"><?=$viewBestPhoto['subject']?></span>
              </h2>
              <hr class="red title-hr">
              <div class="post-article">
                <div class="mb-4"><img class="w-100" src="<?=ALBUM_URL . $viewBestPhoto['filename']?>"></div>
                <div class="text-justify"><?=nl2br($viewBestPhoto['content'])?></div>
              </div>
            </div>
            <div class="row border-top">
              <div class="area-refer col-md-6 mt-4 pl-4">
                <h5 class="font-weight-bold text-dark">
                  <i class="far fa-lg fa-newspaper mr-2"></i>
                  <strong><?=$viewBestPhoto['refer']?></strong>명이 봤어요
                  <a href="javascript:;" class="text-dark btn-liked" data-idx="<?=$viewBestPhoto['idx']?>" data-type-service="<?=SERVICE_TYPE_ALBUM?>" data-type-reaction="<?=REACTION_TYPE_LIKED?>" title="좋아요!"><i class="fas fa-heart ml-4 mr-2<?=!empty($checkLiked) ? ' text-danger' : ''?>"></i>
                  <strong class="cnt-liked"><?=$viewBestPhoto['liked']['cnt']?></strong>명이 좋아해요</a>
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

          <section class="text-left w-100 mb-3">
            <div class="card card-body">
              <div class="row align-items-center">
                <div class="col-2 col-sm-1 ml-3 p-0">
                  <img src="<?=file_exists(AVATAR_PATH . $viewBestPhoto['user_idx']) ? AVATAR_URL . $viewBestPhoto['user_idx'] : '/public/images/user.png'?>" class="img-fluid rounded-circle icon-avatar" alt="">
                </div>
                <div class="col-9 col-sm-10">
                  <p><strong>글쓴이 <?=$viewBestPhoto['nickname']?></strong></p>
                </div>
              </div>
            </div>
          </section>
<!--
          <h5 class="font-weight-bold mt-4">
            <strong>댓글</strong> <span class="badge indigo reply-cnt"><?php //=$cntReply['cnt']?></span>
          </h5><hr width="100%" class="red title-hr">

          <section class="comments-list">
            <div class="text-left">
              <div class="list-reply mt-4">
                <?php foreach ($listReply as $value): ?>
                <div class="item-reply media mb-4" data-idx="<?=$value['idx']?>">
                  <img class="d-flex rounded-circle icon-avatar-reply z-depth-1-half mr-3" src="<?=file_exists(AVATAR_PATH . $value['user_idx']) ? AVATAR_URL . $value['user_idx'] : '/public/images/user.png'?>">
                  <div class="media-body">
                    <h6 class="mt-0 font-weight-bold"><?=$value['nickname']?><span class="small text-muted ml-2"><?=date('Y-m-d H:i', $value['created_at'])?><?php if (!empty($userData['idx'])): ?><a class="text-info ml-2 btn-reply-thread" data-idx="<?=$value['idx']?>">[댓글]</a><?php endif; ?><?php if (!empty($userData['idx']) && ($userData['idx'] == $value['created_by']) || ($userData['idx'] == 1)): ?><a class="text-danger ml-2 btn-reply-delete-modal" data-idx="<?=$value['idx']?>" data-article-idx="<?=$viewBestPhoto['idx']?>">[삭제]</a><?php endif; ?></span></h6>
                    <p class="dark-grey-text article"><?=$value['content']?></p>
                    <?php foreach ($value['listReplyThread'] as $thread): ?>
                    <div class="item-reply media" data-idx="<?=$thread['idx']?>">
                      <img class="d-flex rounded-circle icon-avatar-reply z-depth-1-half mr-3" src="<?=file_exists(AVATAR_PATH . $thread['user_idx']) ? AVATAR_URL . $thread['user_idx'] : '/public/images/user.png'?>">
                      <div class="media-body">
                        <h6 class="mt-0 font-weight-bold"><?=$thread['nickname']?><span class="small text-muted ml-2"><?=date('Y-m-d H:i', $thread['created_at'])?><?php if (!empty($userData['idx']) && ($userData['idx'] == $thread['created_by']) || ($userData['idx'] == 1)): ?><a class="text-danger ml-2 btn-reply-delete-modal" data-idx="<?=$thread['idx']?>" data-article-idx="<?=$viewBestPhoto['idx']?>">[삭제]</a><?php endif; ?></span></h6>
                        <p class="dark-grey-text article"><?=$thread['content']?></p>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
              <?php if (!empty($userData['idx'])): ?>
              <div class="row reply-input mt-2" data-idx='0'>
                <div class="col-8 col-sm-9 pr-0"><input type="hidden" name="idx" class="reply-idx" value="0"><input type="hidden" name="nickname" class="reply-nickname" value="<?=$userData['nickname']?>"><textarea cols="100" class="form-control reply-content" rows="3" placeholder="댓글을 입력해주세요."></textarea></div>
                <div class="col-4 col-sm-2"><button type="button" data-article-idx="<?=$viewBestPhoto['idx']?>" class="btn btn-primary btn-reply h-100 m-0">등록</button></div>
              </div>
              <?php endif; ?>
            </div>
          </section>
-->
        </div>
        <input type="hidden" class="article-url" value="<?=BASE_URL?>/album/best/<?=$viewBestPhoto['idx']?>">
        <input type="hidden" class="article-content" value="<?=articleContent($viewBestPhoto['content'], '100')?>">

        <script id="javascript-sdk" src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
        <script type="text/javascript">
          Kakao.init('bc341ce483d209b1712bf3a88b598ddb');
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
                imageUrl: '<?=base_url() . 'public/album/thumb_' . $viewBestPhoto['filename']?>',
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
