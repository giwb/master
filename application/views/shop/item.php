<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div id="shop" class="club-main">
        <h2 class="sub-header"><?=$pageTitle?></h2>
        <div class="row align-items-center">
          <div class="col-9 mb-2"><a href="javascript:;" class="btn-notice" data-idx="1"><strong><i class="fa fa-question-circle-o" aria-hidden="true"></i> 구매대행 상품이란..?</strong></a></div>
          <div class="col-3 text-right d-none d-sm-block"><button type="button" class="btn btn-sm btn-cart">장바구니 보기</button></div>
        </div>
        <div class="area-notice text-justify small" data-idx="1">
          <div class="border mb-3 p-4">
            본 상품은 경인웰빙이 유통하는 상품이 아니고, 소비자 입장에서 박스로 구입하여 낱개로 전달해 드리는 상품입니다.<br><br>
            박스단위로 택배비 부담이 없이 비교적 저렴하게 구입하여 회원분들께 낱개 단위로 저렴하게 전해 드리는 상품으로 산행시 꼭 필요로 하는 품목을 산행 신청하신 분들을 대상으로 버스에서 전달해 드리는 서비스로 이해해 주시면 감사하겠습니다. <br><br>
            산행 준비를 꼼꼼히 하시는 분들이야 문제 없겠지만, 산행 하는 날마다 편의점에서 비싼 생수를 구입하고, 현지에서 막걸리를 사거나 그마저도 구입할 수 없는 상황에 발을 동동 구르시는 상황을 많이 보아왔던 입장인지라, 가장 기본적인 준비물이지만 미리 준비하기 힘드신 분들을 위해 제가 대신 준비해 드리겠다는 차원입니다.<br><br>
            제공되는 가격은 마트나 온라인 쇼핑몰의 최저가 가격에 10~20% 정도를 붙이거나 낱개 구입 시 발생하는 택배비 정도를 심부름값 정도의 비용을 얹어서 책정되었다는 점도 참고 삼아 미리 말씀 드립니다.<br><br>
            홈페이지 신청은 산행출발 시간 기준 24시간 전까지만 신청이 가능하시구요.. 그 이후로 주문을 원하시는 경우 카톡으로 직접 주문해 주시기 바랍니다. 상황에 따라 미리 준비되지 않은 품목은 전달해 드리지 못할 수 있다는 점도 사전 양해 부탁드립니다.
            <div class="btn-notice text-right mt-2" data-idx="1"><i class="fa fa-chevron-up" aria-hidden="true"></i> 닫기</div>
          </div>
        </div>
        <div class="d-none d-sm-block">
          <div class="row-category row m-0 p-0 border-bottom border-right">
            <a href="<?=BASE_URL?>/shop" class="col border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=empty($search['item_category1']) ? ' active' : ''?>">인기상품</a>
            <?php foreach ($listCategory as $value): ?>
            <a href="<?=BASE_URL?>/shop/?c=<?=$value['idx']?>" class="col border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=$value['idx'] == $search['item_category1'] ? ' active' : ''?>"><?=$value['name']?></a>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="d-sm-none">
          <div class="row-category">
            <div class="row m-0 p-0 border-right">
              <?php foreach ($listCategory as $key => $value): ?>
              <?php if ($key == 0): ?><a href="<?=BASE_URL?>/shop" class="col border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=empty($search['item_category1']) ? ' active' : ''?>">인기상품</a>
              <?php elseif ($key == 1): ?></div><div class='row m-0 p-0 border-right'>
              <?php elseif ($key >= 2 && $key%2 == 1): ?></div><div class='row m-0 p-0 border-right'>
              <?php endif; ?>
              <a href="<?=BASE_URL?>/shop/?c=<?=$value['idx']?>" class="col border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=$value['idx'] == $search['item_category1'] ? ' active' : ''?>"><?=$value['name']?></a>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <div class="sub-content mt-2">
          <div class="row mt-4">
            <div class="col-sm-5 text-center pb-3">
              <img src="<?=$viewItem['item_photo'][0]?>">
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
                  <div class="col-6 pr-2"><button type="button" class="btn w-100 btn-cart-insert" data-type="cart" data-idx="<?=$viewItem['idx']?>">장바구니에 담기</button></div>
                  <div class="col-6 pl-2"><button type="button" class="btn w-100 btn-cart-insert btn-buy" data-type="buy" data-idx="<?=$viewItem['idx']?>">바로 구매하기 &gt;</button></div>
                </div>
              </form>
            </div>
          </div>
          <div class="item-content">
            <h4>상품소개</h4>
            <?=reset_html_escape($viewItem['item_content'])?>
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
      </div>
      <script type="text/javascript" src="/public/js/shop.js"></script>
