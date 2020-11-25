<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <section class="container">
      <div class="row">
        <div class="col-sm-3 pl-0 nav-place d-none d-sm-block">
          <!--
          <img src="/public/images/map_20190902.gif" usemap="#Map">
          <map name="Map" id="Map">
            <area shape="poly" coords="95,65,95,71,82,77,88,89,100,91,113,78,96,67" href="/club/listing?s=area_sido&k=11000" title="서울">
            <area shape="poly" coords="76,93,81,88,77,79,68,74,64,70,62,71,61,75,61,88,70,87" href="/club/listing?s=area_sido&k=14000" title="인천">
            <area shape="poly" coords="84,91,71,99,73,106,66,109,80,119,78,126,93,130,101,125,115,130,121,122,138,113,141,106,143,96,144,86,140,80,126,77,128,59,127,48,117,42,100,31,101,27,77,34,93,38,88,46,71,46,74,48,74,56,74,65,62,61,54,55,54,62,42,61,44,67,37,70,48,80,52,93,59,89,56,69,67,67,78,74,84,70,90,64,98,60,117,76,114,87,105,94,96,95,88,92" href="/club/listing?s=area_sido&k=19000" title="경기">
            <area shape="poly" coords="108,23,106,29,130,43,131,74,147,75,149,89,147,104,145,109,155,108,156,97,165,107,168,102,180,108,191,113,200,113,211,112,219,116,232,113,243,110,241,99,227,82,219,72,221,67,209,60,190,25,187,12,181,1,177,5,181,21,176,22,169,14,163,19,167,26,156,25,138,26,129,20,123,24,115,24" href="/club/listing?s=area_sido&k=20000" title="강원">
            <area shape="poly" coords="64,124,57,122,47,130,40,139,43,150,53,159,51,166,64,182,61,190,73,198,86,197,86,184,98,188,104,199,108,190,127,201,132,199,116,188,106,182,108,167,104,155,105,150,108,138,112,134,105,130,95,137,81,132,70,130,64,125" href="/club/listing?s=area_sido&k=22000" title="충남">
            <area shape="poly" coords="115,139,114,149,111,158,113,166,124,163,131,162,131,157,124,149,123,140,121,138" href="/club/listing?s=area_sido&k=18000" title="세종">
            <area shape="poly" coords="116,168,114,175,115,181,131,192,133,184,131,173,130,169,121,169" href="/club/listing?s=area_sido&k=16000" title="대전">
            <area shape="poly" coords="183,116,175,111,172,109,162,108,162,112,154,114,137,117,123,128,130,136,130,150,138,156,136,170,140,184,137,195,152,200,157,186,154,180,146,178,149,163,150,146,163,136,173,133,182,136,188,129,191,118,184,118" href="/club/listing?s=area_sido&k=21000" title="충북">
            <area shape="poly" coords="246,124,246,119,238,119,229,120,217,123,211,118,194,126,191,135,187,144,174,142,164,144,155,153,155,170,155,175,175,183,164,187,162,194,159,206,156,210,167,212,175,216,174,228,181,224,178,204,187,199,192,196,200,192,210,197,216,207,219,218,210,224,200,230,213,236,219,225,238,221,250,224,256,209,258,196,245,203,245,190,241,184,244,173,244,161,241,154,245,145,249,135,242,121" href="/club/listing?s=area_sido&k=25000" title="경북">
            <area shape="poly" coords="183,229,192,228,192,223,197,224,213,214,209,207,199,199,183,206,183,212,187,218" href="/club/listing?s=area_sido&k=13000" title="대구">
            <area shape="poly" coords="221,237,230,241,232,246,240,245,244,252,250,243,253,230,240,226,226,228,224,235" href="/club/listing?s=area_sido&k=17000" title="울산">
            <area shape="poly" coords="190,279,196,284,207,286,216,278,229,274,239,262,236,252,226,256,212,267,194,273" href="/club/listing?s=area_sido&k=12000" title="부산">
            <area shape="poly" coords="205,294,200,305,187,299,180,292,168,294,163,305,158,294,152,284,153,274,144,271,138,263,141,253,137,234,142,222,155,213,167,219,163,233,174,234,198,236,210,241,226,249,215,257,193,264,184,282,195,290,206,293" href="/club/listing?s=area_sido&k=26000" title="경남">
            <area shape="poly" coords="131,254,134,249,128,243,128,233,130,225,135,213,148,213,144,206,132,209,123,210,115,201,109,203,100,210,96,194,92,204,79,206,70,205,73,220,67,228,60,236,62,247,65,254,82,242,98,247,102,257,109,249,114,257,119,252,132,255" href="/club/listing?s=area_sido&k=23000" title="전북">
            <area shape="poly" coords="90,249,75,254,66,259,57,252,53,259,58,273,51,273,31,272,41,279,39,288,28,289,25,298,26,304,24,312,41,312,26,328,17,343,23,346,32,349,38,341,41,329,50,331,60,328,67,337,80,331,96,335,100,338,104,328,119,329,129,328,124,313,129,308,149,310,148,299,139,280,132,268,126,260,118,262,108,256,102,262,98,286,82,289,73,284,64,278,66,264,84,255,98,259,92,249" href="/club/listing?s=area_sido&k=24000" title="전남">
            <area shape="poly" coords="82,264,71,268,73,277,87,282,97,271,93,265,88,261" href="/club/listing?s=area_sido&k=15000" title="광주">
            <area shape="poly" coords="39,368,39,382,45,388,57,383,88,378,96,366,87,358,75,356,55,363,46,368" href="/club/listing?s=area_sido&k=27000" title="제주">
          </map>
          -->
          <a href="/club" class="d-block<?=empty($keyword) ? ' active' : ''?>">전체보기</a>
          <a href="/club/?s=area_sido&k=11000" class="d-block<?=$keyword == 11000 ? ' active' : ''?>">서울특별시</a>
          <a href="/club/?s=area_sido&k=12000" class="d-block<?=$keyword == 12000 ? ' active' : ''?>">부산광역시</a>
          <a href="/club/?s=area_sido&k=13000" class="d-block<?=$keyword == 13000 ? ' active' : ''?>">대구광역시</a>
          <a href="/club/?s=area_sido&k=14000" class="d-block<?=$keyword == 14000 ? ' active' : ''?>">인천광역시</a>
          <a href="/club/?s=area_sido&k=15000" class="d-block<?=$keyword == 15000 ? ' active' : ''?>">광주광역시</a>
          <a href="/club/?s=area_sido&k=16000" class="d-block<?=$keyword == 16000 ? ' active' : ''?>">대전광역시</a>
          <a href="/club/?s=area_sido&k=17000" class="d-block<?=$keyword == 17000 ? ' active' : ''?>">울산광역시</a>
          <a href="/club/?s=area_sido&k=18000" class="d-block<?=$keyword == 18000 ? ' active' : ''?>">세종특별자치시</a>
          <a href="/club/?s=area_sido&k=19000" class="d-block<?=$keyword == 19000 ? ' active' : ''?>">경기도</a>
          <a href="/club/?s=area_sido&k=20000" class="d-block<?=$keyword == 20000 ? ' active' : ''?>">강원도</a>
          <a href="/club/?s=area_sido&k=21000" class="d-block<?=$keyword == 21000 ? ' active' : ''?>">충청북도</a>
          <a href="/club/?s=area_sido&k=22000" class="d-block<?=$keyword == 22000 ? ' active' : ''?>">충청남도</a>
          <a href="/club/?s=area_sido&k=23000" class="d-block<?=$keyword == 23000 ? ' active' : ''?>">전라북도</a>
          <a href="/club/?s=area_sido&k=24000" class="d-block<?=$keyword == 24000 ? ' active' : ''?>">전라남도</a>
          <a href="/club/?s=area_sido&k=25000" class="d-block<?=$keyword == 25000 ? ' active' : ''?>">경상북도</a>
          <a href="/club/?s=area_sido&k=26000" class="d-block<?=$keyword == 26000 ? ' active' : ''?>">경상남도</a>
          <a href="/club/?s=area_sido&k=27000" class="d-block<?=$keyword == 27000 ? ' active' : ''?>">제주도</a>
        </div>
        <div class="col-12 col-sm-9">
          <div class="mt-3 d-none d-sm-block"></div>
          <div class="row align-items-center border-bottom mb-3 pb-3">
            <div class="col-sm-7">
              <h2><?=$searchTitle['name']?></h2>
            </div>
            <div class="col-sm-5 text-right p-0">
              <form method="get" action="/club" class="row align-items-center m-0">
                <div class="col-8 p-1"><input type="hidden" name="s" value="title"><input type="text" size="10" name="k" value="<?=$search == 'title' ? $keyword : ''?>" class="form-control form-control-sm"></div>
                <div class="col-2 p-1"><button type="submit" class="btn btn-sm btn-primary w-100">검색</button></div>
                <div class="col-2 p-1"><a href="/club/entry"><button type="button" class="btn btn-sm btn-primary w-100">등록</button></a></div>
              </form>
            </div>
          </div>
          <ul class="m-0 p-0">
          <?php if (empty($list)): ?>
          </ul>
          <div class="text-center">등록된 데이터가 없습니다.</div>
          <?php else: foreach ($list as $value): ?>
            <li class="row align-items-top border-bottom mb-3 pr-3 pb-3">
              <div class="col-4 col-sm-2 pr-0"><a target="_blank" href="<?=$value['domain']?>"><img src="<?=PHOTO_URL?><?=$value['photo']?>" class="w-100"></a></div>
              <div class="col-8 col-sm-10 text-justify"><a target="_blank" href="<?=$value['domain']?>"><h3 class="font-weight-bold"><?=$value['title']?></h3></a><?=ksubstr(strip_tags(reset_html_escape($value['about'])), 200)?></div>
            </li>
          <?php endforeach; endif; ?>
          </ul>
        </div>
      </div>
    </section>
