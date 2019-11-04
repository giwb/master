<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-contents">
          <div class="area-reservation text-center"><br>
            <h2>예약 신청이 완료되었습니다!</h2><br>

            결제정보입력은 아래 버튼을 눌러서 곧바로 진행하실 수 있으며,<br>
            추후 마이페이지에서도 입력하실 수 있습니다.<br><br><br>

            <a href="<?=base_url()?>member/<?=$view['idx']?>"><button class="btn btn-primary">결제정보입력</button></a>
            <a href="<?=base_url()?>club/reserve/<?=$view['idx']?>?n=<?=$view['noticeIdx']?>"><button class="btn btn-secondary">좌석현황보기</button></a>
          </div>
        </div>
      </div>
