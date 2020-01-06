$(document).on('click', '.shop-item', function() {
  // 용품 상세 페이지
  location.href = ( $('input[name=baseUrl]').val() + 'club/shop_item/' + $('input[name=clubIdx]').val() + '?n=' + $(this).data('idx') );
}).on('click', '.btn-cart', function() {
  // 장바구니에 담기
  var $btn = $(this);
  var baseUrl = $('input[name=baseUrl]').val();
  var clubIdx = $('input[name=clubIdx]').val();
  var idx = $(this).data('idx');
  var amount = $('select[name=amount]').val();
  var buy_type = $(this).data('type');
  var item_option = $('.item-option').val();

  if (typeof item_option != 'undefined') {
    if (item_option == '') {
      $.openMsgModal('옵션은 꼭 선택해주세요.');
      return false;
    }
    var data = 'idx=' + idx + '&amount=' + amount + '&item_option=' + item_option + '&buy_type=' + buy_type;
  } else {
    item_option = '';
    var data = 'idx=' + idx + '&amount=' + amount + '&buy_type=' + buy_type;
  }

  $.ajax({
    url: baseUrl + 'club/shop_cart_insert/' + clubIdx,
    data: data,
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
}).on('change', '.item-option', function() {
  // 가격 변경
  var $dom = $(this);
  var addedPrice = $('option:selected', $dom).data('added-price')
  var addedCost  = $('option:selected', $dom).data('added-cost')
  if (typeof addedPrice != 'undefined' && addedPrice != 0) $('.item-price').text($.setNumberFormat(addedPrice));
  if (typeof addedCost  != 'undefined' && addedCost  != 0) $('.item-cost').text($.setNumberFormat(addedCost));
}).on('change', '.cart-amount', function() {
  // 수량 변경
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
    $('input[name=paymentCost]').val(totalCost);
    $('.userPoint').text($.setNumberFormat(userPoint));
    $('.paymentCost').text($.setNumberFormat(totalCost));
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
  var userPoint = Number($('input[name=userPoint]').val()); // 사용자 보유 총 포인트
  var totalCost = Number($('input[name=totalCost]').val()); // 합계금액
  var paymentCost = Number($('input[name=paymentCost]').val()); // 결제금액

  if (userPoint > usingPoint) {
    if (paymentCost > userPoint) {
      // 결제금액이 사용자 보유 총 포인트보다 높을때는 남은 포인트만큼만 결제
      usingPoint = userPoint;
      paymentCost = totalCost - userPoint;
      userPoint = 0;
    } else {
      // 결제금액이 총 포인트보다 낮을때는 결제금액 전액 결제
      usingPoint = totalCost;
      userPoint = userPoint - usingPoint;
      paymentCost = 0;
    }

    $('input[name=usingPoint]').val(usingPoint);
    $('input[name=paymentCost]').val(paymentCost);
    $('.userPoint').text($.setNumberFormat(userPoint));
    $('.paymentCost').text($.setNumberFormat(paymentCost));
  }
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