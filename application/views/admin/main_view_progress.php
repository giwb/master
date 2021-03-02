<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <?=$headerMenuView?>
        <div id="content" class="mb-5">
          <div class="sub-contents">
            <input type="hidden" name="clubIdx" value="<?=$view['club_idx']?>">
            <h2 class="m-0 p-0 pb-2"><b><?=viewStatus($view['status'])?></b> <?=$view['subject']?></h2>

            <!-- 안내문 -->
            <?php if (!empty($view['information'])): ?>
            <div class="border border-danger p-4 mt-2 mb-3"><?=nl2br(reset_html_escape($view['information']))?></div>
            <?php endif; ?>

            <?php if (!empty($view['type'])): ?><div class="ti"><strong>・유형</strong> : <?=$view['type']?></div><?php endif; ?>
            <div class="ti"><strong>・일시</strong> : <?=$view['startdate']?> (<?=calcWeek($view['startdate'])?>) <?=$view['starttime']?></div>
            <div class="ti"><strong>・노선</strong> : <?php foreach ($location as $key => $value): if ($key > 1): ?> - <?php endif; ?><?=$value['time']?> <?=$value['short']?><?php endforeach; ?></div>
            <?php $view['cost'] = $view['cost_total'] == 0 ? $view['cost'] : $view['cost_total']; if (!empty($view['cost'])): ?>
            <?php if (!empty($view['sido'])): ?>
            <div class="ti"><strong>・지역</strong> : <?php foreach ($view['sido'] as $key => $value): if ($key != 0): ?>, <?php endif; ?><?=$value?> <?=!empty($view['gugun'][$key]) ? $view['gugun'][$key] : ''?><?php endforeach; ?></div>
            <?php endif; ?>
            <div class="ti"><strong>・요금</strong> : <?=number_format($view['cost_total'] == 0 ? $view['cost'] : $view['cost_total'])?>원 / 1인우등 <?=number_format($view['cost_total'] == 0 ? $view['cost'] + 10000 : $view['cost_total'] + 10000)?>원 (<?=calcTerm($view['startdate'], $view['starttime'], $view['enddate'], $view['schedule'])?><?=!empty($view['distance']) ? ', ' . calcDistance($view['distance']) : ''?><?=!empty($view['options']) ? ', ' . getOptions($view['options']) : ''?><?=!empty($view['options_etc']) ? ', ' . $view['options_etc'] : ''?><?=!empty($view['options']) || !empty($view['options_etc']) ? ' 제공' : ''?><?=!empty($view['costmemo']) ? ', ' . $view['costmemo'] : ''?>)</div>
            <?php endif; ?>
            <?=!empty($view['content']) ? '<div class="ti"><strong>・코스</strong> : ' . nl2br($view['content']) . '</div>' : ''?>
            <?=!empty($view['kilometer']) ? '<div class="ti"><strong>・거리</strong> : ' . $view['kilometer'] . '</div>' : ''?>
            <div class="ti"><strong>・예약</strong> : <?=cntRes($view['idx'])?>명</div>

            <div class="row mt-3">
              <div class="col-4 pl-0">
                <select name="status" class="form-control form-control-sm change-status-modal">
                  <option value="">산행 상태</option>
                  <option value="">------------</option>
                  <option<?=$view['status'] == STATUS_PLAN ? ' selected' : ''?> value="<?=STATUS_PLAN?>">계획</option>
                  <option<?=$view['status'] == STATUS_ABLE ? ' selected' : ''?> value="<?=STATUS_ABLE?>">예정</option>
                  <option<?=$view['status'] == STATUS_CONFIRM ? ' selected' : ''?> value="<?=STATUS_CONFIRM?>">확정</option>
                  <option<?=$view['status'] == STATUS_CANCEL ? ' selected' : ''?> value="<?=STATUS_CANCEL?>">취소</option>
                  <option<?=$view['status'] == STATUS_CLOSED ? ' selected' : ''?> value="<?=STATUS_CLOSED?>">종료</option>
                </select>
              </div>
              <div class="col-8 pr-0 text-right">
                <button type="button" class="btn btn-sm btn-secondary btn-autoseat">코로나19 대응 자동배정</button>
              </div>
            </div>

            <div class="area-reservation">
              <?php foreach ($busType as $key => $value): $bus = $key + 1; // 이번 산행에 등록된 버스 루프 ?>
              <div class="admin-bus-table">
                <table>
                  <colgroup>
                    <col width="4%"></col>
                    <col width="16%"></col>
                    <col width="4%"></col>
                    <col width="16%"></col>
                    <col width="4%"></col>
                    <col width="16%"></col>
                    <col width="4%"></col>
                    <col width="16%"></col>
                    <col width="4%"></col>
                    <col width="16%"></col>
                  </colgroup>
                  <thead>
                    <tr>
                      <th colspan="4" style="border-right: 0px;" class="text-left"><?=$bus?>호차 - <?=$value['bus_name']?> <?=!empty($value['bus_license']) ? '(' . $value['bus_license'] . ')' : ''?></td>
                      <th colspan="6" style="border-left: 0px;" class="text-right">출입구 (예약 : <?=cntRes($view['idx'], $bus)?>명)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($value['seat'] > 15): ?>
                    <tr>
                      <th colspan="4" style="border-right: 0px;" class="text-left">운전석<?=!empty($value['bus_owner']) ? ' (' . $value['bus_owner'] . ' 기사님)' : ''?></th>
                      <th colspan="6" style="border-left: 0px;" class="text-right">보조석 (<?=getBusAssist($view['bus_assist'], $bus)?>)</th>
                    </tr>
                    <?php endif; ?>
                    <?php
                        // 버스 형태 좌석 배치
                        foreach (range(1, $value['seat']) as $seat):
                          if (!empty($reserveInfo['priority']) || !empty($reserveInfo['honor'])) {
                            $boarding = 1;
                          } else {
                            $boarding = 0;
                          }
                          $tableMake = getBusTableMake($value['seat'], $seat); // 버스 좌석 테이블 만들기
                          $reserveInfo = getReserveAdmin($reserve, $bus, $seat, $userData, $boarding); // 예약자 정보
                          $seatNumber = checkDirection($seat, $bus, $view['bustype'], $view['bus']);
                    ?>
                      <?=$tableMake?>
                      <td class="<?=$reserveInfo['class']?>" data-id="<?=$reserveInfo['idx']?>" <?=!empty($reserveInfo['priority']) ? ' data-priority="' . $reserveInfo['priority'] . '"' : ''?> <?=!empty($reserveInfo['honor']) ? ' data-honor="' . $reserveInfo['honor'] . '"' : ''?> data-bus="<?=$bus?>" data-seat="<?=$seat?>"><?=$seatNumber?></td>
                      <td class="<?=$reserveInfo['class']?>" data-id="<?=$reserveInfo['idx']?>" <?=!empty($reserveInfo['priority']) ? ' data-priority="' . $reserveInfo['priority'] . '"' : ''?> <?=!empty($reserveInfo['honor']) ? ' data-honor="' . $reserveInfo['honor'] . '"' : ''?> data-bus="<?=$bus?>" data-seat="<?=$seat?>"><?=$reserveInfo['nickname']?><?=!empty($reserveInfo['honor']) && $reserveInfo['nickname'] != '1인우등' ? '(우등)' : ''?></td>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <?php endforeach; ?>

              <form id="reserveForm" method="post" action="/admin/reserve_complete">
                <div id="addedInfo"></div>
                <input type="hidden" name="idx" value="<?=$view['idx']?>">
                <button type="button" class="btn btn-sm btn-default btn-reserve-confirm">확인</button>
              </form>
            </div>

            <?php if ($view['status'] == STATUS_ABLE || $view['status'] == STATUS_CONFIRM): ?>
            <div class="area-wait">
              <?php if (!empty($wait)): ?>
              <div class="text-dark">■ <strong>대기자 목록</strong></div>
              <?php foreach ($wait as $key => $value): ?>
              <div class="mt-1">
                <a href="javascript:;" class="btn-wait-delete-modal" data-idx="<?=$value['idx']?>">[<?=$key + 1?>] <?=$value['nickname']?> (<?=getGender($value['gender'])?>) <?=!empty($value['location']) ? arrLocation($viewClub['club_geton'], NULL, $value['location'], 1) : '미정'?>
                <?=!empty($value['memo']) ? ' - ' . $value['memo'] : ''?> <span class="small">(<?=substr(date('Y-m-d H:i:s', $value['created_at']), 5, 11)?>)</span></a>
              </div>
              <?php endforeach; ?>

              <div class="mt-4"></div>
              <?php endif; ?>

              <div class="text-dark">■ <strong>대기자 추가</strong></div>
              <div class="wait row align-items-center">
                <div class="col-3 col-sm-3 p-0 pr-1"><input type="text" name="nickname" class="search-user form-control form-control-sm" placeholder="닉네임" data-placement="bottom"><input type="hidden" name="userIdx"></div>
                <div class="col-2 col-sm-2 p-0 pr-1">
                  <select name="gender" class="gender form-control form-control-sm pl-0 pr-0">
                    <option value='M'>남성</option>
                    <option value='F'>여성</option>
                  </select>
                </div>
                <div class="col-2 col-sm-2 p-0 pr-1">
                  <select name="location" class="location form-control form-control-sm pl-0 pr-0">
                    <?php foreach ($arrLocation as $key => $value): if ($key == 0) $value['short'] = '선택'; ?>
                    <option value='<?=$value['no']?>'><?=$value['short']?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-3 col-sm-4 p-0 pr-1"><input type="text" name="memo" class="form-control form-control-sm" placeholder="메모"></div>
                <div class="col-2 col-sm-1 p-0"><button type="button" class="btn btn-sm btn-default w-100 btn-wait-insert">등록</button></div>
              </div>
            </div>
            <?php endif; ?>

            <div class="text-dark mt-4">■ <strong>댓글</strong></div>
            <div class="admin-reply story-reply">
              <?=$listReply?>
            </div>
          </div>
        </div>

        <!-- Wait Delete Modal -->
        <div class="modal fade" id="waitModal" tabindex="-1" role="dialog" aria-labelledby="waitModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">대기자 삭제</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-center">
                <p class="modal-message">정말로 삭제하시겠습니까?</p>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="waitIdx">
                <button type="button" class="btn btn-sm btn-default btn-wait-delete">삭제합니다</button>
                <button type="button" class="btn btn-sm btn-secondary btn-close" data-dismiss="modal">닫기</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Change Status Modal -->
        <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">메시지</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-center">
                <p class="modal-message"></p>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="selectStatus">
                <button type="button" class="btn btn-sm btn-default btn-change-status">승인</button>
                <button type="button" class="btn btn-sm btn-secondary btn-close" data-dismiss="modal">닫기</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Cancel Modal -->
        <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">메시지</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-center">
                <p class="modal-message">
                  정말로 이 좌석의 예약을 취소하시겠습니까?<br>
                  <div class="row align-items-center">
                    <div class="col-sm-3 pl-0 pr-0">취소사유</div>
                    <div class="col-sm-9 pl-0"><input type="text" name="subject" class="form-control"></div>
                  </div>
                </p>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="delete_idx">
                <button type="button" class="btn btn-sm btn-default btn-reserve-cancel-complete">승인</button>
                <button type="button" class="btn btn-sm btn-secondary btn-close" data-dismiss="modal">닫기</button>
              </div>
            </div>
          </div>
        </div>

        <script type="text/javascript">
          $(document).on('click', '.btn-autoseat', function() {
            var $btn = $(this);
            $.ajax({
              url: '/admin/autoseat',
              data: 'idx=<?=$view['idx']?>',
              dataType: 'json',
              type: 'post',
              beforeSend: function() {
                $btn.css('opacity', '0.5').prop('disabled', true).text('진행중.....');
              },
              success: function(result) {
                if (result.error == 1) {
                  $btn.css('opacity', '1').prop('disabled', false).text('코로나19 대응 자동배정');
                  $.openMsgModal(result.message);
                } else {
                  location.reload();
                }
              }
            });
          });
        </script>