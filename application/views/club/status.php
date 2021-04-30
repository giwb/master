<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-xl-8 col-md-12">

          <h4 class="font-weight-bold"><?=$pageTitle?></h4>
          <hr class="text-default">

          <div class="header-menu mt-3 mb-3">
            <div class="header-menu-item<?=empty($type) ? ' active' : ''?>"><a href="<?=BASE_URL?>/club/status">전체보기</a></div>
            <div class="header-menu-item<?=!empty($type) && $type == 1 ? ' active' : ''?>"><a href="<?=BASE_URL?>/club/status?type=1">산행 참여</a></div>
            <div class="header-menu-item<?=!empty($type) && $type == 2 ? ' active' : ''?>"><a href="<?=BASE_URL?>/club/status?type=2">백산백소 인증</a></div>
            <?php if (!empty($userData['admin']) && $userData['admin'] == 1): ?><div class="header-menu-item<?=!empty($type) && $type == 3 ? ' active' : ''?>"><a href="<?=BASE_URL?>/club/status?type=3">홈페이지 방문</a></div><?php endif; ?>
          </div>

          <?php if (!empty($type)): ?>
          <div class="sub-content mb-5"><br>
            <table width="100%" class="auth">
              <colgroup>
                <col width="5%">
                <col width="17%">
                <col width="78%">
              </colgroup>
              <tr>
                <th>No.</th>
                <th>닉네임</th>
                <th>횟수</th>
              </tr>
              <?php
                $rank = 0; $buf = 0;
                foreach ($status as $key => $value):
                  if ($buf != $value['cnt']) { $rank = $key; $rank++; }
              ?>
              <tr>
                <td align="center">
                  <?php if ($rank <= 5): ?><img src="/public/images/medal<?=$rank?>.png">
                  <?php else: ?><?=$rank?><?php endif; ?>
                </td>
                <td nowrap><?=$value['nickname']?>님</td>
                <td class="btn-open-auth" data-idx="<?=$key?>">
                  <div class="auth-progress-bar"><div id="medal<?=$key?>" class="auth-gauge" cnt="<?=$value['cnt']?>" max="<?=$status[0]['cnt']?>"><?=$value['cnt']?>회</div></div>
                </td>
              </tr>
              <?php
                  $buf = $value['cnt'];
                endforeach;
              ?>
            </table>
          </div>
          <?php else: ?>
          <div class="sub-content mb-5">
            <h5 class="mt-2">■ 산행참여</h5>
            <table width="100%" class="auth">
              <colgroup>
                <col width="5%">
                <col width="17%">
                <col width="78%">
              </colgroup>
              <tr>
                <th>No.</th>
                <th>닉네임</th>
                <th>횟수</th>
              </tr>
              <?php
                $rank = 0; $buf = 0;
                foreach ($statusRescount as $key => $value):
                  if ($buf != $value['cnt']) { $rank = $key; $rank++; }
              ?>
              <tr>
                <td align="center">
                  <?php if ($rank <= 5): ?><img src="/public/images/medal<?=$rank?>.png">
                  <?php else: ?><?=$rank?><?php endif; ?>
                </td>
                <td nowrap><?=$value['nickname']?>님</td>
                <td class="btn-open-auth" data-idx="<?=$key?>">
                  <div class="auth-progress-bar"><div id="medal-res<?=$key?>" class="auth-gauge" cnt="<?=$value['cnt']?>" max="<?=$statusRescount[0]['cnt']?>"><?=$value['cnt']?>회</div></div>
                </td>
              </tr>
              <?php
                  $buf = $value['cnt'];
                endforeach;
              ?>
            </table><br>

            <h5 class="mt-2">■ 백산백소 인증</h5>
            <table width="100%" class="auth">
              <colgroup>
                <col width="5%">
                <col width="17%">
                <col width="78%">
              </colgroup>
              <tr>
                <th>No.</th>
                <th>닉네임</th>
                <th>횟수</th>
              </tr>
              <?php
                $rank = 0; $buf = 0;
                foreach ($statusAuth as $key => $value):
                  if ($buf != $value['cnt']) { $rank = $key; $rank++; }
              ?>
              <tr>
                <td align="center">
                  <?php if ($rank <= 5): ?><img src="/public/images/medal<?=$rank?>.png">
                  <?php else: ?><?=$rank?><?php endif; ?>
                </td>
                <td nowrap><?=$value['nickname']?>님</td>
                <td class="btn-open-auth" data-idx="<?=$key?>">
                  <div class="auth-progress-bar"><div id="medal-auth<?=$key?>" class="auth-gauge" cnt="<?=$value['cnt']?>" max="<?=$statusAuth[0]['cnt']?>"><?=$value['cnt']?>회</div></div>
                </td>
              </tr>
              <?php
                  $buf = $value['cnt'];
                endforeach;
              ?>
            </table><br>

            <?php if (!empty($userData['admin']) && $userData['admin'] == 1): ?>
            <h5 class="mt-2">■ 홈페이지 방문</h5>
            <table width="100%" class="auth">
              <colgroup>
                <col width="5%">
                <col width="17%">
                <col width="78%">
              </colgroup>
              <tr>
                <th>No.</th>
                <th>닉네임</th>
                <th>횟수</th>
              </tr>
              <?php
                $rank = 0; $buf = 0;
                foreach ($statusVisit as $key => $value):
                  if ($buf != $value['cnt']) { $rank = $key; $rank++; }
              ?>
              <tr>
                <td align="center">
                  <?php if ($rank <= 5): ?><img src="/public/images/medal<?=$rank?>.png">
                  <?php else: ?><?=$rank?><?php endif; ?>
                </td>
                <td nowrap><?=$value['nickname']?>님</td>
                <td class="btn-open-auth" data-idx="<?=$key?>">
                  <div class="auth-progress-bar"><div id="medal-visit<?=$key?>" class="auth-gauge" cnt="<?=$value['cnt']?>" max="<?=$statusVisit[0]['cnt']?>"><?=$value['cnt']?>회</div></div>
                </td>
              </tr>
              <?php
                  $buf = $value['cnt'];
                endforeach;
              ?>
            </table>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        </div>
