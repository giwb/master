<!DOCTYPE html>
<html>
<head>
  <title>한국여행 데스크</title>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR&display=swap" rel="stylesheet">
  <link href="/public/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/css/desk.css" rel="stylesheet">
  <script type="text/javascript" src="/public/js/jquery-3.3.1.min.js"></script>
  <script type="text/javascript" src="/public/js/jquery.MultiFile.min.js"></script>
  <script type="text/javascript" src="/public/js/bootstrap.min.js"></script>
</head>
<body>
  <div class="p-3">
    <h4>사진 올리기</h4><hr>
    <form id="formPhoto" method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="process">
      <input type="file" name="files[]" multiple="multiple" class="multi" accept="gif|jpg|png|jpeg" />
      <br><button type="button" class="btn btn-primary btn-upload">사진 전송</button>
      <div class="ajax-loader text-center pt-3 d-none">
        <img src="/public/images/ajax-loader.gif">
      </div>
    </form>
  </div>
  <script type="text/javascript">
    $(document).on('click', '.btn-upload', function() {
      // 파일 업로드
      var $btn = $(this);
      var $dom = $('.multi');
      var formData = new FormData($('#formPhoto')[0]);
      $.ajax({
        url: '/desk/upload_process',
        processData: false,
        contentType: false,
        data: formData,
        dataType: 'json',
        type: 'post',
        beforeSend: function() {
          $btn.hide();
          $('.multi, .MultiFile-list').hide();
          $('.ajax-loader').removeClass('d-none');
        },
        success: function(result) {
          opener.nhn.husky.PopUpManager.setCallback(window, 'SET_PHOTO', [result]);
          window.close();
        }
      });
    });
  </script>

</body>
</html>