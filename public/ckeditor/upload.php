<?php
if (strstr($_SERVER['HTTP_HOST'], 'giwb.localhost') == true) {
  define('EDITOR_PATH', '/mamp/htdocs/giwb/public/uploads/editor/');
} elseif (strstr($_SERVER['HTTP_HOST'], 'giwb.kr') == true) {
  define('EDITOR_PATH', '/home/bitnami/htdocs/giwb/public/uploads/editor');
} else {
  define('EDITOR_PATH', '/home/sayhome/www/giwb/public/uploads/editor/');
}
define('EDITOR_URL', '/public/uploads/editor/');

if (isset($_FILES['upload'])) {
  $ckCsrfToken = $_POST['ckCsrfToken'];
  $filename = time() . mt_rand(10000, 99999) . '.jpg';
  move_uploaded_file($_FILES['upload']['tmp_name'], EDITOR_PATH . $filename);
  $url = EDITOR_URL . $filename;
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