<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-header">백산백소 인증현황</div>
        <div class="sub-content"><br>
          <table class="auth">
            <colgroup>
              <col width="5%">
              <col width="17%">
              <col width="8%">
              <col width="70%">
            </colgroup>
            <tr>
              <th>No.</th>
              <th>닉네임</th>
              <th>횟수</th>
              <th>산행인증내역</th>
            </tr>
            <?php foreach ($auth as $value): ?>
            <tr>
              <td><?=$value['rank']?></td>
              <td><?=$value['nickname']?>님</td>
              <td><?=$value['cnt']?></td>
              <td><?=$value['title']?></td>
            </tr>
            <?php endforeach; ?>
          </table>
        </div><br>
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- SP_CENTER -->
        <ins class="adsbygoogle"
          style="display:block"
          data-ad-client="ca-pub-2424708381875991"
          data-ad-slot="4319659782"
          data-ad-format="auto"
          data-full-width-responsive="true"></ins>
        <script>
          (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
      </div>
