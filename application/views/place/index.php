<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-8 col-md-12">
          <section class="section extra-margins listing-section">
            <div class="row">
              <div class="col-6"><h4 class="font-weight-bold"><strong>산림청 100대 명산</strong></h4></div>
              <div class="col-6 text-right" style="color: #3e4551;"><h4><i class="fas fa-th-list" title="목록"></i> <i class="fas fa-th-large ml-2" title="사진"></i></i></h4></div>
            </div>
            <hr class="red mt-2">
            <div class="row mb-4">
<!--
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6 view overlay">
                    <img src="https://tripkorea.net/public/photos/156764919843927.jpg" class="card-img-top">
                    <a href="/place/view/31"><div class="mask rgba-white-slight"></div></a>
                  </div>
                  <div class="col-md-6 pl-0 pb-0 pr-4">
                    <div class="card-body">
                      <h4 class="card-title"><strong><a href="/place/view/31"><i class="fas fa-crown pr-1" style="color:gold;"></i> 가리산<br><small class="grey-text">강원 춘천시, 홍천군 / 1,051m</small></a></strong></h4><hr>
                      <p class="card-text text-justify">강원도에서 진달래가 가장 많이 피는 산으로 알려져 있고, 참나무 중심의 울창한 산림과 부드러운 산줄기 등 우리나라 산의 전형적인 모습을 갖추고 있으며, 홍천강의 발원지 및 소양강의 수원(水源)을 이루고 있는 점 등을 강원도에서 진달래가 가장 많이 피는 산으로 알려져 있고, 참나무 중심의 울창한 산림과 부드러운 산줄기 등 우리나라 산의 전형적인 모습을 갖추고...</p>
                    </div>
                  </div>
                </div>
                <div class="row no-gutters">
                  <div class="col-md-12 mdb-color lighten-3 text-center">
                    <ul class="list-unstyled list-inline font-small text-white mt-3">
                      <li class="list-inline-item pr-2"><i class="far fa-eye pr-1"></i>조회 24</li>
                      <li class="list-inline-item pr-2"><i class="far fa-comments pr-1"></i>댓글 0</li>
                      <li class="list-inline-item"><i class="fas fa-crown pr-1"></i>랭킹 1위</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mb-4">
              <div class="col-md-6 text-left mt-3">
                <div class="card">
                  <div class="view overlay">
                    <img src="https://tripkorea.net/public/photos/156764923430348.jpg" class="card-img-top">
                    <a href="/place/view/32"><div class="mask rgba-white-slight"></div></a>
                  </div>
                  <div class="card-body">                    
                    <h4 class="card-title"><strong><a href="/place/view/32"><i class="fas fa-crown pr-1" style="color:silver;"></i>가리왕산<br><small class="grey-text">강원 정선군, 평창군 / 1,561m</small></a></strong></h4><hr>
                    <p class="card-text">상봉 외에 주위에 북서쪽에 백석산(1,365m), 서쪽에 중왕산(1,376m), 동남쪽에 중봉(1,433m)·하봉(1,380m), 남서쪽에 청옥산(1,256m) 등이 솟아 있으며 청옥산이 능선으로 이어져 있어 같은 산으로 보기도...</p>
                  </div>
                  <div class="mdb-color lighten-3 text-center">
                    <ul class="list-unstyled list-inline font-small text-white mt-3">
                      <li class="list-inline-item pr-2"><i class="far fa-eye pr-1"></i>조회 24</a></li>
                      <li class="list-inline-item pr-2"><i class="far fa-comments pr-1"></i>댓글 0</a></li>
                      <li class="list-inline-item"><i class="fas fa-crown pr-1"></i>랭킹 2위</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-md-6 my-3">
                <div class="card">
                  <div class="view overlay">
                    <img src="https://tripkorea.net/public/photos/156764919843927.jpg" class="card-img-top">
                    <a href="/place/view/20"><div class="mask rgba-white-slight"></div></a>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title"><strong><a href="/place/view/20"><i class="fas fa-crown pr-1" style="color:#CE7430;"></i>울릉도 성인봉<br><small class="grey-text">강원 춘천시, 홍천군 / 1,051m</small></a></strong></h4><hr>
                    <p class="card-text text-justify">강원도에서 진달래가 가장 많이 피는 산으로 알려져 있고, 참나무 중심의 울창한 산림과 부드러운 산줄기 등 우리나라 산의 전형적인 모습을 갖추고 있으며, 홍천강의 발원지 및 소양강의 수원(水源)을 이루고 있는 점 등을...</p>
                  </div>
                  <div class="mdb-color lighten-3 text-center">
                    <ul class="list-unstyled list-inline font-small text-white mt-3">
                      <li class="list-inline-item pr-2"><i class="far fa-eye pr-1"></i>조회 24</a></li>
                      <li class="list-inline-item pr-2"><i class="far fa-comments pr-1"></i>댓글 0</a></li>
                      <li class="list-inline-item"><i class="fas fa-crown pr-1"></i>랭킹 3위</a></li>
                    </ul>
                  </div>
                </div>
              </div>
-->
              <?php foreach($listPlace as $value): ?>
              <div class="col-md-6 my-3">
                <div class="card">
                  <div class="view overlay">
                    <img src="<?=!empty($value['thumbnail']) ? PHOTO_PLACE_URL . 'thumb_' . $value['thumbnail'] : '/public/images/noimage.png'?>" class="card-img-top">
                    <a href="/place/view/<?=$value['idx']?>"><div class="mask rgba-white-slight"></div></a>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title"><strong><a href="/place/view/<?=$value['idx']?>"><i class="fas fa-crown pr-1" style="color:#CE7430;"></i><?=$value['title']?><br><small class="grey-text">강원 춘천시, 홍천군<?=!empty($value['altitude']) ? ' /' . number_format($value['altitude']) . 'm' : ''?></small></a></strong></h4><hr>
                    <p class="card-text text-justify"><?=articleContent($value['content'])?></p>
                  </div>
                  <div class="mdb-color lighten-3 text-center">
                    <ul class="list-unstyled list-inline font-small text-white mt-3">
                      <li class="list-inline-item pr-2"><i class="far fa-eye pr-1"></i>조회 0</a></li>
                      <li class="list-inline-item pr-2"><i class="far fa-comments pr-1"></i>댓글 0</a></li>
                      <li class="list-inline-item"><i class="fas fa-crown pr-1"></i>랭킹 0위</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </section>
        </div>
