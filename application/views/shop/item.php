<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <script type="text/javascript" src="/public/js/shop.js"></script>
  <main id="shop" class="shop-main">
    <div class="container-fluid">
      <div class="row mt-1 mb-5">
        <div class="col-xl-8 col-md-12">
          <div class="row align-items-center">
            <div class="col-6">
              <table><tr valign="bottom"><td class="m-0 p-0"><h4 class="font-weight-bold"><?=$pageTitle?></h4></td><td valign="top" class="m-0 p-0"><small><a href="javascript:;" class="btn-notice" data-idx="1"><i class="far fa-question-circle mt-0 ml-3"></i></a></small></td></tr></table>
            </div>
            <div class="col-6 text-right">
              <a href="<?=BASE_URL?>/shop/cart" class="btn-custom btn-giwb btn-cart small">장바구니 보기</a>
            </div>
          </div>
          <hr class="text-default mt-2">

          <div class="area-notice text-justify small" data-idx="1">
            <div class="border mb-3 p-4">
              용품샵이란, 온라인 최저가 상품을 박스단위로 구입하여 산행시 필요로 하는 분들께 택배비 부담없이 낱개로 전달해 드리는 상품입니다.<br><br>
              산행 준비를 꼼꼼히 하시는 분들이야 문제 없겠지만, 산행하는 날마다 편의점에서 생수나 여행용티슈를 비싸게 구입하시는  경우도 많다보니, 가장 기본적인 준비물이지만 미리 준비하기 힘드신 분들께 제공해드리는 편의 서비스라고 이해하시면 좋을것 같습니다.<br><br>
              아울러 등산용품 중 추천할만한 용품을 비교적 저렴한 가격으로 손쉽게 구입하실 수 있도록 검증된 제품만 선별해서 올려드릴 예정이니 많은 관심 부탁드립니다.<br><br>
              신청은 해당 산행의 출발 1일전 18시에 마감할 예정이구요.. 기타 문의사항은 댓글이나 카톡을 이용해 주세요~
              <div class="btn-notice text-right mt-2" data-idx="1"><i class="fa fa-chevron-up" aria-hidden="true"></i> 닫기</div>
            </div>
          </div>
          <div class="header-menu">
            <div class="header-menu-item<?=empty($search['item_category1']) ? ' active' : ''?>"><a href="<?=BASE_URL?>/shop">인기상품</a></div>
            <?php foreach ($listCategory as $value): ?>
            <div class="header-menu-item<?=!empty($search['item_category1']) && $search['item_category1'] == $value['idx'] ? ' active' : ''?>"><a href="<?=BASE_URL?>/shop/?c=<?=$value['idx']?>"><?=$value['name']?></a></div>
            <?php endforeach; ?>
          </div>

          <div class="sub-content mt-3 p-3">
            <div class="row mt-4">
              <div class="col-sm-5 text-center pb-3">
                <img class="w-100" src="<?=$viewItem['item_photo'][0]?>">
              </div>
              <div class="col-sm-7 text-left">
                <form id="shopForm" method="post" action="/shop/cart_insert">
                  <div class="item-category"><?php if (!empty($viewItem['item_category_name'])): foreach ($viewItem['item_category_name'] as $key => $cname): if ($key != 0) { echo ' > '; } ?><?=$cname?><?php endforeach; endif; ?></div>
                  <h2 class="item-name"><?=$viewItem['item_name']?></h2>
                  <h4 class="mt-4 mb-4 area-cost">
                    <?=!empty($viewItem['item_price']) ? '<span class="discount"><s class="text-secondary"><span class="item-price">' . number_format($viewItem['item_price']) . '</span>원</s> (' . round(($viewItem['item_price'] - $viewItem['item_cost']) / $viewItem['item_price'] * 100) . '%)</span><br>' : ''?><span class="item-cost"><?=number_format($viewItem['item_cost'])?></span>원<br>
                    <?php if (!empty($viewItem['item_options'])): ?>
                    <select name="item_option" class="item-option form-control mt-3">
                      <option value="">옵션을 선택해주세요</option>
                      <option value="">---------------</option>
                      <?php foreach ($viewItem['item_options'] as $key => $value): ?>
                      <option value="<?=$key?>"><?=$value['item_option']?><?=!empty($value['added_cost']) ? ' : ' . number_format($value['added_cost']) . '원' : ''?></option>
                      <?php endforeach; ?>
                    </select>
                    <?php endif; ?>
                  </h4>
                  <div class="area-option">
                    <?php if (empty($viewItem['item_options'])): ?>
                    <div class="row">
                      <div class="col-3">
                        <select name="amount[]" class="item-amount form-control form-control-sm pl-1 pr-0">
                          <?php foreach (range(1, 10) as $cnt): ?>
                          <option value='<?=$cnt?>'><?=$cnt?>개</option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="col-9"></div>
                    </div>
                    <?php endif; ?>
                  </div>
                  <div class="row mt-4 text-center">
                    <input type="hidden" name="idx" value="<?=$viewItem['idx']?>">
                    <div class="col-6 pr-2"><button type="button" class="btn w-100 btn-cart-insert pl-3 pr-3" data-type="cart" data-idx="<?=$viewItem['idx']?>">장바구니에 담기</button></div>
                    <div class="col-6 pl-2"><button type="button" class="btn w-100 btn-cart-insert btn-buy pl-3 pr-3" data-type="buy" data-idx="<?=$viewItem['idx']?>">바로 구매하기 &gt;</button></div>
                  </div>
                </form>
              </div>
            </div>
            <div class="item-content">
              <h4>상품소개</h4>
              <?=reset_html_escape($viewItem['item_content'])?>
            </div>
          </div>

          <div class="story-reaction">
            <button type="button" data-idx="<?=$viewItem['idx']?>" data-type="<?=REPLY_TYPE_NOTICE?>"><i class="fa fa-reply" aria-hidden="true"></i> 댓글 <span class="cnt-reply" data-idx="<?=$viewItem['idx']?>"><?=$viewItem['reply_cnt']?></span></button>
            <button type="button" class="btn-like<?=!empty($viewItem['like']) ? ' text-danger' : ''?>" data-idx="<?=$viewItem['idx']?>" data-type="<?=REACTION_TYPE_SHOP?>"><i class="fa fa-heart" aria-hidden="true"></i> 좋아요 <span class="cnt-like"><?=$viewItem['like_cnt']?></span></button>
            <button type="button" class="btn-share" data-idx="<?=$viewItem['idx']?>"><i class="fa fa-share-alt" aria-hidden="true"></i> 공유하기 <span class="cnt-share"><?=$viewItem['share_cnt']?></span></button>
            <div class="area-share" data-idx="<?=$viewItem['idx']?>">
              <ul>
                <li><a href="javascript:;" class="btn-share-sns" data-idx="<?=$viewItem['idx']?>" data-reaction-type="<?=REACTION_TYPE_SHOP?>" data-type="<?=SHARE_TYPE_FACEBOOK?>" data-url="https://facebook.com/sharer/sharer.php?u=<?=BASE_URL?>/shop/item/<?=$viewItem['idx']?>"><img src="/public/images/icon_facebook.png"><br>페이스북</a></li>
                <li><a href="javascript:;" class="btn-share-sns" data-idx="<?=$viewItem['idx']?>" data-reaction-type="<?=REACTION_TYPE_SHOP?>" data-type="<?=SHARE_TYPE_TWITTER?>" data-url="https://twitter.com/intent/tweet?url=<?=BASE_URL?>/shop/item/<?=$viewItem['idx']?>"><img src="/public/images/icon_twitter.png"><br>트위터</a></li>
                <li><a href="javascript:;" class="btn-share-url" data-idx="<?=$viewItem['idx']?>" data-reaction-type="<?=REACTION_TYPE_SHOP?>" data-type="<?=SHARE_TYPE_URL?>" data-trigger="click" data-placement="bottom" data-clipboard-text="<?=BASE_URL?>/shop/item/<?=$viewItem['idx']?>"><img src="/public/images/icon_url.png"><br>URL</a></li>
              </ul>
            </div>
          </div>
          <div class="story-reply mt-4 reply-type-<?=REPLY_TYPE_NOTICE?>" data-idx="<?=$viewItem['idx']?>">
            <div class="story-reply-content">
              <?=$listReply?>
            </div>
            <form method="post" action="/story/insert_reply" class="story-reply-input" data-idx="<?=$viewItem['idx']?>">
              <input type="hidden" name="clubIdx" value="<?=$view['idx']?>">
              <input type="hidden" name="storyIdx" value="<?=$viewItem['idx']?>">
              <input type="hidden" name="replyType" value="<?=REPLY_TYPE_SHOP?>">
              <input type="hidden" name="replyIdx" value="">
              <textarea name="content" class="club-story-reply"></textarea>
              <button type="button" class="btn btn-default btn-post-reply" data-idx="<?=$viewItem['idx']?>">댓글달기</button>
            </form>
          </div>
        </div>
