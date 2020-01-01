<?php
defined('BASEPATH')           OR exit('No direct script access allowed');

// 경로 설정
defined('PATH_MAIN')          OR define('PATH_MAIN', '/mamp/htdocs/giwb');

// 테이블명 - 한국여행
defined('DB_ALBUM')           OR define('DB_ALBUM', 'tripkorea_db.album');
defined('DB_AREAS')           OR define('DB_AREAS', 'tripkorea_db.areas');
defined('DB_CLUBS')           OR define('DB_CLUBS', 'tripkorea_db.clubs');
defined('DB_FILES')           OR define('DB_FILES', 'tripkorea_db.files');
defined('DB_STORY')           OR define('DB_STORY', 'tripkorea_db.story');
defined('DB_STORY_REACTION')  OR define('DB_STORY_REACTION', 'tripkorea_db.story_reaction');
defined('DB_STORY_REPLY')     OR define('DB_STORY_REPLY', 'tripkorea_db.story_reply');

// 베이스 디렉토리
defined('BASE_PATH')          OR define('BASE_PATH', '/mamp/htdocs/giwb');

// 업로드 디렉토리
defined('UPLOAD_PATH')        OR define('UPLOAD_PATH', BASE_PATH . '/public/uploads/');
defined('UPLOAD_URL')         OR define('UPLOAD_URL', 'public/uploads/');

// 사진 디렉토리
defined('PHOTO_PATH')         OR define('PHOTO_PATH', BASE_PATH . '/public/photos/');
defined('PHOTO_URL')          OR define('PHOTO_URL', 'public/photos/');

// 에디터 업로드 디렉토리
defined('EDITOR_PATH')        OR define('EDITOR_PATH', BASE_PATH . '/public/uploads/editor/');
defined('EDITOR_URL')         OR define('EDITOR_URL', 'public/uploads/editor/');
