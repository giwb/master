<?php
defined('BASEPATH')           OR exit('No direct script access allowed');

// 경로 설정
defined('PATH_MAIN')          OR define('PATH_MAIN', '/home/sayhome/www/giwb');

// 테이블명 - 한국여행
defined('DB_AREAS')           OR define('DB_AREAS', 'sayhome_tripkorea.areas');
defined('DB_CLUBS')           OR define('DB_CLUBS', 'sayhome_tripkorea.clubs');
defined('DB_FILES')           OR define('DB_FILES', 'sayhome_tripkorea.files');
defined('DB_STORY')           OR define('DB_STORY', 'sayhome_tripkorea.story');
defined('DB_STORY_REACTION')  OR define('DB_STORY_REACTION', 'sayhome_tripkorea.story_reaction');
defined('DB_STORY_REPLY')     OR define('DB_STORY_REPLY', 'sayhome_tripkorea.story_reply');


// 업로드 디렉토리
defined('UPLOAD_PATH')        OR define('UPLOAD_PATH', '/home/sayhome/www/giwb/public/uploads/');
defined('UPLOAD_URL')         OR define('UPLOAD_URL', 'public/uploads/');

// 사진 디렉토리
defined('PHOTO_PATH')         OR define('PHOTO_PATH', '/home/sayhome/www/giwb/public/photos/');
defined('PHOTO_URL')          OR define('PHOTO_URL', 'public/photos/');

// 에디터 업로드 디렉토리
defined('EDITOR_PATH')        OR define('EDITOR_PATH', '/home/sayhome/www/giwb/public/uploads/editor/');
defined('EDITOR_URL')         OR define('EDITOR_URL', 'public/uploads/editor/');
