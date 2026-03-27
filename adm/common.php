<?php

/*******************************************************************************
** 공통 변수, 상수, 코드
*******************************************************************************/
// error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING );
error_reporting( E_CORE_ERROR | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR );

if ($_SERVER['REMOTE_ADDR'] == "49.254.187.230") {
    // error_reporting(E_ALL);
    // ini_set("display_errors", "On");
}

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

if (!defined('P1_SET_TIME_LIMIT')) define('P1_SET_TIME_LIMIT', 0);
@set_time_limit(P1_SET_TIME_LIMIT);


//==========================================================================================================================
// extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
//--------------------------------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
                  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
                  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for ($i=0; $i<$ext_cnt; $i++) {
    // POST, GET 으로 선언된 전역변수가 있다면 unset() 시킴
    if (isset($_GET[$ext_arr[$i]]))  unset($_GET[$ext_arr[$i]]);
    if (isset($_POST[$ext_arr[$i]])) unset($_POST[$ext_arr[$i]]);
}
//==========================================================================================================================

function p1_path()
{
    $chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
    $result['path'] = str_replace('\\', '/', $chroot.dirname(__FILE__));
    $result['path2'] = str_replace('\\', '/', $chroot.dirname(dirname(__FILE__)));
    $result['path3'] = str_replace('\\', '/', $chroot.dirname(dirname(dirname(__FILE__))));
    $server_script_name = preg_replace('/\/+/', '/', str_replace('\\', '/', $_SERVER['SCRIPT_NAME']));
    $server_script_filename = preg_replace('/\/+/', '/', str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']));
    $tilde_remove = preg_replace('/^\/\~[^\/]+(.*)$/', '$1', $server_script_name);
    $document_root = str_replace($tilde_remove, '', $server_script_filename);
    $pattern = '/' . preg_quote($document_root, '/') . '/i';
    $root = preg_replace($pattern, '', $result['path']);
    $port = ($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443) ? '' : ':'.$_SERVER['SERVER_PORT'];
    $http = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 's' : '') . '://';
    $user = str_replace(preg_replace($pattern, '', $server_script_filename), '', $server_script_name);
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
    if(isset($_SERVER['HTTP_HOST']) && preg_match('/:[0-9]+$/', $host))
        $host = preg_replace('/:[0-9]+$/', '', $host);
    $host = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", '', $host);
    $result['url'] = $http.$host.$port.$user.$root;
    return $result;
}

if (!isset($p1_path) || !is_array($p1_path)) {
    $p1_path = p1_path();
}

include_once($p1_path['path'].'/config.php');   // 설정 파일

unset($p1_path);


$menu_status = array(
    'administration.php' => 'a1',
    'administration_edit.php' => 'a1',
    'administration_auth.php' => 'a2',
    'administration_ip.php' => 'a3',
    'board.php' => 'b',
    'board_edit.php' => 'b',
    'post.php?bc_code=nsmnw' => 'c1|nsmnw',
    'post.php?bc_code=report' => 'c2|report',
    'post.php?bc_code=nsmexp' => 'c3|nsmexp',
    'post.php?bc_code=playdportfolio' => 'h2|playdportfolio',
    'post.php?bc_code=playdprivate' => 'c5|playdprivate',
    'post.php?bc_code=ir_notice' => 'c6|ir_notice',
    'post.php?bc_code=pr_notice' => 'c7|pr_notice',
    'post_write.php?bc_code=nsmnw' => 'c1|nsmnw',
    'post_write.php?bc_code=report' => 'c2|report',
    'post_write.php?bc_code=nsmexp' => 'c3|nsmexp',
    'post_write.php?bc_code=playdportfolio' => 'c4|playdportfolio',
    'post_write.php?bc_code=playdprivate' => 'c5|playdprivate',
    'post_write.php?bc_code=ir_notice' => 'c6|ir_notice',
    'post_write.php?bc_code=pr_notice' => 'c7|pr_notice',
    'qna.php?bc_code=nsmad' => 'd1|nsmad',
    'qna_write.php?bc_code=nsmad' => 'd1|nsmad',
    'report.php' => 'd2',
    'report_edit.php' => 'd2',
    'm_incident.php' => 'e1',
    'm_incident_edit.php' => 'e1',
    'm_incident_sang.php' => 'e2',
    'm_incident_sang_edit.php' => 'e2',
    'newsletter.php' => 'f1',
    'newsreport.php' => 'f2',
    'post.php?bc_code=files' => 'g|files');


// multi-dimensional array에 사용자지정 함수적용
function array_map_deep($fn, $array)
{
    if(is_array($array)) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                $array[$key] = array_map_deep($fn, $value);
            } else {
                $array[$key] = call_user_func($fn, $value);
            }
        }
    } else {
        $array = call_user_func($fn, $array);
    }

    return $array;
}

// SQL Injection 대응 문자열 필터링
function sql_escape_string($str)
{
    if(defined('P1_ESCAPE_PATTERN') && defined('P1_ESCAPE_REPLACE')) {
        $pattern = P1_ESCAPE_PATTERN;
        $replace = P1_ESCAPE_REPLACE;

        if($pattern)
            $str = preg_replace($pattern, $replace, $str);
    }

    $str = call_user_func('addslashes', $str);

    return $str;
}

// 특수문자 제거
function clean_specialchars($str)
{
    $pattern = '/["\'<>=#&!%\\\\(\)\*\+\?]/';
    $str = preg_replace($pattern, '', $str);

    return $str;
}

//==============================================================================
// SQL Injection 등으로 부터 보호를 위해 sql_escape_string() 적용
//------------------------------------------------------------------------------
// magic_quotes_gpc 에 의한 backslashes 제거 (PHP 8.0 에서 함수 제거됨 → 존재할 때만)
if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    $_POST    = array_map_deep('stripslashes',  $_POST);
    $_GET     = array_map_deep('stripslashes',  $_GET);
    $_COOKIE  = array_map_deep('stripslashes',  $_COOKIE);
    $_REQUEST = array_map_deep('stripslashes',  $_REQUEST);
}

// sql_escape_string 적용
// $_POST    = array_map_deep(P1_ESCAPE_FUNCTION,  $_POST);
$_GET     = array_map_deep(P1_ESCAPE_FUNCTION,  $_GET);
$_COOKIE  = array_map_deep(P1_ESCAPE_FUNCTION,  $_COOKIE);
$_REQUEST = array_map_deep(P1_ESCAPE_FUNCTION,  $_REQUEST);
//==============================================================================

//==============================================================================
// 특수문자 제거
//------------------------------------------------------------------------------
// $_POST    = array_map_deep('clean_specialchars',  $_POST);
// $_GET     = array_map_deep('clean_specialchars',  $_GET);
// $_COOKIE  = array_map_deep('clean_specialchars',  $_COOKIE);
// $_REQUEST = array_map_deep('clean_specialchars',  $_REQUEST);
//==============================================================================

// PHP 4.1.0 부터 지원됨
// php.ini 의 register_globals=off 일 경우
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

//==============================================================================
// 공통
//------------------------------------------------------------------------------
$dbconfig_file = P1_PATH."/".P1_DBCONFIG_FILE;
if (file_exists($dbconfig_file)) {
    include_once($dbconfig_file);
    include_once(P1_LIB_PATH.'/common.lib.php');    // 공통 라이브러리

    $connect_db = sql_connect(P1_MYSQL_HOST, P1_MYSQL_USER, P1_MYSQL_PASSWORD) or die('MySQL Connect Error!!!');
    $select_db  = sql_select_db(P1_MYSQL_DB, $connect_db) or die('MySQL DB Error!!!');

    $p1['connect_db'] = $connect_db;

    sql_set_charset('utf8mb4', $connect_db);
    if(defined('P1_MYSQL_SET_MODE') && P1_MYSQL_SET_MODE) sql_query("SET SESSION sql_mode = ''");
    if (defined('P1_TIMEZONE')) sql_query(" set time_zone = '".P1_TIMEZONE."'");
}
//==============================================================================


//==============================================================================
// SESSION 설정
//------------------------------------------------------------------------------
@ini_set("session.use_trans_sid", 0);    // PHPSESSID를 자동으로 넘기지 않음
@ini_set("url_rewriter.tags",""); // 링크에 PHPSESSID가 따라다니는것을 무력화함

//session_save_path(P1_SESSION_PATH);

if (isset($SESSION_CACHE_LIMITER))
    @session_cache_limiter($SESSION_CACHE_LIMITER);
else
    @session_cache_limiter("no-cache, must-revalidate");

ini_set("session.cache_expire", 180); // 세션 캐쉬 보관시간 (분)
ini_set("session.gc_maxlifetime", 10800); // session data의 garbage collection 존재 기간을 지정 (초)
ini_set("session.gc_probability", 1); // session.gc_probability는 session.gc_divisor와 연계하여 gc(쓰레기 수거) 루틴의 시작 확률을 관리합니다. 기본값은 1입니다. 자세한 내용은 session.gc_divisor를 참고하십시오.
ini_set("session.gc_divisor", 100); // session.gc_divisor는 session.gc_probability와 결합하여 각 세션 초기화 시에 gc(쓰레기 수거) 프로세스를 시작할 확률을 정의합니다. 확률은 gc_probability/gc_divisor를 사용하여 계산합니다. 즉, 1/100은 각 요청시에 GC 프로세스를 시작할 확률이 1%입니다. session.gc_divisor의 기본값은 100입니다.
ini_set("session.cookie_httponly", 1); // httponly flag
$__playd_https_on = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== '' && $_SERVER['HTTPS'] !== 'off');
ini_set('session.cookie_secure', $__playd_https_on ? '1' : '0');
unset($__playd_https_on);

session_set_cookie_params(0, '/');
ini_set("session.cookie_domain", P1_COOKIE_DOMAIN);

@session_start();
//==============================================================================

//==============================================================================
// 공용 변수
//------------------------------------------------------------------------------
define('P1_HTTP_PAGE_URL',  https_url(P1_PAGE_DIR, false));
define('P1_HTTPS_PAGE_URL', https_url(P1_PAGE_DIR, true));

// 4.00.03 : [보안관련] PHPSESSID 가 틀리면 로그아웃한다.
if (isset($_REQUEST['PHPSESSID']) && $_REQUEST['PHPSESSID'] != session_id())
    goto_url(P1_PAGE_URL.'/logout.php');

// Query String
// $qstr = $_SERVER['QUERY_STRING'];
$qstr = '';

// 검색어구분
if (isset($_REQUEST['search_type'])) {
    $search_type = trim($_REQUEST['search_type']);
    $search_type = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $search_type);
    if ($search_type)
        $qstr .= '&amp;search_type=' . urlencode($search_type);
} else {
    $search_type = '';
}

// 검색어
if (isset($_REQUEST['search_txt'])) {
    $search_txt = trim($_REQUEST['search_txt']);
    if ($search_txt)
        $qstr .= '&amp;search_txt='. urlencode(cut_str($search_txt, 20, ''));
} else {
    $search_txt = '';
}

// 게시일자(시작)
if (isset($_REQUEST['fr_date'])) {
    $fr_date = trim($_REQUEST['fr_date']);
    if ($fr_date)
        $qstr .= '&amp;fr_date=' . urlencode($fr_date);
} else {
    $fr_date = '';
}

// 게시일자(종료)
if (isset($_REQUEST['to_date'])) {
    $to_date = trim($_REQUEST['to_date']);
    if ($to_date)
        $qstr .= '&amp;to_date=' . urlencode($to_date);
} else {
    $to_date = '';
}

/*--------------*
 *  관리자 관리
 *--------------*/
// 권한
if (isset($_REQUEST['auth_tp'])) {
    $auth_tp = (int)$_REQUEST['auth_tp'];
    if ($auth_tp)
        $qstr .= '&amp;auth_tp=' . urlencode($auth_tp);
} else {
    $auth_tp = '';
}

/*------------*
 * 가맹점목록 *
 *------------*/
// 승인상태
if (isset($_REQUEST['service_state'])) {
    $service_state = (int)$_REQUEST['service_state'];
    if ($service_state)
        $qstr .= '&amp;service_state=' . urlencode($service_state);
} else {
    $service_state = '';
}

/*------------*
 *  회원목록  *
 *------------*/
// 성별
if (isset($_REQUEST['sex'])) {
    $sex = trim($_REQUEST['sex']);
    $sex = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $sex);
    if ($sex)
        $qstr .= '&amp;sex=' . urlencode($sex);
} else {
    $sex = '';
}

// 연령대
if (isset($_REQUEST['age_group'])) {
    $age_group = (int)$_REQUEST['age_group'];
    if ($age_group)
        $qstr .= '&amp;age_group=' . urlencode($age_group);
} else {
    $age_group = '';
}

// 직업
if (isset($_REQUEST['job'])) {
    $job = (int)$_REQUEST['job'];
    if ($job)
        $qstr .= '&amp;job=' . urlencode($job);
} else {
    $job = '';
}

// 회원구분
if (isset($_REQUEST['user_type'])) {
    $user_type = (int)$_REQUEST['user_type'];
    if ($user_type)
        $qstr .= '&amp;user_type=' . urlencode($user_type);
} else {
    $user_type = '';
}

// 회원상태
if (isset($_REQUEST['user_state'])) {
    $user_state = (int)$_REQUEST['user_state'];
    if ($user_state)
        $qstr .= '&amp;user_state=' . urlencode($user_state);
} else {
    $user_state = '';
}

// 로그인유형
if (isset($_REQUEST['login_type'])) {
    $login_type = trim($_REQUEST['login_type']);
    $login_type = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $login_type);
    if ($login_type)
        $qstr .= '&amp;login_type=' . urlencode($login_type);
} else {
    $login_type = '';
}

/*--------------*
 * 쿠폰신청관리 *
 *  짤신청관리  *
 *--------------*/
// 신청상태
if (isset($_REQUEST['appl_state'])) {
    $appl_state = (int)$_REQUEST['appl_state'];
    if ($appl_state)
        $qstr .= '&amp;appl_state=' . urlencode($appl_state);
} else {
    $appl_state = '';
}

/*------------*
 *  배너관리  *
 * 게시물관리 *
 *------------*/
// 게시상태
if (isset($_REQUEST['post_state'])) {
    $post_state = (int)$_REQUEST['post_state'];
    if ($post_state)
        $qstr .= '&amp;post_state=' . urlencode($post_state);
} else {
    $post_state = '';
}

/*------------*
 * 게시판설정
 *------------*/
// 게시판유형
if (isset($_REQUEST['type'])) {
    $type = trim($_REQUEST['type']);
    $type = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $type);
    if ($type)
        $qstr .= '&amp;type=' . urlencode($type);
} else {
    $type = '';
}

// 사용여부
if (isset($_REQUEST['use_yn']))  {
    $use_yn = trim($_REQUEST['use_yn']);
    $use_yn = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $use_yn);
    if ($use_yn)
        $qstr .= '&amp;use_yn=' . urlencode($use_yn);
} else {
    $use_yn = '';
}

// 정렬(공통)
if (isset($_REQUEST['sst']))  {
    $sst = trim($_REQUEST['sst']);
    $sst = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $sst);
    if ($sst)
        $qstr .= '&amp;sst=' . urlencode($sst); // search sort (검색 정렬 필드)
} else {
    $sst = '';
}

if (isset($_REQUEST['sod']))  { // search order (검색 오름, 내림차순)
    $sod = preg_match("/^(asc|desc)$/i", $sod) ? $sod : '';
    if ($sod)
        $qstr .= '&amp;sod=' . urlencode($sod);
} else {
    $sod = '';
}
//===================================

if ($_SESSION['ss_m_seq']) { // 로그인중이라면
    $member = get_user($_SESSION['ss_m_seq']);

    // 중지된 회원이면 ss_m_seq 초기화
    if($member['M_USE_YN'] == "0") {
        if($member['out_dttm'] && $member['out_dttm'] <= date("Ymd", P1_SERVER_TIME)) {
            set_session('ss_m_seq', '');
            $member = array();
        }
    }
}

// 게시판 관련
if ($bc_code) {
    $board = sql_fetch(" select * from {$p1['t_board_config_table']} where bc_code = '$bc_code' ");
    if ($board['BC_CODE']) {
        set_cookie("ck_bc_code", $board['BC_CODE'], 86400 * 1);

        if ($board['BC_TYPE'] == "qna") {
            // form data seq 변환
            $a_seq = ($a_seq) ? $a_seq : $seq; # 문의

            // 문의 게시판 : qna
            if (isset($a_seq) && $a_seq)
                $write = get_qna($a_seq);
        } else if ($board['BC_TYPE'] == "post") {
            // form data seq 변환
            $b_seq = ($b_seq) ? $b_seq : $seq; # 일반

            // 일반 게시판 : post
            if (isset($b_seq) && $b_seq)
                $write = get_write($b_seq);
        }
    }
}

// 에디터 선언
if ($board['BC_EDITOR'])
    define('P1_EDITOR_LIB', P1_EDITOR_PATH."/{$board['BC_EDITOR']}/editor.lib.php");
else
    define('P1_EDITOR_LIB', P1_LIB_PATH."/editor.lib.php");

// 회원, 비회원 구분
$is_member = $is_guest = false;

if ($member['M_SEQ']) {
    $is_member = true;
} else {
    $is_guest = true;
}

if(debug_ip()) {
    // print_r2($config);
    // print_r2($_SESSION);
    // print_r2($_POST);
    // print_r2($_GET);
    // print_r2($_SERVER);
}

//==============================================================================
// 메뉴별 권한처리
//------------------------------------------------------------------------------
$txt = trim($_SERVER['REQUEST_URI']);

$intercept_arr = array(
    array("administration", ROLE_ADMIN),    # 관리자관리
    array("board", ROLE_ADMIN),             # 게시판설정관리
    array("test", "test2")
);

foreach($intercept_arr as $k=>$v) {
    $re1 = '.*?';	# Non-greedy match on filler
    $re2 = '('.$intercept_arr[$k][0].')';	# Word 1

    if ($c = preg_match_all ("/".$re1.$re2."/is", $txt, $matches)) {
        $member_roles     = explode(",", $member['M_ROLES']);
        $intercept_roles  = explode(",", $intercept_arr[$k][1]);
        $intersect_result = array_intersect($intercept_roles, $member_roles);

        // 권한 bypass.
        if (count($intersect_result) > 0) {
            ;
        } else {
            // echo 'test';
        }

        // $word1 = $matches[1][0];
        // print_r2($matches);
        // print "($word1) \n";
    }
}
//==============================================================================

ob_start();

// 자바스크립트에서 go(-1) 함수를 쓰면 폼값이 사라질때 해당 폼의 상단에 사용하면
// 캐쉬의 내용을 가져옴. 완전한지는 검증되지 않음
// header('Content-Type: text/html; charset=utf-8');
$gmnow = gmdate('D, d M Y H:i:s') . ' GMT';
header('Expires: 0'); // rfc2616 - Section 14.21
header('Last-Modified: ' . $gmnow);
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache'); // HTTP/1.0

// 웹취약점 보완
header('X-Frame-Options: DENY'); // 'X-Frame-Options
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1;mode=block'); // IE8+

$html_process = new html_process();

// 디렉토리 생성
// $data_path = './'.P1_DATA_DIR;

// $dir_arr = array (
    // $data_path.'/session'
// );

// for ($i=0; $i<count($dir_arr); $i++) {
    // @mkdir($dir_arr[$i], P1_DIR_PERMISSION);
    // @chmod($dir_arr[$i], P1_DIR_PERMISSION);
// }

function get_admin_page_title(){

}

function get_admin_page_active($url, $code=''){
    $array = explode('|', $url);
    foreach($array as $value) {
        if(strpos(basename($_SERVER["PHP_SELF"]), $value) === 0) {
            if($code){
                if(strpos($code, $_REQUEST['bc_code']) !== false){
                    return "active";
                } else {
                    return "";
                }
            }
            return "active";
        }
    }
    return "";
}


function get_check_admin_auth($member){
    global $menu_status;
   
    if ($member['M_AUTH_TP'] == "1") {
        return $menu_status = array("read" => "Y", "write" => "Y", "del" => "Y");
    }
    
    $menus = '';
    $grpstr = '';

    foreach ($menu_status as $key => $value) {
        $chkurl = basename($_SERVER["PHP_SELF"]);
        if($_REQUEST['bc_code']){
            $chkurl .= "?bc_code=".$_REQUEST['bc_code'];
        }
        if($key == $chkurl){
            $grpstr = $value;
            $grp = explode('|', $grpstr);
            if(sizeof($grp) == 2){
                $grpstr = $grp[0];
            }
            break;
        }
    }

    if($grpstr){
        $board = sql_fetch(" select * from T_GROUP_AUTH where G_SEQ = ".$member['G_SEQ']." and G_MENU = '".$grpstr."' limit 1");
    }
  
    //$grp = explode('|', $menu_status[basename($_SERVER["PHP_SELF"])]);
    // if (strlen($grp[0]) == 1) {
    //     $board = sql_fetch(" select * from T_GROUP_AUTH where G_SEQ = ".$member['G_SEQ']." and G_MENU like '".$grp[0]."%' limit 1");
    // } else {
    //     $board = sql_fetch(" select * from T_GROUP_AUTH where G_SEQ = ".$member['G_SEQ']." and G_MENU = '".$grp[0]."' limit 1");
    // }

    if(!$board){
        return $menu_status = array("read" => "N", "write" => "N", "del" => "N");
    } else {
        return $menu_status = array("read" => $board['G_AUTH_READ'], "write" => $board['G_AUTH_WRITE'], "del" => $board['G_AUTH_DEL']);
    }
}

//리포트 types 가져오기
function get_report_types(){
    $stack = array('Trend Delivery', 'VOICE Trend', 'Trend Overview');
    return $stack;
}

//광고 types 가져오기
function get_ad_types(){
    $stack = array('MEDIA', 'SOLUTION', 'CASE STUDY');
    return $stack;
}

function get_portfolio_types(){
    $stack = array('IT/통신',
    '가전/전기',
    '가정/생활',
    '건축/인테리어',
    '결혼/출산/육아',
    '관공서/단체',
    '교육',
    '금융/보험',
    '레저/취미',
    '문화/예술/게임',
    '부동산',
    '산업 자원',
    '식품/음료',
    '여행/교통',
    '의료/건강',
    '의류/패션잡화',
    '화장품/미용',
    '자동차',
    '전문 서비스',
    '종합쇼핑',
    '취업',
    '인쇄/문구/사무기기',
    '기타',
    '테스트 카테고리');
    return $stack;
}


// XSS 관련 태그 제거
// function clean_xss_tags($str)
// {
//     $str = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $str);

//     return $str;
// }


// mode = 1 (기본)
// mode = 2 (숫자만)
function nvl($str, $mode = 1, $out_data = ''){
    $output = strip_tags($str);
    //$output = addslashes($output);
    $output = clean_xss_tags($output);

    if($mode==2){
        if(!is_numeric($str)) $output = $out_data;
    }

    return $str?$output:'';
}


function nvldesc($str){
    $output = strip_tags($str);
    $output = preg_replace("/[\n\r]/", "", $output);
    $output = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", '', $output);
    $output = preg_replace("/(\‘)/i", "", $output);
    $output = preg_replace("/(\’)/i", "", $output);

    $output = clean_xss_tags($output);
    $output = cut_str($output, 200, '');

    return $output;
}

function Encrypt($str, $secret_key='123456789', $secret_iv='#@$%^&*()_+=-')
{
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 32)    ;
    return str_replace("=", "", base64_encode(
    	openssl_encrypt($str, "AES-256-CBC", $key, 0, $iv))
    );
}

function Decrypt($str, $secret_key='123456789', $secret_iv='#@$%^&*()_+=-')
{
   if($str == 'TnJITlFlelI3dGx4WEFUa1RoV3FuUT09') return '';
   
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 32);
    $output = openssl_decrypt( base64_decode($str), "AES-256-CBC", $key, 0, $iv );
    if(!$output) $output = $str;
    return $output ;
}



function insert_mgr_log($ml_mode, $ml_path, $ml_link, $ml_seq='', $ml_id=''){

    $m_seq = get_session('ss_m_seq');
    if($ml_seq) { 
        $m_seq = $ml_seq;
    }
    $m_id = get_session('ss_m_id');
    if($ml_id) { 
        $m_id = $ml_id;
    }
    
    $query = " insert into T_MGR_LOG (ML_MODE, ML_PATH, ML_LINK, ML_IP, M_ID, M_SEQ, ML_REG) values ('".$ml_mode."','".$ml_path."','".$ml_link."','".$_SERVER['REMOTE_ADDR']."','".$m_id."','".$m_seq."', now() ) ";
    sql_query($query);
      
}

function get_code_str($code){
    if($code == 'nsmnw'){
        return '뉴스레터';
    }else if($code == 'report'){
        return '리포트';
    }else if($code == 'nsmexp'){
        return '광고컬럼';
    }else if($code == 'playdportfolio'){
        return '포트폴리오';
    }else if($code == 'playdprivate'){
        return '개인정보처리방침';
    }else if($code == 'ir_notice'){
        return '전자공고';
    }else if($code == 'pr_notice'){
        return '공시정보';
    }else if($code == 'nsmad'){
        return '광고문의';
    }else if($code == 'files'){
        return '파일업로드';
    }

}

function get_admin_menu_active($lnb, $menus){
    global $member;
    if ($member['M_AUTH_TP'] == "1") {
        return '';
    }
    $lnb = $lnb.",";
  
    if(strpos($menus, $lnb) === false ){
        return ' style="display:none" ';
    }
    return '';
}

?>
