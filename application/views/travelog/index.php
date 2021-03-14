<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row">
        <div class="col-xl-8 col-md-12">
          <section class="section extra-margins listing-section mb-5">
            <div class="row">
              <div class="col-6"><h4 class="font-weight-bold mb-0"><strong><?=$pageTitle?></strong></h4></div>
              <div class="col-6 text-right pr-4"><a href="<?=BASE_URL?>/travelog/post/?type=<?=$type?>"><span class="text-dark"><i class="fas fa-edit"></i> 글쓰기</a></span></div>
            </div>
            <hr class="red">
            <div class="row">
              <?php if (empty($listTravelog)): ?>
                <div class="col-12 text-center mb-5 pt-5 pb-5">현재 등록된 여행기가 없습니다.</div>
              <?php endif; ?>
              <?php foreach ($listTravelog as $value): ?>
              <div class="col-md-12 my-2">
                <div class="card">
                  <div class="card-body">
                    <div class="row area-travelog" data-idx="<?=$value['idx']?>">
                      <div class="col-sm-2 area-photo"><img class="w-100" src="<?=getThumbnail($value['content'])?>"></div>
                      <div class="col-sm-10">
                        <h5><strong><?=$value['title']?></strong></h5>
                        <p class="card-text text-justify"><?=articleContent($value['content'])?> <span class="small">(<?=date('Y-m-d H:i', $value['created_at'])?>)</span></p>
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
