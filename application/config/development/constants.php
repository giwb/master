<?php
defined('BASEPATH')           OR exit('No direct script access allowed');

// 경로 설정
defined('PATH_MAIN')          OR define('PATH_MAIN', '/mamp/htdocs/giwb');

// 테이블명 - 한국여행
defined('DB_AREAS')           OR define('DB_AREAS', 'tripkorea_db.areas');
defined('DB_CLUBS')           OR define('DB_CLUBS', 'tripkorea_db.clubs');
defined('DB_FILES')           OR define('DB_FILES', 'tripkorea_db.files');
defined('DB_STORY')           OR define('DB_STORY', 'tripkorea_db.story');
defined('DB_STORY_REACTION')  OR define('DB_STORY_REACTION', 'tripkorea_db.story_reaction');
defined('DB_STORY_REPLY')     OR define('DB_STORY_REPLY', 'tripkorea_db.story_reply');

// 업로드 디렉토리
defined('UPLOAD_PATH')        OR define('UPLOAD_PATH', '/mamp/htdocs/giwb/public/uploads/');
defined('UPLOAD_URL')         OR define('UPLOAD_URL', 'public/uploads/');

// 사진 디렉토리
defined('PHOTO_PATH')         OR define('PHOTO_PATH', '/mamp/htdocs/giwb/public/photos/');
defined('PHOTO_URL')          OR define('PHOTO_URL', 'public/photos/');
