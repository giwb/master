<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-xl-8 col-md-12">

          <h4 class="font-weight-bold"><?=$pageTitle?></h4>
          <hr class="text-default">

          <div class="sub-content"><br>
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
              <?php foreach ($auth as $key => $value): ?>
              <tr>
                <td align="center">
                  <?php if ($value['rank'] <= 5): ?><img src="/public/images/medal<?=$value['rank']?>.png">
                  <?php else: ?><?=$value['rank']?><?php endif; ?>
                </td>
                <td nowrap><?=$value['nickname']?>님</td>
                <td class="btn-open-auth" data-idx="<?=$key?>">
                  <div class="auth-progress-bar"><div id="medal<?=$key?>" class="auth-gauge" cnt="<?=$value['cnt']?>"><?=$value['cnt']?>회</div></div>
                  <div class="auth-title d-none"><?=$value['title']?></div>
                </td>
              </tr>
              <?php endforeach; ?>
            </table>
          </div>
        </div>

        <script type="text/javascript">
          $(document).on('click', '.btn-open-auth', function() {
            var idx = $(this).data('idx');
            var $dom = $('.auth-title[data-idx=' + idx + ']');
            if ($dom.hasClass('d-none')) {
              $dom.removeClass('d-none');
            } else {
              $dom.addClass('d-none');
            }
          });
        </script>