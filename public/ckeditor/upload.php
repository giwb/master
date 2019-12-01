<?php
if (strstr($_SERVER['HTTP_HOST'], 'giwb.localhost') == true) {
  define('UPLOAD_PATH', 'd:/mamp/htdocs/giwb/public/uploads/');
} else {
  define('UPLOAD_PATH', '/home/sayhome/www/giwb.new/public/uploads/');
}
define('UPLOAD_URL', '/public/uploads/');

if (isset($_FILES['upload'])) {
  $ckCsrfToken = $_POST['ckCsrfToken'];
  $filename = time() . mt_rand(10000, 99999) . '.jpg';
  move_uploaded_file($_FILES['upload']['tmp_name'], UPLOAD_PATH . $filename);
  $url = UPLOAD_URL . $filename;
  $message = '';
} else {
  $message = '업로드된 파일이 없습니다.';
}

$result = array(
  'uploaded' => 1,
  'fileName' => $url,
  'url' => $url
);

echo json_encode($result);

?>