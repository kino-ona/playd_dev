<?php
/********************
    상수 선언
********************/
define('_VER_', '5.3.2.8');

// 이 상수가 정의되지 않으면 각각의 개별 페이지는 별도로 실행될 수 없음
define('_ADM_', true);

if (PHP_VERSION >= '5.1.0') {
    //if (function_exists("date_default_timezone_set")) date_default_timezone_set("Asia/Seoul");
    date_default_timezone_set("Asia/Seoul");
}

/********************
    경로 상수
********************/

/*
보안서버 도메인
회원가입, 글쓰기에 사용되는 https 로 시작되는 주소를 말합니다.
포트가 있다면 도메인 뒤에 :443 과 같이 입력하세요.
보안서버주소가 없다면 공란으로 두시면 되며 보안서버주소 뒤에 / 는 붙이지 않습니다.
입력예) https://www.domain.com:443/test
*/
define('P1_DOMAIN', '');
define('P1_HTTPS_DOMAIN', '');

/*
www.domain.com 과 domain.com 도메인은 서로 다른 도메인으로 인식합니다. 쿠키를 공유하려면 .domain.com 과 같이 입력하세요.
이곳에 입력이 없다면 www 붙은 도메인과 그렇지 않은 도메인은 쿠키를 공유하지 않으므로 로그인이 풀릴 수 있습니다.
*/
// define('P1_COOKIE_DOMAIN',  '.nsm.co.kr');
// define('P1_COOKIE_DOMAIN',  $_SERVER['SERVER_NAME']);
$__playd_http_host = isset($_SERVER['HTTP_HOST']) ? preg_replace('/:\d+$/', '', (string) $_SERVER['HTTP_HOST']) : '';
if ($__playd_http_host === 'localhost' || $__playd_http_host === '127.0.0.1' || $__playd_http_host === '[::1]') {
    define('P1_COOKIE_DOMAIN', '');
} else {
    define('P1_COOKIE_DOMAIN', '49.236.137.157');
}
unset($__playd_http_host);

define('P1_DBCONFIG_FILE',  'dbconfig.php');

define('P1_CSS_DIR',        'css');
define('P1_DATA_DIR',       'data');
define('P1_FONT_DIR',       'font');
define('P1_IMAGES_DIR',     'images');
define('P1_JS_DIR',         'js');
define('P1_LIB_DIR',        'lib');
define('P1_PAGE_DIR',       'page');
define('P1_VIDEO_DIR',      'video');
define('P1_POST_SKIN_DIR',  'post_skin');
define('P1_MOBILE_DIR',     'mobile');
define('P1_SESSION_DIR',    'session');

define('P1_EDITOR_DIR',     'editor');

define('P1_PHPMAILER_DIR',  'PHPMailer');

// 외부 파일 업로드 폴더
define('P1_NSM_DIR',        'nsm_');      // ./nsm_
define('P1_UPLOAD_DIR',     'upload');    // ./upload
define('P1_UPFILE_DIR',     'upfile');    // ../upfile

// URL 은 브라우저상에서의 경로 (도메인으로 부터의)
if (P1_DOMAIN) {
    define('P1_URL', P1_DOMAIN);
} else {
    if (isset($p1_path['url']))
        define('P1_URL', $p1_path['url']);
    else
        define('P1_URL', '');
}

if (isset($p1_path['path'])) {
    define('P1_PATH', $p1_path['path']);
} else {
    define('P1_PATH', '');
}

if (isset($p1_path['path2'])) {
    define('P1_PATH2', $p1_path['path2']);
} else {
    define('P1_PATH2', '');
}

if (isset($p1_path['path3'])) {
    define('P1_PATH3', $p1_path['path3']);
} else {
    define('P1_PATH3', '');
}

define('P1_CSS_URL',          P1_URL.'/'.P1_CSS_DIR);
define('P1_DATA_URL',         P1_URL.'/'.P1_DATA_DIR);
define('P1_FONT_URL',         P1_URL.'/'.P1_FONT_DIR);
define('P1_IMAGES_URL',       P1_URL.'/'.P1_IMAGES_DIR);
define('P1_JS_URL',           P1_URL.'/'.P1_JS_DIR);
define('P1_PAGE_URL',         P1_URL.'/'.P1_PAGE_DIR);
define('P1_VIDEO_URL',        P1_URL.'/'.P1_VIDEO_DIR);

define('P1_EDITOR_URL',       P1_JS_URL.'/'.P1_EDITOR_DIR);

define('P1_NSM_URL',          'https://www.playd.com/'.P1_NSM_DIR);

// PATH 는 서버상에서의 절대경로
define('P1_DATA_PATH',        P1_PATH.'/'.P1_DATA_DIR);
define('P1_JS_PATH',          P1_PATH.'/'.P1_JS_DIR);
define('P1_LIB_PATH',         P1_PATH.'/'.P1_LIB_DIR);
define('P1_PAGE_PATH',        P1_PATH.'/'.P1_PAGE_DIR);
define('P1_POST_SKIN_PATH',   P1_PATH.'/'.P1_PAGE_DIR.'/'.P1_POST_SKIN_DIR);
define('P1_SESSION_PATH',     P1_DATA_PATH.'/'.P1_SESSION_DIR);
define('P1_EDITOR_PATH',      P1_JS_PATH.'/'.P1_EDITOR_DIR);

define('P1_PHPMAILER_PATH',   P1_LIB_PATH.'/'.P1_PHPMAILER_DIR);

define('Y1_PHPMAILER_PATH',   P1_LIB_PATH.'/'.P1_PHPMAILER_DIR);
define('Y1_SMTP',      'smtp.playd.info');
define('Y1_SMTP_PORT', '587');

define('Y1_FROM_NAME', '');
define('Y1_FROM_MAIL', '');


// 외부 파일 업로드 절대경로
define('P1_NSM_PATH',         P1_PATH2.'/'.P1_NSM_DIR);       # /home/playd/nsm.co.kr/nsm_
define('P1_UPLOAD_PATH',      P1_PATH2.'/'.P1_UPLOAD_DIR);    # /home/playd/nsm.co.kr/upload
define('P1_UPFILE_PATH',      P1_PATH3.'/'.P1_UPFILE_DIR);    # /home/playd/upfile

//==============================================================================

/********************
    시간 상수
********************/
// 서버의 시간과 실제 사용하는 시간이 틀린 경우 수정하세요.
// 하루는 86400 초입니다. 1시간은 3600초
// 6시간이 빠른 경우 time() + (3600 * 6);
// 6시간이 느린 경우 time() - (3600 * 6);
define('P1_SERVER_TIME',     time());
define('P1_TIME_YMDHIS',     date('Y-m-d H:i:s', P1_SERVER_TIME));
define('P1_TIME_YMDHIS_ORG', date('YmdHis', P1_SERVER_TIME));
define('P1_TIME_YMD',        substr(P1_TIME_YMDHIS, 0, 10));
define('P1_TIME_HIS',        substr(P1_TIME_YMDHIS, 11, 8));

// 쿠폰 만료시간 상수
define('P1_COUPON_EXP_HOURS', (60 * 60 * 12));

// 입력값 검사 상수 (숫자를 변경하시면 안됩니다.)
define('P1_ALPHAUPPER',      1); // 영대문자
define('P1_ALPHALOWER',      2); // 영소문자
define('P1_ALPHABETIC',      4); // 영대,소문자
define('P1_NUMERIC',         8); // 숫자
define('P1_HANGUL',         16); // 한글
define('P1_SPACE',          32); // 공백
define('P1_SPECIAL',        64); // 특수문자

// 퍼미션
define('P1_DIR_PERMISSION',  0755); // 디렉토리 생성시 퍼미션
define('P1_FILE_PERMISSION', 0644); // 파일 생성시 퍼미션

// SMTP
// lib/mailer.lib.php 에서 사용
define('P1_SMTP',      '127.0.0.1');
define('P1_SMTP_PORT', '25');

define('P1_FROM_NAME', '');
define('P1_FROM_MAIL', '');

// 모바일 인지 결정 $_SERVER['HTTP_USER_AGENT']
define('P1_MOBILE_AGENT',   'phone|samsung|lgtel|mobile|[^A]skt|nokia|blackberry|BB10|android|sony');

/********************
    기타 상수
********************/

// 암호화 함수 지정
// 사이트 운영 중 설정을 변경하면 로그인이 안되는 등의 문제가 발생합니다.
define('P1_STRING_ENCRYPT_FUNCTION', 'sha1');

// SQL 에러를 표시할 것인지 지정
// 에러를 표시하려면 TRUE 로 변경
define('P1_DISPLAY_SQL_ERROR', FALSE);

// escape string 처리 함수 지정
// addslashes 로 변경 가능
// define('P1_ESCAPE_FUNCTION', 'addslashes');
define('P1_ESCAPE_FUNCTION', 'sql_escape_string');

// sql_escape_string 함수에서 사용될 패턴
define('P1_ESCAPE_PATTERN',  '/(and|or).*(union|select|insert|update|delete|from|where|limit|create|drop).*/i');
define('P1_ESCAPE_REPLACE',  '');

// 썸네일 jpg Quality 설정
define('P1_THUMB_JPG_QUALITY', 70);

// 썸네일 png Compress 설정
define('P1_THUMB_PNG_COMPRESS', 5);

// 썸네일 처리 방식, 비율유지 하지 않고 썸네일을 생성하려면 주석을 풀고 값은 false 입력합니다.
// ( true 또는 주석으로 된 경우에는 비율 유지합니다. )
// define('P1_USE_THUMB_RATIO', false);

// MySQLi 사용여부를 설정합니다.
define('P1_MYSQLI_USE', true);

// ip 숨김방법 설정
/* 123.456.789.012 ip의 숨김 방법을 변경하는 방법은
\\1 은 123, \\2는 456, \\3은 789, \\4는 012에 각각 대응되므로
표시되는 부분은 \\1 과 같이 사용하시면 되고 숨길 부분은 ♡등의
다른 문자를 적어주시면 됩니다.
*/
define('P1_IP_DISPLAY', '\\1.♡.\\3.\\4');

/********************
    기본 이미지 상수
********************/
define('P1_NOIMG_GALLERY', '/images/sample/noimg_gallery.png');    # 300x300
define('P1_NOIMG_MENU',    '/images/sample/noimg_menu.png');       # 64x64
define('P1_NOIMG_STORE',   '/images/sample/noimg_store.png');      # 72x72
define('P1_NOIMG_TILE',    '/images/sample/noimg_tile.png');       # 300x300
define('P1_NOIMG_USER',    '/images/sample/noimg_user.png');       # 96x96
define('P1_NOIMG_VISUAL',  '/images/sample/noimg_visual.png');     # 360x190

/********************
    smarteditor2 상수
********************/
// smarteditor2 JS
define('P1_SMART_EDIT_JS',  '<script type="text/javascript" src="'.P1_JS_URL.'/smarteditor2/js/service/HuskyEZCreator.js" charset="utf-8"></script>');

/********************
    dtpicker 상수
********************/
// dtpicker CSS, JS
define('P1_DTPICKER_CSS', '<link href="'.P1_CSS_URL.'/jquery.simple-dtpicker.css" rel="stylesheet"/>');
define('P1_DTPICKER_JS',  '<script src="'.P1_JS_URL.'/jquery.simple-dtpicker.js"></script>');

/********************
    MENU 상수
********************/
define('M_AUTH_TP_ALL', '1');     # 관리자 권한 (전체)
define('M_AUTH_TP_GEN', '2');     # 관리자 권한 (일반)

// 게시판관리
define('BOARD',         'a');     # 게시판관리
define('BOARDA',        'aa');    # 회사소식
define('BOARDB',        'ab');    # 뉴스레터
define('BOARDC',        'ac');    # 칼럼관리
define('BOARDD',        'ad');    # 성공사례
define('BOARDE',        'ae');    # FAQ
define('BOARDF',        'af');    # 개인정보처리방침
define('BOARDG',        'ag');    # 포트폴리오

// 문의관리
define('ASK',           'b');     # 문의관리
define('ASKA',          'ba');    # 광고문의
define('ASKB',          'bb');    # 제휴문의
define('ASKC',          'bc');    # 윤리경영제보
define('ASKD',          'bd');    # 글로벌커머스

// 채용관리(PLAYD)
define('JOB',           'c');     # 채용관리(PLAYD)
define('JOBA',          'ca');    # 입사지원관리
define('JOBB',          'cb');    # 채용문의
define('JOBC',          'cc');    # 인재풀관리

// 채용관리(MABLE)
define('JOB_MABLE',     'd');     # 채용관리(MABLE)
define('JOB_MABLE_A',   'da');    # 입사지원관리
define('JOB_MABLE_B',   'db');    # 채용문의

define('NEWLETTER',     'e');     # 뉴스레터신청자
define('IMAGEUPLOAD',   'f');     # 파일업로드

/********************
    ROLE 상수
********************/
define('ROLE_ADMIN',       'ROLE_ADMIN');          # 최고관리자

// 게시물관리
define('ROLE_BOARD',       'ROLE_BOARD');          # 게시물관리
define('ROLE_BOARDA',      'ROLE_BOARDA');         # 회사소식
define('ROLE_BOARDB',      'ROLE_BOARDB');         # 뉴스레터
define('ROLE_BOARDC',      'ROLE_BOARDC');         # 칼럼관리
define('ROLE_BOARDD',      'ROLE_BOARDD');         # 성공사례
define('ROLE_BOARDE',      'ROLE_BOARDE');         # FAQ
define('ROLE_BOARDF',      'ROLE_BOARDF');         # 개인정보처리방침
define('ROLE_BOARDG',      'ROLE_BOARDG');         # 포트폴리오

// 문의관리
define('ROLE_ASK',         'ROLE_ASK');            # 문의관리
define('ROLE_ASKA',        'ROLE_ASKA');           # 광고문의
define('ROLE_ASKB',        'ROLE_ASKB');           # 제휴문의
define('ROLE_ASKC',        'ROLE_ASKC');           # 윤리경영제보
define('ROLE_ASKD',        'ROLE_ASKD');           # 글로벌커머스

// 채용관리(PLAYD)
define('ROLE_JOB',         'ROLE_JOB');            # 채용관리(PLAYD)
define('ROLE_JOBA',        'ROLE_JOBA');           # 입사지원관리
define('ROLE_JOBB',        'ROLE_JOBB');           # 채용문의
define('ROLE_JOBC',        'ROLE_JOBC');           # 인재풀관리

// 채용관리(MABLE)
define('ROLE_JOB_MABLE',   'ROLE_JOB_MABLE');      # 채용관리(MABLE)
define('ROLE_JOB_MABLE_A', 'ROLE_JOB_MABLE_A');    # 입사지원관리
define('ROLE_JOB_MABLE_B', 'ROLE_JOB_MABLE_B');    # 채용문의

define('ROLE_NEWLETTER',   'ROLE_NEWLETTER');      # 뉴스레터신청자
define('ROLE_IMAGEUPLOAD', 'ROLE_IMAGEUPLOAD');    # 파일업로드

// version.extend
define('P1_JS_VER',  '190311');
define('P1_CSS_VER', '190311');
?>