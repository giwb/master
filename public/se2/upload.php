<?php
if ($_POST['action'] == 'process') {
  if (strstr($_SERVER['HTTP_HOST'], 'tripkorea.localhost') == true) {
    define('EDITOR_PATH', '/mamp/htdocs/tripkorea/public/uploads/editor/');
  } else {
    define('EDITOR_PATH', '/home/bitnami/htdocs/public/uploads/editor/');
  }
  define('EDITOR_URL', '/public/uploads/editor/');

  $files = $_FILES['files'];
  foreach ($files['tmp_name'] as $key => $value) {
    if (!empty($value)) {
      if ($files['type'][$key] == 'image/jpeg') {
        $ext = ".jpg";
      } else {
        $ext = ".jpg";
      }
      $filename = time() . mt_rand(10000, 99999) . $ext;
      if (move_uploaded_file($value, EDITOR_PATH . $filename)) {
        // 썸네일 만들기
        $this->image_lib->clear();
        $config['image_library'] = 'gd2';
        $config['source_image'] = EDITOR_PATH . $filename;
        $config['new_image'] = EDITOR_PATH . 'thumb_' . $filename;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['thumb_marker'] = '';
        $config['width'] = 500;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
      }
    }
  }
  exit;
}
?>
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
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="process">
      <input type="file" name="files[]" multiple="multiple" class="multi" accept="gif|jpg|png|jpeg" />
      <br><button type="button" class="btn btn-primary btn-upload">사진 전송</button>
    </form>
  </div>
  <script type="text/javascript">
    $(document).on('click', '.btn-upload', function() {
      // 파일 업로드
      var $btn = $(this);
      var $dom = $('.multi');
      var formData = new FormData($('form')[0]);
      $.ajax({
        url: 'upload.php',
        processData: false,
        contentType: false,
        data: formData,
        dataType: 'json',
        type: 'post',
        beforeSend: function() {
          $btn.hide();
        },
        success: function(result) {
          $btn.show();
        }
      });
    });
  </script>
</body>
</html>