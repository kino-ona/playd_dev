<?php
if (!defined('_ADM_')) exit;

// 로컬 기본값. Docker(web 컨테이너)에서는 PLAYD_DB_* 환경변수로 덮어씀 (docker-compose.yml 참고).
$__playd_dbenv = static function (string $key, string $default): string {
    $v = getenv($key);

    return $v === false ? $default : $v;
};
define('P1_MYSQL_HOST', $__playd_dbenv('PLAYD_DB_HOST', '127.0.0.1'));
define('P1_MYSQL_USER', $__playd_dbenv('PLAYD_DB_USER', 'root'));
define('P1_MYSQL_PASSWORD', $__playd_dbenv('PLAYD_DB_PASSWORD', ''));
define('P1_MYSQL_DB', $__playd_dbenv('PLAYD_DB_NAME', 'playd'));
// 호스트에서 Docker MySQL(3307:3306) 등 비표준 포트: PLAYD_DB_PORT=3307
define('P1_MYSQL_PORT', (int) $__playd_dbenv('PLAYD_DB_PORT', '3306'));
define('P1_MYSQL_SET_MODE', false);
unset($__playd_dbenv);

// define('P1_MYSQL_HOST', '10.82.96.52');
// define('P1_MYSQL_USER', 'root');
// define('P1_MYSQL_PASSWORD', '9560');
// define('P1_MYSQL_DB', 'playd');
// define('P1_MYSQL_SET_MODE', false);


$p1['mable_t_support_table']      = 'mable_T_SUPPORT';         # (MABLE) 입사지원관리 테이블
$p1['mable_t_support_view_table'] = 'mable_T_SUPPORT_VIEW';    # (MABLE) 입사지원자 조회 테이블

$p1['t_ask_table']                = 'T_ASK';                   # 문의 관리 테이블

$p1['t_board_table']              = 'T_BOARD';                 # 게시판 관리 테이블
$p1['t_board_config_table']       = 'T_BOARD_CONFIG';          # 게시판 설정 관리 테이블
$p1['t_board_recom_table']        = 'T_BOARD_RECOM';           # 게시글 해시태그 테이블

$p1['t_comment_table']            = 'T_COMMENT';               # 댓글 관리 테이블

$p1['t_menu_config_table']        = 'T_MENU_CONFIG';           # 메뉴 설정 관리 테이블
$p1['t_mgr_table']                = 'T_MGR';                   # 관리자 관리 테이블

$p1['t_newsletter_table']         = 'T_NEWSLETTER';            # 뉴스레터 신청자 테이블

$p1['t_pool_table']               = 'T_POOL';                  # 인재풀관리 테이블
$p1['t_pool_view_table']          = 'T_POOL_VIEW';             # 인재풀관리 조회 테이블

$p1['t_report_table']             = 'T_REPORT';                # 윤리경영제보 테이블

$p1['t_support_table']            = 'T_SUPPORT';               # (PLAYD) 입사지원관리 테이블
$p1['t_support_view_table']       = 'T_SUPPORT_VIEW';          # (PLAYD) 입사지원자 조회 테이블

$p1['t_pds_table']       = 'T_PDS';          # PDS 테이블
$p1['t_pds_category_table']       = 'T_PDS_CATEGORY';          # PDS category 테이블
?>
