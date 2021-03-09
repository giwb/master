              <?php foreach ($listArticle as $value): ?>
              <div class="col-md-6 my-3">
                <div class="card">
                  <div class="view overlay">
                    <img src="<?=getThumbnail($value['content'])?>" class="card-img-top">
                    <a href="/article/<?=$value['idx']?>"><div class="mask rgba-white-slight"></div></a>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title"><strong><a href="/article/<?=$value['idx']?>"><?=$value['title']?></a></strong></h4><hr>
                    <p class="card-text text-justify"><?=articleContent($value['content'])?></p>
                  </div>
                  <div class="mdb-color lighten-3 text-center">
                    <ul class="list-unstyled list-inline font-small mt-3">
                      <li class="list-inline-item pr-1 white-text"><?=$value['category_name']?></li>
                      <li class="list-inline-item pr-1 white-text"><i class="far fa-clock-o pr-1"></i><?=date('Y-m-d', $value['viewing_at'])?></li>
                      <li class="list-inline-item pr-1 white-text"><i class="far fa-comments pr-1"></i>0</li>
                      <li class="list-inline-item pr-1 white-text"><i class="far fa-eye pr-1"></i>24</li>
                    </ul>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>