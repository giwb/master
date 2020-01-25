<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section id="subpage">
  <div class="sub-header">
    <div class="left">
      <h2>여행정보</h2>
    </div>
    <div class="right">
      <form method="get" action="<?=base_url()?>place">
        <input type="hidden" name="search" value="title"><input type="text" name="keyword" value="<?=$search == 'title' ? $keyword : ''?>"> <button type="submit">검색</button>
        <a href="<?=base_url()?>place/entry"><button type="button">정보등록</button></a>
      </form>
    </div>
  </div>
  <div class="sub-contents">
    <ul class="type">
      <li><a href="<?=base_url()?>place"><button type="button">전체</button></a></li>
      <li><a href="<?=base_url()?>place/?search=type&keyword=type1"><button type="button">산림청 선정 100대 명산</button></a></li>
      <li><a href="<?=base_url()?>place/?search=type&keyword=type2"><button type="button">블랙야크 명산100</button></a></li>
      <li><a href="<?=base_url()?>place/?search=type&keyword=type3"><button type="button">죽기전에 꼭 가봐야 할 국내여행 1001</button></a></li>
      <li><a href="<?=base_url()?>place/?search=type&keyword=type4"><button type="button">백두대간</button></a></li>
      <li><a href="<?=base_url()?>place/?search=type&keyword=type5"><button type="button">도보트레킹</button></a></li>
      <li><a href="<?=base_url()?>place/?search=type&keyword=type6"><button type="button">투어</button></a></li>
      <li><a href="<?=base_url()?>place/?search=type&keyword=type7"><button type="button">섬</button></a></li>
    </ul>
    <ul>
<?php
  if (empty($list)) {
?>
    </ul>
    <div class="text-center">등록된 데이터가 없습니다.</div>
<?php
  } else {
    foreach ($list as $value) {
?>
      <li><a href="<?=base_url()?>place/view/<?=$value['idx']?>"><img src="<?=base_url()?><?=PHOTO_URL?><?=$value['photo']?>"><p><?=$value['title']?><br><small><?=getHeight($value['altitude'])?><?=getAreaName($value['area_sido'], $value['area_gugun'], 1)?></small></p></a></li>
<?php
    }
  }
?>
    </ul>
  </div>
</section>

