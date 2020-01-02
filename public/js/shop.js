$(document).on('click', '.shop-item', function() {
  // 용품 상세 페이지
  location.href = ( $('input[name=baseUrl]').val() + 'club/shop_item/' + $('input[name=clubIdx]').val() + '?n=' + $(this).data('idx') );
}).on('click', '.btn-cart', function() {
  // 장바구니에 담기
  var $btn = $(this);
  var baseUrl = $('input[name=baseUrl]').val();
  var clubIdx = $('input[name=clubIdx]').val();
  var idx = $(this).data('idx');
  var buy_type = $(this).data('type');
  var item_key = $('.item-option').val();
  var item_cost = $('.item-option option:selected').data('cost');


  if (typeof item_cost == 'undefined' || item_cost == '') {
    item_cost = $('.item-option').data('cost');
  }

  if (typeof $('.item-option').val() == 'undefined' || $('.item-option').val() == '') {
    $.openMsgModal('옵션은 꼭 선택해주세요.');
    return false;
  }

  $.ajax({
    url: baseUrl + 'club/shop_cart_insert/' + clubIdx,
    data: 'idx=' + idx + '&item_key=' + item_key + '&item_cost=' + item_cost + '&buy_type=' + buy_type,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
    },
    success: function(result) {
      if (result.error == 1) {
        $btn.css('opacity', '1').prop('disabled', false).text('장바구니에 담기');
        $.openMsgModal(result.message);
      } else {
        location.href = (result.message);
      }
    }
  });
}).on('change', '.cart-amount', function() {
  // 장바구니에 담기
  var $dom = $(this);
  var baseUrl = $('input[name=baseUrl]').val();
  var clubIdx = $('input[name=clubIdx]').val();
  var rowid = $(this).data('rowid');
  var amount = $(this).val();
  $.ajax({
    url: baseUrl + 'club/shop_cart_update/' + clubIdx,
    data: 'rowid=' + rowid + '&amount=' + amount,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $dom.css('opacity', '0.5').prop('disabled', true);
    },
    success: function(result) {
      if (result.error == 1) {
        $dom.css('opacity', '1').prop('disabled', false);
        $.openMsgModal(result.message);
      } else {
        location.href = (result.message);
      }
    }
  });
}).on('click', '.btn-cart-delete', function() {
  // 장바구니 삭제
  var $btn = $(this);
  var baseUrl = $('input[name=baseUrl]').val();
  var clubIdx = $('input[name=clubIdx]').val();
  var rowid = $(this).data('rowid');
  var amount = 0;
  $.ajax({
    url: baseUrl + 'club/shop_cart_update/' + clubIdx,
    data: 'rowid=' + rowid + '&amount=' + amount,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true);
    },
    success: function(result) {
      if (result.error == 1) {
        $btn.css('opacity', '1').prop('disabled', false);
        $.openMsgModal(result.message);
      } else {
        location.href = (result.message);
      }
    }
  });
}).on('blur', '.using-point', function() {
  // 포인트 사용
  var result = 0;
  var usingPoint = Number($(this).val());
  var userPoint = Number($('input[name=userPoint]').val());
  var totalCost = Number($('input[name=totalCost]').val());
  var paymentCost = Number($('input[name=paymentCost]').val());

  if (usingPoint > userPoint) {
    $.openMsgModal('보유한 포인트만 사용할 수 있습니다.');
    $(this).val('');
    return false;
  }
  if (usingPoint < 0) {
    usingPoint = '';
    $('input[name=usingPoint]').val(usingPoint);
    return false;
  }
  if (usingPoint > totalCost) {
    usingPoint = totalCost;
    $('input[name=usingPoint]').val(usingPoint);
  }

  paymentCost = totalCost - usingPoint;
  userPoint = userPoint - usingPoint;

  $('input[name=paymentCost]').val(paymentCost);
  $('.userPoint').text($.setNumberFormat(userPoint));
  $('.paymentCost').text($.setNumberFormat(paymentCost));
}).on('click', '.using-point-all', function() {
  // 포인트 전액 사용
  var result = 0;
  var usingPoint = Number($('.using-point').val()); // 사용한 포인트
  var userPoint = Number($('input[name=userPoint]').val()); // 총 포인트
  var totalCost = Number($('input[name=totalCost]').val()); // 합계금액
  var paymentCost = Number($('input[name=paymentCost]').val()); // 결제금액

  if (usingPoint < 0) {
    if (paymentCost > userPoint) {
      // 결제금액이 총 포인트보다 높을때는 남은 포인트만큼만 결제
      usingPoint = paymentCost;
      userPoint = 0;
      paymentCost = 0;
    } else {
      // 결제금액이 총 포인트보다 낮을때는 결제금액 전액 결제
      usingPoint = paymentCost;
      userPoint = userPoint - usingPoint;
      paymentCost = 0;
    }
  } else {
    $.openMsgModal('보유한 포인트만 사용할 수 있습니다.');
    usingPoint = '';
    paymentCost = totalCost;
  }

  $('input[name=usingPoint]').val(usingPoint);
  $('input[name=paymentCost]').val(paymentCost);
  $('.userPoint').text($.setNumberFormat(userPoint));
  $('.paymentCost').text($.setNumberFormat(paymentCost));
}).on('click', '.btn-checkout', function() {
  // 구매 완료하기
  var $btn = $(this);
  var $dom = $('#formCheckout');
  var formData = new FormData($dom[0]);
  $.ajax({
    url: $dom.attr('action'),
    processData: false,
    contentType: false,
    data: formData,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
    },
    success: function(result) {
      if (result.error == 1) {
        $btn.css('opacity', '1').prop('disabled', false).text('구매 완료하기');
        $.openMsgModal(result.message);
      } else {
        location.href = (result.message);
      }
    }
  });
});