<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-8 col-md-12">
          <section class="section extra-margins listing-section">
            <h4 class="font-weight-bold"><strong>기사 검색 : <?=$type?></strong></h4><hr class="red">
            <div class="row">
              <?php foreach ($listArticle as $value): ?>
              <div class="col-md-12 my-2">
                <div class="card">
                  <div class="card-body">
                    <div class="row" onClick="location.href=('/article/<?=$value['idx']?>');">
                      <div class="col-2"><img style="width: 100%; max-height: 100px;" src="<?=getThumbnail($value['content'])?>"></div>
                      <div class="col-10">
                        <h5><strong><?=$value['title']?></strong></h5>
                        <p class="card-text text-justify"><?=articleContent($value['content'])?> <span class="small">(<?=date('Y-m-d H:i', $value['viewing_at'])?>)</span></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </section>
<!--
          <div class="text-center mt-3 mb-5">
            <a href="javascript:alert('클릭하면 하단에 다음 기사들이 주루룩 나오는 형태입니다');" class="btn btn-info" rel="nofollow">더 보기</a>
          </div>
-->
        </div>
