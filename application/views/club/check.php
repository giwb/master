<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-contents">
          <h2><b>[<?=viewStatus($notice['status'])?>]</b> <?=$notice['subject']?></h2>

          <div class="area-reservation text-center"><br>
            <h3 style="font-size: 24px;">예약 신청이 완료되었습니다!</h3><br>

            예약해 주셔서 감사합니다. 예약 정보는 다음과 같습니다.<br>
            결제는 아래 버튼을 눌러서 곧바로 진행하실 수 있으며,<br>
            추후 마이페이지를 통해 결제 정보 입력도 가능합니다.<br><br><br>

            <button class="btn btn-primary">결제정보입력</button>
            <button class="btn btn-primary">좌석현황보기</button>
          </div>
        </div>
      </div>
