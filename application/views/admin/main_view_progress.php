<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <div class="row align-items-center border-bottom text-center mb-4 pt-3 pb-3">
            <div class="col pt-0 pr-1 pb-0 pl-1"><a href="<?=BASE_URL?>/admin/main_entry/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">수정</button></a></div>
            <div class="col pt-0 pr-1 pb-0 pl-1"><a href="<?=BASE_URL?>/admin/main_notice/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">공지</button></a></div>
            <div class="col pt-0 pr-1 pb-0 pl-1"><a href="<?=BASE_URL?>/admin/main_view_progress/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-secondary w-100">예약</button></a></div>
            <div class="col pt-0 pr-1 pb-0 pl-1"><a href="<?=BASE_URL?>/admin/main_view_boarding/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">승차</button></a></div>
            <div class="col pt-0 pr-1 pb-0 pl-1"><a href="<?=BASE_URL?>/admin/main_view_sms/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">문자</button></a></div>
            <div class="col pt-0 pr-1 pb-0 pl-1"><a href="<?=BASE_URL?>/admin/main_view_adjust/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">정산</button></a></div>
          </div>
          <div class="sub-contents">
            <h2 class="m-0 p-0 pb-2"><b><?=viewStatus($view['status'])?></b> <?=$view['subject']?></h2>
            <?php if (!empty($view['type'])): ?><div class="ti"><strong>・유형</strong> : <?=$view['type']?></div><?php endif; ?>
            <div class="ti"><strong>・일시</strong> : <?=$view['startdate']?> (<?=calcWeek($view['startdate'])?>) <?=$view['starttime']?></div>
            <?php $view['cost'] = $view['cost_total'] == 0 ? $view['cost'] : $view['cost_total']; if (!empty($view['cost'])): ?>
            <?php if (!empty($view['sido'])): ?>
            <div class="ti"><strong>・지역</strong> : <?php foreach ($view['sido'] as $key => $value): if ($key != 0): ?>, <?php endif; ?><?=$value?> <?=!empty($view['gugun'][$key]) ? $view['gugun'][$key] : ''?><?php endforeach; ?></div>
            <?php endif; ?>
            <div class="ti"><strong>・요금</strong> : <?=number_format($view['cost_total'] == 0 ? $view['cost'] : $view['cost_total'])?>원 (<?=calcTerm($view['startdate'], $view['starttime'], $view['enddate'], $view['schedule'])?><?=!empty($view['distance']) ? ', ' . calcDistance($view['distance']) : ''?><?=!empty($view['options']) ? ', ' . getOptions($view['options']) : ''?><?=!empty($view['options_etc']) ? ', ' . $view['options_etc'] : ''?><?=!empty($view['options']) || !empty($view['options_etc']) ? ' 제공' : ''?><?=!empty($view['costmemo']) ? ', ' . $view['costmemo'] : ''?>)</div>
            <?php endif; ?>
            <?=!empty($view['content']) ? '<div class="ti"><strong>・코스</strong> : ' . nl2br($view['content']) . '</div>' : ''?>
            <?=!empty($view['kilometer']) ? '<div class="ti"><strong>・거리</strong> : ' . $view['kilometer'] . '</div>' : ''?>
            <div class="ti"><strong>・예약</strong> : <?=cntRes($view['idx'])?>명</div>

            <div class="col-sm-3 mt-3 pl-0">
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
                      <th colspan="10"><?=$bus?>호차 - <?=$value['bus_name']?> <?=!empty($value['bus_license']) ? '(' . $value['bus_license'] . ')' : ''?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($value['seat'] > 12): ?>
                    <tr>
                      <th colspan="4" class="text-left">운전석<?=!empty($value['bus_owner']) ? ' (' . $value['bus_owner'] . ' 기사님)' : ''?></th>
                      <th colspan="6" class="text-right">출입구 (예약 : <?=cntRes($view['idx'], $bus)?>명)</th>
                    </tr>
                    <?php endif; ?>
                    <?php
                        // 버스 형태 좌석 배치
                        foreach (range(1, $value['seat']) as $seat):
                          $tableMake = getBusTableMake($value['seat'], $seat); // 버스 좌석 테이블 만들기
                          $reserveInfo = getReserveAdmin($reserve, $bus, $seat, $userData); // 예약자 정보
                          $seatNumber = checkDirection($seat, $bus, $view['bustype'], $view['bus']);
                    ?>
                      <?=$tableMake?>
                      <td class="<?=$reserveInfo['class']?>" data-id="<?=$reserveInfo['idx']?>" <?=!empty($reserveInfo['priority']) ? ' data-priority="' . $reserveInfo['priority'] . '"' : ''?> data-bus="<?=$bus?>" data-seat="<?=$seat?>"><?=$seatNumber?></td>
                      <td class="<?=$reserveInfo['class']?>" data-id="<?=$reserveInfo['idx']?>" <?=!empty($reserveInfo['priority']) ? ' data-priority="' . $reserveInfo['priority'] . '"' : ''?> data-bus="<?=$bus?>" data-seat="<?=$seat?>"><?=$reserveInfo['nickname']?></td>
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
                <a href="javascript:;" class="btn-wait-delete-modal" data-idx="<?=$value['idx']?>">[<?=$key + 1?>] <?=$value['nickname']?> (<?=getGender($value['gender'])?>) <?=!empty($value['location']) ? arrLocation(NULL, $value['location'], 1) : '미정'?>
                <?=!empty($value['memo']) ? ' - ' . $value['memo'] : ''?> <span class="small">(<?=substr(date('Y-m-d H:i:s', $value['created_at']), 5, 11)?>)</span></a>
              </div>
              <?php endforeach; ?>

              <div class="mt-4"></div>
              <?php endif; ?>

              <div class="text-dark">■ <strong>대기자 추가</strong></div>
              <div class="wait row align-items-center">
                <div class="col-3 col-sm-3 p-0 pr-1"><input type="text" name="nickname" class="search-userid form-control form-control-sm" placeholder="닉네임" data-placement="bottom"><input type="hidden" name="userid"></div>
                <div class="col-2 col-sm-2 p-0 pr-1">
                  <select name="gender" class="gender form-control form-control-sm pl-0 pr-0">
                    <option value='M'>남성</option>
                    <option value='F'>여성</option>
                  </select>
                </div>
                <div class="col-2 col-sm-2 p-0 pr-1">
                  <select name="location" class="location form-control form-control-sm pl-0 pr-0">
                    <?php foreach ($arrLocation as $key => $value): if ($key == 0) $value['stitle'] = '선택'; ?>
                    <option value='<?=$value['no']?>'><?=$value['stitle']?></option>
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

            <div class="border-top mt-5 mb-5">
              <h3 class="pt-4 pb-3">진행중 산행 목록</h3>
              <?php foreach ($list as $value): ?>
              <div class="border-top pt-2 pb-2">
                <b><?=viewStatus($value['status'])?></b> <a href="/admin/main_view_progress/<?=$value['idx']?>"><?=$value['subject']?></a><br>
                <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 / 예약인원 <?=cntRes($value['idx'])?>명
              </div>
              <?php endforeach; ?>
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
                <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
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
                <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
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
                <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
              </div>
            </div>
          </div>
        </div>
