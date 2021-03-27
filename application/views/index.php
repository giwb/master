<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="main-slider">
  <?php foreach($listArticleMain as $key => $value): ?>
  <div class="slider-item">
    <img src="<?=PHOTO_ARTICLE_URL . $value['main_image']?>">
    <div class="slider-content">
      <a href="<?=base_url()?>article/<?=$value['idx']?>"><h1><?=$value['title']?></h1></a>
      <h2><?=articleContent($value['content'])?></h2>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<script>
$(document).ready(function(){
  $('#main-slider').flickity({
    wrapAround: true,
    pageDots: false
  });
});
</script>

<main id="mainpage">
  <div class="container-fluid">
    <div class="row mt-1">
      <div class="col-xl-8 col-md-12">
        <section class="section extra-margins listing-section">
          <h4 class="font-weight-bold"><strong>최신 기사</strong></h4>
          <hr class="red">
          <div class="row mb-4 article-list">
            <?=$listArticle?>
          </div>
        </section>
        <?php if ($maxArticle['cnt'] > $perPage): ?>
        <div class="text-center mb-5">
          <a href="javascript:;" class="btn btn-info btn-more" rel="nofollow">더 보기</a>
        </div>
        <?php endif; ?>
        <input type="hidden" name="p" value="1">
      </div>
