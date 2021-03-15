<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  if (empty($clubIdx)) $baseUrl = base_url(); else $baseUrl = BASE_URL . '/';
?>

  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-8 col-md-12">
          <section class="section extra-margins listing-section">
            <h4 class="font-weight-bold"><strong>기사 검색 : <?=$type?></strong></h4><hr class="red">
            <div class="row pb-5">
              <?php if (empty($listArticle)): ?>
              <div class="col-md-12 my-2 pt-5 pb-5 text-center">
                검색된 정보가 없습니다.
              </div>
              <?php else: ?>
              <?php foreach ($listArticle as $value): ?>
              <div class="col-md-12 my-2">
                <div class="card">
                  <div class="card-body">
                    <div class="row area-link" data-link="<?=$baseUrl?>club/article/<?=$value['idx']?>">
                      <div class="col-sm-2 area-photo"><img src="<?=getThumbnail($value['content'])?>"></div>
                      <div class="col-sm-10">
                        <h5><strong><?=$value['title']?></strong></h5>
                        <p class="card-text text-justify"><?=articleContent($value['content'])?> <span class="small">(<?=date('Y-m-d H:i', $value['viewing_at'])?>)</span></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php endforeach; endif; ?>
            </div>
          </section>
<!--
          <div class="text-center mt-3 mb-5">
            <a href="javascript:alert('클릭하면 하단에 다음 기사들이 주루룩 나오는 형태입니다');" class="btn btn-info" rel="nofollow">더 보기</a>
          </div>
-->
        </div>
