<?php
if (!defined('_ADM_')) exit; // 개별 페이지 접근 불가

/*************************************************************************
**
**  일반 함수 모음
**
*************************************************************************/
// 스킨디렉토리를 SELECT 형식으로 얻음
function get_skin_select($skin_gubun, $id, $name, $selected='', $event='') {
    $skins = get_skin_dir($skin_gubun);

    $str = "<select id=\"$id\" name=\"$name\" $event>\n";
    for ($i=0; $i<count($skins); $i++) {
        if ($i == 0) $str .= "<option value=\"\">게시판스킨 선택</option>";

        $text = $skins[$i];

        $str .= option_selected($skins[$i], $selected, $text);
    }
    $str .= "</select>";
    return $str;
}

// 스킨경로를 얻는다
function get_skin_dir($skin, $skin_path=P1_PAGE_PATH) {
    global $p1;

    $result_array = array();

    $dirname = $skin_path.'/'.$skin.'/';
    if(!is_dir($dirname))
        return;

    $handle = opendir($dirname);
    while ($file = readdir($handle)) {
        if($file == '.'||$file == '..') continue;

        if (is_dir($dirname.$file)) $result_array[] = $file;
    }
    closedir($handle);
    sort($result_array);

    return $result_array;
}

//--------------------------------------------
// 이미지 파일 압축
// $source = 이미지 tmp_name
// $destination = 저장경로
// $quality = 0~100
//--------------------------------------------
function compress_image($source, $destination, $quality) {
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') 
        $image = imagecreatefromjpeg($source);
    else if ($info['mime'] == 'image/gif') 
        $image = imagecreatefromgif($source);
    else if ($info['mime'] == 'image/png') 
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);

    return $destination;
}

// 날짜 계산 함수
function passing_time($datetime) {
    $datetime = strtotime($datetime);
    $time_lag = time() - $datetime;

    if($time_lag >= 60 and $time_lag < 3600) {
        $posting_time = floor($time_lag/60)."분 전";
    } else if($time_lag >= 3600 and $time_lag < 86400) {
        $posting_time = floor($time_lag/3600)."시간 전";
    } else {
        // $posting_time = date("Y-m-d H:i:s", $datetime);
        $posting_time = date("Y-m-d", $datetime);
    }

    return $posting_time;
}

//=====================================================
// 정보 삭제
//=====================================================
// 뉴스레터 신청자 삭제
function newsletter_delete($ns_seq)
{
    global $p1;
    
    $sql = " delete
               from {$p1['t_newsletter_table']}
              where ns_seq = '{$ns_seq}' ";
    sql_query($sql);
}


function newsreport_delete($ns_seq)
{
    global $p1;
    
    $sql = " delete
               from T_NEWSREPORT
              where ns_seq = '{$ns_seq}' ";
    sql_query($sql);
}

// 윤리경영제보 삭제
function report_delete($a_seq)
{
    global $p1;
    
    $sql = " delete
               from {$p1['t_report_table']}
              where a_seq = '{$a_seq}' ";
    sql_query($sql);
}

// 인재풀글 삭제 (pool)
function pl_incident_delete($s_seq)
{
    global $p1;
    
    $sql = " delete
               from {$p1['t_pool_table']}
              where s_seq = '{$s_seq}' ";
    sql_query($sql);
}

// 입사지원글 삭제 (mable)
function m_incident_delete($s_seq)
{
    global $p1;
    
    $sql = " delete
               from {$p1['mable_t_support_table']}
              where s_seq = '{$s_seq}' ";
    sql_query($sql);
}

// 입사지원글 삭제 (playd)
function incident_delete($s_seq)
{
    global $p1;
    
    $sql = " delete
               from {$p1['t_support_table']}
              where s_seq = '{$s_seq}' ";
    sql_query($sql);
}

// PDF글 삭제 (pool)
function pdf_delete($s_seq)
{
    global $p1;
    
    $sql = " delete
               from {$p1['t_pds_table']}
              where s_seq = '{$s_seq}' ";
    sql_query($sql);
}


// 게시물 삭제 (문의 : qna)
function qna_delete($a_seq)
{
    global $p1;
    
    $sql = " delete
               from {$p1['t_ask_table']}
              where a_seq = '{$a_seq}' ";
    sql_query($sql);
}


// 게시물 삭제 (일반 : post)
function post_delete($b_seq)
{
    global $p1;
    
    $sql = " delete
               from {$p1['t_board_table']}
              where b_seq = '{$b_seq}' ";
    sql_query($sql);
}

// 게시판 설정 삭제
function board_delete($bc_seq)
{
    global $p1;
    
    $sql = " update {$p1['t_board_config_table']}
                set bc_use_yn = '0'
              where bc_seq = '{$bc_seq}' ";
    sql_query($sql);
}

// 유저 삭제
function user_delete($m_seq)
{
    global $p1;
    
    $sql = " delete
               from {$p1['t_mgr_table']}
              where m_seq = '{$m_seq}' ";
    sql_query($sql);
}

//=====================================================
// 정보 얻기
//=====================================================
// 뉴스레터 신청자 정보를 얻는다.
function get_newsletter($ns_seq, $fields='*')
{
    global $p1;
    return sql_fetch(" select $fields from {$p1['t_newsletter_table']} where ns_seq = TRIM('$ns_seq') ");
}

function get_newsreport($ns_seq, $fields='*')
{
    global $p1;
    return sql_fetch(" select $fields from T_NEWSREPORT where ns_seq = TRIM('$ns_seq') ");
}


// 윤리경영제보 정보를 얻는다.
function get_report($a_seq, $fields='*')
{
    global $p1;
    return sql_fetch(" select $fields from {$p1['t_report_table']} where a_seq = TRIM('$a_seq') ");
}

// 인재풀 정보를 얻는다. (pool)
function get_pl_incident($s_seq, $fields='*')
{
    global $p1;
    return sql_fetch(" select $fields from {$p1['t_pool_table']} where s_seq = TRIM('$s_seq') ");
}

// 입사지원 정보를 얻는다. (mable)
function get_m_incident($s_seq, $fields='*')
{
    global $p1;
    return sql_fetch(" select $fields from {$p1['mable_t_support_table']} where s_seq = TRIM('$s_seq') ");
}

// 입사지원 정보를 얻는다. (playd)
function get_incident($s_seq, $fields='*')
{
    global $p1;
    return sql_fetch(" select $fields from {$p1['t_support_table']} where s_seq = TRIM('$s_seq') ");
}

// PDS게시판 정보를 얻는다. (playd)
function get_pdf($s_seq, $fields='*')
{
    global $p1;
    return sql_fetch(" select $fields from {$p1['t_pds_table']} where s_seq = TRIM('$s_seq') ");
}


// 게시글 정보를 얻는다. (문의 : qna)
function get_qna($a_seq, $fields='*')
{
    global $p1;
    return sql_fetch(" select $fields from {$p1['t_ask_table']} where a_seq = TRIM('$a_seq') ");
}

// 게시글 정보를 얻는다. (일반 : post)
function get_write($b_seq, $fields='*')
{
    global $p1;
    return sql_fetch(" select $fields from {$p1['t_board_table']} where b_seq = TRIM('$b_seq') ");
}

// 게시판설정 정보를 얻는다.
function get_board($bc_seq, $fields='*')
{
    global $p1;
    return sql_fetch(" select $fields from {$p1['t_board_config_table']} where bc_seq = TRIM('$bc_seq') ");
}

// 회원 정보를 얻는다. - m_seq 조회
function get_user($m_seq, $fields='*')
{
    global $p1;
    return sql_fetch(" select $fields from {$p1['t_mgr_table']} where m_seq = TRIM('$m_seq') ");
}

// 회원 정보를 얻는다.
function get_member($m_id, $fields='*')
{
    global $p1;
    return sql_fetch(" select $fields from {$p1['t_mgr_table']} where m_id = TRIM('$m_id') ");
}


/*************************************************************************
**
**  일반 함수 모음
**
*************************************************************************/

// 특정 ip 디버깅
function debug_ip() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $iplist = array(
                "::2",
                "49.254.187.230"
              );
    
    foreach ($iplist as $value) { 
        if (strpos($ip, $value) === 0) return true;
        else continue;
    }
    
    return false;
}

// 현재 페이지 include 된 파일 리스트
function get_include_list() {
    foreach (get_included_files() as $filename) :
        echo $filename."<br>";
    endforeach;
}

// 마이크로 타임을 얻어 계산 형식으로 만듦
function get_microtime()
{
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

// 한페이지에 보여줄 행, 현재페이지, 총페이지수, URL
function get_paging($write_pages, $cur_page, $total_page, $url, $add="")
{
    //$url = preg_replace('#&amp;page=[0-9]*(&amp;page=)$#', '$1', $url);
    $url = preg_replace('#&amp;page=[0-9]*#', '', $url) . '&amp;page=';

    $str = '';
    if ($cur_page > 1) {
        $str .= '<li><a href="'.$url.'1'.$add.'"><<</a></li>'.PHP_EOL;
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($start_page > 1) $str .= '<li><a href="'.$url.($start_page-1).$add.'"><</a></li>'.PHP_EOL;

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<li><a href="'.$url.$k.$add.'">'.$k.'</a></li>'.PHP_EOL;
            else
                $str .= '<li><a href="#" class="on">'.$k.'</a></li>'.PHP_EOL;
        }
    }

    if ($total_page > $end_page) $str .= '<li><a href="'.$url.($end_page+1).$add.'">></a></li>'.PHP_EOL;

    if ($cur_page < $total_page) {
        $str .= '<li><a href="'.$url.$total_page.$add.'">>></a></li>'.PHP_EOL;
    }

    if ($str)
        return "<div class=\"pagination\"><ul>{$str}</ul></div>";
    else
        return "";
}

// 페이징 코드의 <nav><span> 태그 다음에 코드를 삽입
function page_insertbefore($paging_html, $insert_html)
{
    if(!$paging_html)
        $paging_html = '<nav class="pg_wrap"><span class="pg"></span></nav>';

    return preg_replace("/^(<nav[^>]+><span[^>]+>)/", '$1'.$insert_html.PHP_EOL, $paging_html);
}

// 페이징 코드의 </span></nav> 태그 이전에 코드를 삽입
function page_insertafter($paging_html, $insert_html)
{
    if(!$paging_html)
        $paging_html = '<nav class="pg_wrap"><span class="pg"></span></nav>';

    if(preg_match("#".PHP_EOL."</span></nav>#", $paging_html))
        $php_eol = '';
    else
        $php_eol = PHP_EOL;

    return preg_replace("#(</span></nav>)$#", $php_eol.$insert_html.'$1', $paging_html);
}

// 변수 또는 배열의 이름과 값을 얻어냄. print_r() 함수의 변형
function print_r2($var)
{
    ob_start();
    print_r($var);
    $str = ob_get_contents();
    ob_end_clean();
    $str = str_replace(" ", "&nbsp;", $str);
    echo nl2br("<span style='font-family:Tahoma, 굴림; font-size:9pt;'>$str</span>");
}

// 메타태그를 이용한 URL 이동
// header("location:URL") 을 대체
function goto_url($url)
{
    $url = str_replace("&amp;", "&", $url);
    //echo "<script> location.replace('$url'); </script>";

    if (!headers_sent())
        header('Location: '.$url);
    else {
        echo '<script>';
        echo 'location.replace("'.$url.'");';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>';
    }
    exit;
}

// 세션변수 생성
function set_session($session_name, $value)
{
    if (PHP_VERSION < '5.3.0')
        session_register($session_name);
    // PHP 버전별 차이를 없애기 위한 방법
    $$session_name = $_SESSION[$session_name] = $value;
}

// 세션변수값 얻음
function get_session($session_name)
{
    return isset($_SESSION[$session_name]) ? $_SESSION[$session_name] : '';
}

// 쿠키변수 생성
function set_cookie($cookie_name, $value, $expire)
{
    global $p1;

    setcookie(md5($cookie_name), base64_encode($value), P1_SERVER_TIME + $expire, '/', P1_COOKIE_DOMAIN);
}

// 쿠키변수값 얻음
function get_cookie($cookie_name)
{
    $cookie = md5($cookie_name);
    if (array_key_exists($cookie, $_COOKIE))
        return base64_decode($_COOKIE[$cookie]);
    else
        return "";
}

// 경고메세지를 경고창으로
function alert($msg='', $url='', $error=true, $post=false)
{
    global $p1, $config, $member;
    global $is_admin;

    $msg = $msg ? strip_tags($msg, '<br>') : '올바른 방법으로 이용해 주십시오.';

    $header = '';
    if (isset($p1['title'])) {
        $header = $p1['title'];
    }
    include_once(P1_PAGE_PATH.'/alert.php');
    exit;
}

// 경고메세지 출력후 창을 닫음
function alert_close($msg, $error=true)
{
    global $p1;
    
    $msg = strip_tags($msg, '<br>');

    $header = '';
    if (isset($p1['title'])) {
        $header = $p1['title'];
    }
    include_once(P1_PAGE_PATH.'/alert_close.php');
    exit;
}

// confirm 창
function confirm($msg, $url1='', $url2='', $url3='')
{
    global $p1;

    if (!$msg) {
        $msg = '올바른 방법으로 이용해 주십시오.';
        alert($msg);
    }

    if(!trim($url1) || !trim($url2)) {
        $msg = '$url1 과 $url2 를 지정해 주세요.';
        alert($msg);
    }

    if (!$url3) $url3 = clean_xss_tags($_SERVER['HTTP_REFERER']);

    $msg = str_replace("\\n", "<br>", $msg);

    $header = '';
    if (isset($p1['title'])) {
        $header = $p1['title'];
    }
    include_once(P1_PAGE_PATH.'/confirm.php');
    exit;
}

// way.co.kr 의 wayboard 참고
function url_auto_link($str)
{
    global $p1;
    global $config;

    // 140326 유창화님 제안코드로 수정
    // http://sir.kr/pg_lecture/461
    // http://sir.kr/pg_lecture/463
    $attr_nofollow = (function_exists('check_html_link_nofollow') && check_html_link_nofollow('url_auto_link')) ? ' rel="nofollow"' : '';
    $str = str_replace(array("&lt;", "&gt;", "&amp;", "&quot;", "&nbsp;", "&#039;"), array("\t_lt_\t", "\t_gt_\t", "&", "\"", "\t_nbsp_\t", "'"), $str);
    //$str = preg_replace("`(?:(?:(?:href|src)\s*=\s*(?:\"|'|)){0})((http|https|ftp|telnet|news|mms)://[^\"'\s()]+)`", "<A HREF=\"\\1\" TARGET='{$config['cf_link_target']}'>\\1</A>", $str);
    $str = preg_replace("/([^(href=\"?'?)|(src=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[가-힣\xA1-\xFEa-zA-Z0-9\.:&#!=_\?\/~\+%@;\-\|\,\(\)]+)/i", "\\1<A HREF=\"\\2\" TARGET=\"{$config['cf_link_target']}\" $attr_nofollow>\\2</A>", $str);
    $str = preg_replace("/(^|[\"'\s(])(www\.[^\"'\s()]+)/i", "\\1<A HREF=\"http://\\2\" TARGET=\"{$config['cf_link_target']}\" $attr_nofollow>\\2</A>", $str);
    $str = preg_replace("/[0-9a-z_-]+@[a-z0-9._-]{4,}/i", "<a href=\"mailto:\\0\" $attr_nofollow>\\0</a>", $str);
    $str = str_replace(array("\t_nbsp_\t", "\t_lt_\t", "\t_gt_\t", "'"), array("&nbsp;", "&lt;", "&gt;", "&#039;"), $str);

    /*
    // 속도 향상 031011
    $str = preg_replace("/&lt;/", "\t_lt_\t", $str);
    $str = preg_replace("/&gt;/", "\t_gt_\t", $str);
    $str = preg_replace("/&amp;/", "&", $str);
    $str = preg_replace("/&quot;/", "\"", $str);
    $str = preg_replace("/&nbsp;/", "\t_nbsp_\t", $str);
    $str = preg_replace("/([^(http:\/\/)]|\(|^)(www\.[^[:space:]]+)/i", "\\1<A HREF=\"http://\\2\" TARGET='{$config['cf_link_target']}'>\\2</A>", $str);
    //$str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,]+)/i", "\\1<A HREF=\"\\2\" TARGET='$config['cf_link_target']'>\\2</A>", $str);
    // 100825 : () 추가
    // 120315 : CHARSET 에 따라 링크시 글자 잘림 현상이 있어 수정
    $str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[가-힣\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i", "\\1<A HREF=\"\\2\" TARGET='{$config['cf_link_target']}'>\\2</A>", $str);

    // 이메일 정규표현식 수정 061004
    //$str = preg_replace("/(([a-z0-9_]|\-|\.)+@([^[:space:]]*)([[:alnum:]-]))/i", "<a href='mailto:\\1'>\\1</a>", $str);
    $str = preg_replace("/([0-9a-z]([-_\.]?[0-9a-z])*@[0-9a-z]([-_\.]?[0-9a-z])*\.[a-z]{2,4})/i", "<a href='mailto:\\1'>\\1</a>", $str);
    $str = preg_replace("/\t_nbsp_\t/", "&nbsp;" , $str);
    $str = preg_replace("/\t_lt_\t/", "&lt;", $str);
    $str = preg_replace("/\t_gt_\t/", "&gt;", $str);
    */

    return $str;
}

// url에 http:// 를 붙인다
function set_http($url)
{
    if (!trim($url)) return;

    if (!preg_match("/^(http|https|ftp|telnet|news|mms)\:\/\//i", $url))
        $url = "http://" . $url;

    return $url;
}

// 파일의 용량을 구한다.
//function get_filesize($file)
function get_filesize($size)
{
    //$size = @filesize(addslashes($file));
    if ($size >= 1048576) {
        $size = number_format($size/1048576, 1) . "M";
    } else if ($size >= 1024) {
        $size = number_format($size/1024, 1) . "K";
    } else {
        $size = number_format($size, 0) . "byte";
    }
    return $size;
}

// 폴더의 용량 ($dir는 / 없이 넘기세요)
function get_dirsize($dir)
{
    $size = 0;
    $d = dir($dir);
    while ($entry = $d->read()) {
        if ($entry != '.' && $entry != '..') {
            $size += filesize($dir.'/'.$entry);
        }
    }
    $d->close();
    return $size;
}

// 날짜, 조회수의 경우 높은 순서대로 보여져야 하므로 $flag 를 추가
// $flag : asc 낮은 순서 , desc 높은 순서
// 제목별로 컬럼 정렬하는 QUERY STRING
function subject_sort_link($col, $query_string='', $flag='asc')
{
    global $sst, $sod, $search_type, $search_txt, $page;

    $q1 = "sst=$col";
    if ($flag == 'asc')
    {
        $q2 = 'sod=asc';
        if ($sst == $col)
        {
            if ($sod == 'asc')
            {
                $q2 = 'sod=desc';
            }
        }
    }
    else
    {
        $q2 = 'sod=desc';
        if ($sst == $col)
        {
            if ($sod == 'desc')
            {
                $q2 = 'sod=asc';
            }
        }
    }

    $arr_query = array();
    $arr_query[] = $query_string;
    $arr_query[] = $q1;
    $arr_query[] = $q2;
    $arr_query[] = 'search_type='.$search_type;
    $arr_query[] = 'search_txt='.$search_txt;
    $arr_query[] = 'page='.$page;
    $qstr = implode("&amp;", $arr_query);

    return "<a href=\"{$_SERVER['SCRIPT_NAME']}?{$qstr}\">";
}

// 관리자 정보를 얻음
function get_admin($admin='super', $fields='*')
{
    global $config, $group, $board;
    global $p1;

    $is = false;
    if ($admin == 'board') {
        $mb = sql_fetch("select {$fields} from {$p1['member_table']} where mb_id in ('{$board['bo_admin']}') limit 1 ");
        $is = true;
    }

    if (($is && !$mb['mb_id']) || $admin == 'group') {
        $mb = sql_fetch("select {$fields} from {$p1['member_table']} where mb_id in ('{$group['gr_admin']}') limit 1 ");
        $is = true;
    }

    if (($is && !$mb['mb_id']) || $admin == 'super') {
        $mb = sql_fetch("select {$fields} from {$p1['member_table']} where mb_id in ('{$config['cf_admin']}') limit 1 ");
    }

    return $mb;
}

// 권한별 체크
function auth_check_no($auths="", $dep_no="1") {
    global $member;

    $auth_arr = explode(",", $auths);                    // 요청 권한
    $dep1_arr = explode(",", $member['M_DEP1_MENU']);    // 일반 권한 대분류
    $dep2_arr = explode(",", $member['M_DEP2_MENU']);    // 일반 권한 소분류
    
    foreach($auth_arr as $k=>$v) {
        if (in_array($v, ${"dep".$dep_no."_arr"})) {
            return true;
        }
    }
    
    return false;
}

function radio_selected($value, $selected, $text='', $name='', $class='')
{                        
    if (!$text) $text = $value;
    if ($value == $selected)
        return "<input type=\"radio\" class=\"$class\" name=\"$name\" value=\"$value\" checked=\"checked\">\n<label for=\"$name\">$text</label>";
    else
        return "<input type=\"radio\" class=\"$class\" name=\"$name\" value=\"$value\">\n<label for=\"$name\">$text</label>";
}

function checkbox_selected($value, $selected, $text='', $name='', $class='')
{                        
    if (!$text) $text = $value;
    if ($value == $selected)
        return "<input type=\"checkbox\" class=\"$class\" name=\"$name\" value=\"$value\" checked=\"checked\">\n<label for=\"$name\">$text</label>";
    else
        return "<input type=\"checkbox\" class=\"$class\" name=\"$name\" value=\"$value\">\n<label for=\"$name\">$text</label>";
}

function option_selected($value, $selected, $text='')
{
    if (!$text) $text = $value;
    if ($value == $selected)
        return "<option value=\"$value\" selected=\"selected\">$text</option>\n";
    else
        return "<option value=\"$value\">$text</option>\n";
}

// '예', '아니오'를 SELECT 형식으로 얻음
function get_yn_select($name, $selected='1', $event='')
{
    $str = "<select name=\"$name\" $event>\n";
    if ($selected) {
        $str .= "<option value=\"1\" selected>예</option>\n";
        $str .= "<option value=\"0\">아니오</option>\n";
    } else {
        $str .= "<option value=\"1\">예</option>\n";
        $str .= "<option value=\"0\" selected>아니오</option>\n";
    }
    $str .= "</select>";
    return $str;
}

function cut_str($str, $len, $suffix="…")
{
    $arr_str = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    $str_len = count($arr_str);

    if ($str_len >= $len) {
        $slice_str = array_slice($arr_str, 0, $len);
        $str = join("", $slice_str);

        return $str . ($str_len > $len ? $suffix : '');
    } else {
        $str = join("", $arr_str);
        return $str;
    }
}

// TEXT 형식으로 변환
function get_text($str, $html=0, $restore=false)
{
    $source[] = "<";
    $target[] = "&lt;";
    $source[] = ">";
    $target[] = "&gt;";
    $source[] = "\"";
    $target[] = "&#034;";
    $source[] = "\'";
    $target[] = "&#039;";

    if($restore)
        $str = str_replace($target, $source, $str);

    // 3.31
    // TEXT 출력일 경우 &amp; &nbsp; 등의 코드를 정상으로 출력해 주기 위함
    if ($html == 0) {
        $str = html_symbol($str);
    }

    if ($html) {
        $source[] = "\n";
        $target[] = "<br/>";
    }

    return str_replace($source, $target, $str);
}

/*
// HTML 특수문자 변환 htmlspecialchars
function hsc($str)
{
    $trans = array("\"" => "&#034;", "'" => "&#039;", "<"=>"&#060;", ">"=>"&#062;");
    $str = strtr($str, $trans);
    return $str;
}
*/

// 3.31
// HTML SYMBOL 변환
// &nbsp; &amp; &middot; 등을 정상으로 출력
function html_symbol($str)
{
    return preg_replace("/\&([a-z0-9]{1,20}|\#[0-9]{0,3});/i", "&#038;\\1;", $str);
}

/*************************************************************************
**
**  SQL 관련 함수 모음
**
*************************************************************************/

// DB 연결
function sql_connect($host, $user, $pass, $db=P1_MYSQL_DB)
{
    global $p1;

    if(function_exists('mysqli_connect') && P1_MYSQLI_USE) {
        $port = (defined('P1_MYSQL_PORT') && P1_MYSQL_PORT > 0) ? (int) P1_MYSQL_PORT : 3306;
        $link = mysqli_connect($host, $user, $pass, $db, $port);

        // 연결 오류 발생 시 스크립트 종료
        if (mysqli_connect_errno()) {
            die('Connect Error: '.mysqli_connect_error());
        }
    } else {
        $link = mysql_connect($host, $user, $pass);
    }

    return $link;
}

// DB 선택
function sql_select_db($db, $connect)
{
    global $p1;

    if(function_exists('mysqli_select_db') && P1_MYSQLI_USE)
        return @mysqli_select_db($connect, $db);
    else
        return @mysql_select_db($db, $connect);
}

function sql_set_charset($charset, $link=null)
{
    global $p1;

    if(!$link)
        $link = $p1['connect_db'];

    if(function_exists('mysqli_set_charset') && P1_MYSQLI_USE)
        mysqli_set_charset($link, $charset);
    else
        mysql_query(" set names {$charset} ", $link);
}

// mysqli_query 와 mysqli_error 를 한꺼번에 처리
// mysql connect resource 지정
function sql_query($sql, $error=P1_DISPLAY_SQL_ERROR, $link=null, $not_preg=null)
{
    global $p1;

    if(!$link)
        $link = $p1['connect_db'];

    // Blind SQL Injection 취약점 해결
    $sql = trim($sql);
    
    // 정규식 이용여부 : 어쩔수 없이 union 사용할 때..
    if($not_preg == null) {
        // union의 사용을 허락하지 않습니다.
        // $sql = preg_replace("#^select.*from.*union.*#i", "select 1", $sql);
        $sql = preg_replace("#^select.*from.*[\s\(]+union[\s\)]+.*#i ", "select 1", $sql);
        // `information_schema` DB로의 접근을 허락하지 않습니다.
        $sql = preg_replace("#^select.*from.*where.*`?information_schema`?.*#i", "select 1", $sql);
    }
    
    if(function_exists('mysqli_query') && P1_MYSQLI_USE) {
        if ($error) {
            $result = @mysqli_query($link, $sql) or die("<p>$sql<p>" . mysqli_errno($link) . " : " .  mysqli_error($link) . "<p>error file : {$_SERVER['SCRIPT_NAME']}");
        } else {
            $result = @mysqli_query($link, $sql);
        }
    } else {
        if ($error) {
            $result = @mysql_query($sql, $link) or die("<p>$sql<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : {$_SERVER['SCRIPT_NAME']}");
        } else {
            $result = @mysql_query($sql, $link);
        }
    }

    return $result;
}

// 쿼리를 실행한 후 결과값에서 한행을 얻는다.
function sql_fetch($sql, $error=P1_DISPLAY_SQL_ERROR, $link=null)
{
    global $p1;

    if(!$link)
        $link = $p1['connect_db'];

    $result = sql_query($sql, $error, $link);
    //$row = @sql_fetch_array($result) or die("<p>$sql<p>" . mysqli_errno() . " : " .  mysqli_error() . "<p>error file : $_SERVER['SCRIPT_NAME']");
    $row = sql_fetch_array($result);
    return $row;
}

// mysqli_fetch_assoc 이 컬럼명을 소문자로 주는 환경(MariaDB 등)에서도 기대 키로 접근
function playd_row_col(mixed $row, string $name): mixed
{
    if (!is_array($row)) {
        return null;
    }
    if (array_key_exists($name, $row)) {
        return $row[$name];
    }
    $want = strtolower($name);
    foreach ($row as $k => $v) {
        if (is_string($k) && strtolower($k) === $want) {
            return $v;
        }
    }

    return null;
}

// 결과값에서 한행 연관배열(이름으로)로 얻는다.
function sql_fetch_array($result)
{
    if(function_exists('mysqli_fetch_assoc') && P1_MYSQLI_USE)
        $row = @mysqli_fetch_assoc($result);
    else
        $row = @mysql_fetch_assoc($result);

    return $row;
}

// $result에 대한 메모리(memory)에 있는 내용을 모두 제거한다.
// sql_free_result()는 결과로부터 얻은 질의 값이 커서 많은 메모리를 사용할 염려가 있을 때 사용된다.
// 단, 결과 값은 스크립트(script) 실행부가 종료되면서 메모리에서 자동적으로 지워진다.
function sql_free_result($result)
{
    if(function_exists('mysqli_free_result') && P1_MYSQLI_USE)
        return mysqli_free_result($result);
    else
        return mysql_free_result($result);
}

function sql_password($value)
{
    // mysql 4.0x 이하 버전에서는 password() 함수의 결과가 16bytes
    // mysql 4.1x 이상 버전에서는 password() 함수의 결과가 41bytes
    $row = sql_fetch(" select password('".sql_escape_string($value)."') as pass ");

    return playd_row_col($row, 'pass');
}

function sql_insert_id($link=null)
{
    global $p1;

    if(!$link)
        $link = $p1['connect_db'];

    if(function_exists('mysqli_insert_id') && P1_MYSQLI_USE)
        return mysqli_insert_id($link);
    else
        return mysql_insert_id($link);
}

function sql_num_rows($result)
{
    if(function_exists('mysqli_num_rows') && P1_MYSQLI_USE)
        return mysqli_num_rows($result);
    else
        return mysql_num_rows($result);
}

function sql_field_names($table, $link=null)
{
    global $p1;

    if(!$link)
        $link = $p1['connect_db'];

    $columns = array();

    $sql = " select * from `$table` limit 1 ";
    $result = sql_query($sql, $link);

    if(function_exists('mysqli_fetch_field') && P1_MYSQLI_USE) {
        while($field = mysqli_fetch_field($result)) {
            $columns[] = $field->name;
        }
    } else {
        $i = 0;
        $cnt = mysql_num_fields($result);
        while($i < $cnt) {
            $field = mysql_fetch_field($result, $i);
            $columns[] = $field->name;
            $i++;
        }
    }

    return $columns;
}

function sql_error_info($link=null)
{
    global $p1;

    if(!$link)
        $link = $p1['connect_db'];

    if(function_exists('mysqli_error') && P1_MYSQLI_USE) {
        return mysqli_errno($link) . ' : ' . mysqli_error($link);
    } else {
        return mysql_errno($link) . ' : ' . mysql_error($link);
    }
}

// PHPMyAdmin 참고
function get_table_define($table, $crlf="\n")
{
    global $p1;

    // For MySQL < 3.23.20
    $schema_create .= 'CREATE TABLE ' . $table . ' (' . $crlf;

    $sql = 'SHOW FIELDS FROM ' . $table;
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result))
    {
        $schema_create .= '    ' . $row['Field'] . ' ' . $row['Type'];
        if (isset($row['Default']) && $row['Default'] != '')
        {
            $schema_create .= ' DEFAULT \'' . $row['Default'] . '\'';
        }
        if ($row['Null'] != 'YES')
        {
            $schema_create .= ' NOT NULL';
        }
        if ($row['Extra'] != '')
        {
            $schema_create .= ' ' . $row['Extra'];
        }
        $schema_create     .= ',' . $crlf;
    } // end while
    sql_free_result($result);

    $schema_create = preg_replace('/,' . $crlf . '$/', '', $schema_create);

    $sql = 'SHOW KEYS FROM ' . $table;
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result))
    {
        $kname    = $row['Key_name'];
        $comment  = (isset($row['Comment'])) ? $row['Comment'] : '';
        $sub_part = (isset($row['Sub_part'])) ? $row['Sub_part'] : '';

        if ($kname != 'PRIMARY' && $row['Non_unique'] == 0) {
            $kname = "UNIQUE|$kname";
        }
        if ($comment == 'FULLTEXT') {
            $kname = 'FULLTEXT|$kname';
        }
        if (!isset($index[$kname])) {
            $index[$kname] = array();
        }
        if ($sub_part > 1) {
            $index[$kname][] = $row['Column_name'] . '(' . $sub_part . ')';
        } else {
            $index[$kname][] = $row['Column_name'];
        }
    } // end while
    sql_free_result($result);

    while (list($x, $columns) = @each($index)) {
        $schema_create     .= ',' . $crlf;
        if ($x == 'PRIMARY') {
            $schema_create .= '    PRIMARY KEY (';
        } else if (substr($x, 0, 6) == 'UNIQUE') {
            $schema_create .= '    UNIQUE ' . substr($x, 7) . ' (';
        } else if (substr($x, 0, 8) == 'FULLTEXT') {
            $schema_create .= '    FULLTEXT ' . substr($x, 9) . ' (';
        } else {
            $schema_create .= '    KEY ' . $x . ' (';
        }
        $schema_create     .= implode($columns, ', ') . ')';
    } // end while

    $schema_create .= $crlf . ') ENGINE=MyISAM DEFAULT CHARSET=utf8';

    return $schema_create;
} // end of the 'PMA_getTableDef()' function

// 리퍼러 체크
function referer_check($url='')
{
    /*
    // 제대로 체크를 하지 못하여 주석 처리함
    global $p1;

    if (!$url)
        $url = P1_URL;

    if (!preg_match("/^http['s']?:\/\/".$_SERVER['HTTP_HOST']."/", $_SERVER['HTTP_REFERER']))
        alert("제대로 된 접근이 아닌것 같습니다.", $url);
    */
}

// 한글 요일
function get_yoil($date, $full=0)
{
    $arr_yoil = array ('일', '월', '화', '수', '목', '금', '토');

    $yoil = date("w", strtotime($date));
    $str = $arr_yoil[$yoil];
    if ($full) {
        $str .= '요일';
    }
    return $str;
}

// 날짜를 select 박스 형식으로 얻는다
function date_select($date, $name='')
{
    global $p1;

    $s = '';
    if (substr($date, 0, 4) == "0000") {
        $date = P1_TIME_YMDHIS;
    }
    preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $date, $m);

    // 년
    $s .= "<select name='{$name}_y'>";
    for ($i=$m['0']-3; $i<=$m['0']+3; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['0']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>년 \n";

    // 월
    $s .= "<select name='{$name}_m'>";
    for ($i=1; $i<=12; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['2']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>월 \n";

    // 일
    $s .= "<select name='{$name}_d'>";
    for ($i=1; $i<=31; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['3']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>일 \n";

    return $s;
}

// 날짜를 select 박스 형식으로 얻는다
function date_ym_select($date, $name='')
{
    global $p1;

    $s = '';
    if (substr($date, 0, 4) == "0000") {
        $date = P1_TIME_YMDHIS;
    }
    preg_match("/([0-9]{4})-([0-9]{2})/", $date, $m);

    // 년
    $s .= "<select name='{$name}_y'>";
    for ($i=$m['0']-3; $i<=$m['0']+3; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['0']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select> 년 \n";

    // 월
    $s .= "<select name='{$name}_m'>";
    for ($i=1; $i<=12; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['2']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select> 월 \n";

    return $s;
}


// 시간을 select 박스 형식으로 얻는다
// 1.04.00
// 경매에 시간 설정이 가능하게 되면서 추가함
function time_select($time, $name="")
{
    preg_match("/([0-9]{2}):([0-9]{2}):([0-9]{2})/", $time, $m);

    // 시
    $s .= "<select name='{$name}_h'>";
    for ($i=0; $i<=23; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['0']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>시 \n";

    // 분
    $s .= "<select name='{$name}_i'>";
    for ($i=0; $i<=59; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['2']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>분 \n";

    // 초
    $s .= "<select name='{$name}_s'>";
    for ($i=0; $i<=59; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['3']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>초 \n";

    return $s;
}

// 문자열이 한글, 영문, 숫자, 특수문자로 구성되어 있는지 검사
function check_string($str, $options)
{
    global $p1;

    $s = '';
    for($i=0;$i<strlen($str);$i++) {
        $c = $str[$i];
        $oc = ord($c);

        // 한글
        if ($oc >= 0xA0 && $oc <= 0xFF) {
            if ($options & P1_HANGUL) {
                $s .= $c . $str[$i+1] . $str[$i+2];
            }
            $i+=2;
        }
        // 숫자
        else if ($oc >= 0x30 && $oc <= 0x39) {
            if ($options & P1_NUMERIC) {
                $s .= $c;
            }
        }
        // 영대문자
        else if ($oc >= 0x41 && $oc <= 0x5A) {
            if (($options & P1_ALPHABETIC) || ($options & P1_ALPHAUPPER)) {
                $s .= $c;
            }
        }
        // 영소문자
        else if ($oc >= 0x61 && $oc <= 0x7A) {
            if (($options & P1_ALPHABETIC) || ($options & P1_ALPHALOWER)) {
                $s .= $c;
            }
        }
        // 공백
        else if ($oc == 0x20) {
            if ($options & P1_SPACE) {
                $s .= $c;
            }
        }
        else {
            if ($options & P1_SPECIAL) {
                $s .= $c;
            }
        }
    }

    // 넘어온 값과 비교하여 같으면 참, 틀리면 거짓
    return ($str == $s);
}

// 한글(2bytes)에서 마지막 글자가 1byte로 끝나는 경우
// 출력시 깨지는 현상이 발생하므로 마지막 완전하지 않은 글자(1byte)를 하나 없앰
function cut_hangul_last($hangul)
{
    global $p1;

    // 한글이 반쪽나면 ?로 표시되는 현상을 막음
    $cnt = 0;
    for($i=0;$i<strlen($hangul);$i++) {
        // 한글만 센다
        if (ord($hangul[$i]) >= 0xA0) {
            $cnt++;
        }
    }

    return $hangul;
}

// 테이블에서 INDEX(키) 사용여부 검사
function explain($sql)
{
    if (preg_match("/^(select)/i", trim($sql))) {
        $q = "explain $sql";
        echo $q;
        $row = sql_fetch($q);
        if (!$row['key']) $row['key'] = "NULL";
        echo " <font color=blue>(type={$row['type']} , key={$row['key']})</font>";
    }
}

// 악성태그 변환
function bad_tag_convert($code)
{
    global $view;
    global $member, $is_admin;

    if ($is_admin && $member['mb_id'] != $view['mb_id']) {
        //$code = preg_replace_callback("#(\<(embed|object)[^\>]*)\>(\<\/(embed|object)\>)?#i",
        // embed 또는 object 태그를 막지 않는 경우 필터링이 되도록 수정
        $code = preg_replace_callback("#(\<(embed|object)[^\>]*)\>?(\<\/(embed|object)\>)?#i",
                    create_function('$matches', 'return "<div class=\"embedx\">보안문제로 인하여 관리자 아이디로는 embed 또는 object 태그를 볼 수 없습니다. 확인하시려면 관리권한이 없는 다른 아이디로 접속하세요.</div>";'),
                    $code);
    }

    return preg_replace("/\<([\/]?)(script|iframe|form)([^\>]*)\>?/i", "&lt;$1$2$3&gt;", $code);
}

// 토큰 생성
function _token()
{
    return md5(uniqid(rand(), true));
}

// 불법접근을 막도록 토큰을 생성하면서 토큰값을 리턴
function get_admin_token()
{
    $token = md5(uniqid(rand(), true));
    set_session('ss_admin_token', $token);

    return $token;
}

// POST로 넘어온 토큰과 세션에 저장된 토큰 비교
function check_admin_token()
{
    $token = get_session('ss_admin_token');
    set_session('ss_admin_token', '');

    if(!$token || !$_REQUEST['token'] || $token != $_REQUEST['token'])
        alert('올바른 방법으로 이용해 주십시오.', P1_URL);

    return true;
}

// 문자열에 utf8 문자가 들어 있는지 검사하는 함수
// 코드 : http://in2.php.net/manual/en/function.mb-check-encoding.php#95289
function is_utf8($str)
{
    $len = strlen($str);
    for($i = 0; $i < $len; $i++) {
        $c = ord($str[$i]);
        if ($c > 128) {
            if (($c > 247)) return false;
            elseif ($c > 239) $bytes = 4;
            elseif ($c > 223) $bytes = 3;
            elseif ($c > 191) $bytes = 2;
            else return false;
            if (($i + $bytes) > $len) return false;
            while ($bytes > 1) {
                $i++;
                $b = ord($str[$i]);
                if ($b < 128 || $b > 191) return false;
                $bytes--;
            }
        }
    }
    return true;
}

// UTF-8 문자열 자르기
// 출처 : https://www.google.co.kr/search?q=utf8_strcut&aq=f&oq=utf8_strcut&aqs=chrome.0.57j0l3.826j0&sourceid=chrome&ie=UTF-8
function utf8_strcut( $str, $size, $suffix='...' )
{
        $substr = substr( $str, 0, $size * 2 );
        $multi_size = preg_match_all( '/[\x80-\xff]/', $substr, $multi_chars );

        if ( $multi_size > 0 )
            $size = $size + intval( $multi_size / 3 ) - 1;

        if ( strlen( $str ) > $size ) {
            $str = substr( $str, 0, $size );
            $str = preg_replace( '/(([\x80-\xff]{3})*?)([\x80-\xff]{0,2})$/', '$1', $str );
            $str .= $suffix;
        }

        return $str;
}

/*
-----------------------------------------------------------
    Charset 을 변환하는 함수
-----------------------------------------------------------
iconv 함수가 있으면 iconv 로 변환하고
없으면 mb_convert_encoding 함수를 사용한다.
둘다 없으면 사용할 수 없다.
*/
function convert_charset($from_charset, $to_charset, $str)
{

    if( function_exists('iconv') )
        return iconv($from_charset, $to_charset, $str);
    elseif( function_exists('mb_convert_encoding') )
        return mb_convert_encoding($str, $to_charset, $from_charset);
    else
        die("Not found 'iconv' or 'mbstring' library in server.");
}

// mysqli_real_escape_string 의 alias 기능을 한다.
function sql_real_escape_string($str, $link=null)
{
    global $p1;

    if(!$link)
        $link = $p1['connect_db'];
    
    if(function_exists('mysqli_connect') && P1_MYSQLI_USE) {
        return mysqli_real_escape_string($link, $str);
    }

    return mysql_real_escape_string($str, $link);
}

function escape_trim($field)
{
    $str = call_user_func(P1_ESCAPE_FUNCTION, $field);
    return $str;
}

// $_POST 형식에서 checkbox 엘리먼트의 checked 속성에서 checked 가 되어 넘어 왔는지를 검사
function is_checked($field)
{
    return !empty($_POST[$field]);
}

function abs_ip2long($ip='')
{
    $ip = $ip ? $ip : $_SERVER['REMOTE_ADDR'];
    return abs(ip2long($ip));
}

function get_selected($field, $value)
{
    if(is_int($value)) {
        return ((int) $field===$value) ? ' selected="selected"' : '';
    }
    
    return ($field===$value) ? ' selected="selected"' : '';
}

function get_checked($field, $value)
{
    if(is_int($value)) {
        return ((int) $field===$value) ? ' checked="checked"' : '';
    }
    
    return ($field==$value) ? ' checked="checked"' : '';
}

function is_mobile()
{
    return preg_match('/'.P1_MOBILE_AGENT.'/i', $_SERVER['HTTP_USER_AGENT']);
}

/*******************************************************************************
    유일한 키를 얻는다.

    결과 :

        년월일시분초00 ~ 년월일시분초99
        년(4) 월(2) 일(2) 시(2) 분(2) 초(2) 100분의1초(2)
        총 16자리이며 년도는 2자리로 끊어서 사용해도 됩니다.
        예) 2008062611570199 또는 08062611570199 (2100년까지만 유일키)

    사용하는 곳 :
    1. 게시판 글쓰기시 미리 유일키를 얻어 파일 업로드 필드에 넣는다.
    2. 주문번호 생성시에 사용한다.
    3. 기타 유일키가 필요한 곳에서 사용한다.
*******************************************************************************/
// 기존의 get_unique_id() 함수를 사용하지 않고 get_uniqid() 를 사용한다.
function get_uniqid()
{
    global $p1;

    sql_query(" LOCK TABLE {$p1['uniqid_table']} WRITE ");
    while (1) {
        // 년월일시분초에 100분의 1초 두자리를 추가함 (1/100 초 앞에 자리가 모자르면 0으로 채움)
        $key = date('YmdHis', time()) . str_pad((int)(microtime()*100), 2, "0", STR_PAD_LEFT);
        
        $result = sql_query(" insert into {$p1['uniqid_table']} set uq_id = '$key', uq_ip = '{$_SERVER['REMOTE_ADDR']}' ", false);
        if ($result) break; // 쿼리가 정상이면 빠진다.

        // insert 하지 못했으면 일정시간 쉰다음 다시 유일키를 만든다.
        usleep(10000); // 100분의 1초를 쉰다
    }
    sql_query(" UNLOCK TABLES ");

    return $key;
}

// CHARSET 변경 : euc-kr -> utf-8
function iconv_utf8($str)
{
    return iconv('euc-kr', 'utf-8', $str);
}

// CHARSET 변경 : utf-8 -> euc-kr
function iconv_euckr($str)
{
    return iconv('utf-8', 'euc-kr', $str);
}

// PC 또는 모바일 사용인지를 검사
function check_device($device)
{
    global $is_admin;

    if ($is_admin) return;

    if ($device=='pc' && P1_IS_MOBILE) {
        alert('PC 전용 게시판입니다.', P1_URL);
    } else if ($device=='mobile' && !P1_IS_MOBILE) {
        alert('모바일 전용 게시판입니다.', P1_URL);
    }
}

// file_put_contents 는 PHP5 전용 함수이므로 PHP4 하위버전에서 사용하기 위함
// http://www.phpied.com/file_get_contents-for-php4/
if (!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data) {
        $f = @fopen($filename, 'w');
        if (!$f) {
            return false;
        } else {
            $bytes = fwrite($f, $data);
            fclose($f);
            return $bytes;
        }
    }
}

// HTML 마지막 처리
function html_end()
{
    global $html_process;

    return $html_process->run();
}

function add_stylesheet($stylesheet, $order=0)
{
    global $html_process;

    if(trim($stylesheet))
        $html_process->merge_stylesheet($stylesheet, $order);
}

function add_javascript($javascript, $order=0)
{
    global $html_process;

    if(trim($javascript))
        $html_process->merge_javascript($javascript, $order);
}

class html_process {
    protected $css = array();
    protected $js  = array();

    function merge_stylesheet($stylesheet, $order)
    {
        $links = $this->css;
        $is_merge = true;

        foreach($links as $link) {
            if($link[1] == $stylesheet) {
                $is_merge = false;
                break;
            }
        }

        if($is_merge)
            $this->css[] = array($order, $stylesheet);
    }

    function merge_javascript($javascript, $order)
    {
        $scripts = $this->js;
        $is_merge = true;

        foreach($scripts as $script) {
            if($script[1] == $javascript) {
                $is_merge = false;
                break;
            }
        }

        if($is_merge)
            $this->js[] = array($order, $javascript);
    }

    function run()
    {
        $buffer = ob_get_contents();
        ob_end_clean();

        $stylesheet = '';
        $links = $this->css;

        if(!empty($links)) {
            foreach ($links as $key => $row) {
                $order[$key] = $row[0];
                $index[$key] = $key;
                $style[$key] = $row[1];
            }

            array_multisort($order, SORT_ASC, $index, SORT_ASC, $links);

            foreach($links as $link) {
                if(!trim($link[1]))
                    continue;

                $link[1] = preg_replace('#\.css([\'\"]?>)$#i', '.css?ver='.P1_CSS_VER.'$1', $link[1]);

                $stylesheet .= PHP_EOL.$link[1];
            }
        }

        $javascript = '';
        $scripts = $this->js;
        $php_eol = '';

        unset($order);
        unset($index);

        if(!empty($scripts)) {
            foreach ($scripts as $key => $row) {
                $order[$key] = $row[0];
                $index[$key] = $key;
                $script[$key] = $row[1];
            }

            array_multisort($order, SORT_ASC, $index, SORT_ASC, $scripts);

            foreach($scripts as $js) {
                if(!trim($js[1]))
                    continue;

                $js[1] = preg_replace('#\.js([\'\"]?>)$#i', '.js?ver='.P1_JS_VER.'$1', $js[1]);

                $javascript .= $php_eol.$js[1];
                $php_eol = PHP_EOL;
            }
        }

        /*
        </title>
        <link rel="stylesheet" href="default.css">
        밑으로 스킨의 스타일시트가 위치하도록 하게 한다.
        */
        $buffer = preg_replace('#(</title>[^<]*<link[^>]+>)#', "$1$stylesheet", $buffer);

        /*
        </head>
        <body>
        전에 스킨의 자바스크립트가 위치하도록 하게 한다.
        */
        $nl = '';
        if($javascript)
            $nl = "\n";
        $buffer = preg_replace('#(</head>[^<]*<body[^>]*>)#', "$javascript{$nl}$1", $buffer);

        return $buffer;
    }
}

// 휴대폰번호의 숫자만 취한 후 중간에 하이픈(-)을 넣는다.
function hyphen_hp_number($hp)
{
    $hp = preg_replace("/[^0-9]/", "", $hp);
    return preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $hp);
}

// 로그인 후 이동할 URL
function login_url($url='', $is_adm='')
{
    if (!$url && !$is_adm) $url = P1_URL;
    if ($is_adm) $url = P1_ADMIN_URL;

    return urlencode(clean_xss_tags(urldecode($url)));
}

// $dir 을 포함하여 https 또는 http 주소를 반환한다.
function https_url($dir, $https=true)
{
    if ($https) {
        if (P1_HTTPS_DOMAIN) {
            $url = P1_HTTPS_DOMAIN.'/'.$dir;
        } else {
            $url = P1_URL.'/'.$dir;
        }
    } else {
        if (P1_DOMAIN) {
            $url = P1_DOMAIN.'/'.$dir;
        } else {
            $url = P1_URL.'/'.$dir;
        }
    }

    return $url;
}

// goo.gl 짧은주소 만들기
function googl_short_url($longUrl)
{
    global $config;

    // Get API key from : http://code.google.com/apis/console/
    // URL Shortener API ON
    $apiKey = $config['cf_googl_shorturl_apikey'];

    $postData = array('longUrl' => $longUrl);
    $jsonData = json_encode($postData);

    $curlObj = curl_init();

    curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey);
    curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curlObj, CURLOPT_HEADER, 0);
    curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
    curl_setopt($curlObj, CURLOPT_POST, 1);
    curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

    $response = curl_exec($curlObj);

    //change the response json string to object
    $json = json_decode($response);

    curl_close($curlObj);

    return $json->id;
}

// get_sock 함수 대체
if (!function_exists("get_sock")) {
    function get_sock($url)
    {
        // host 와 uri 를 분리
        //if (ereg("http://([a-zA-Z0-9_\-\.]+)([^<]*)", $url, $res))
        if (preg_match("/http:\/\/([a-zA-Z0-9_\-\.]+)([^<]*)/", $url, $res))
        {
            $host = $res[1];
            $get  = $res[2];
        }

        // 80번 포트로 소캣접속 시도
        $fp = fsockopen ($host, 80, $errno, $errstr, 30);
        if (!$fp)
        {
            //die("$errstr ($errno)\n");

            echo "$errstr ($errno)\n";
            return null;
        }
        else
        {
            fputs($fp, "GET $get HTTP/1.0\r\n");
            fputs($fp, "Host: $host\r\n");
            fputs($fp, "\r\n");

            // header 와 content 를 분리한다.
            while (trim($buffer = fgets($fp,1024)) != "")
            {
                $header .= $buffer;
            }
            while (!feof($fp))
            {
                $buffer .= fgets($fp,1024);
            }
        }
        fclose($fp);

        // content 만 return 한다.
        return $buffer;
    }
}

// 주소출력
function print_address($addr1, $addr2, $addr3, $addr4)
{
    $address = get_text(trim($addr1));
    $addr2   = get_text(trim($addr2));
    $addr3   = get_text(trim($addr3));

    if($addr4 == 'N') {
        if($addr2)
            $address .= ' '.$addr2;
    } else {
        if($addr2)
            $address .= ', '.$addr2;
    }

    if($addr3)
        $address .= ' '.$addr3;

    return $address;
}

// input vars 체크
function check_input_vars()
{
    $max_input_vars = ini_get('max_input_vars');

    if($max_input_vars) {
        $post_vars = count($_POST, COUNT_RECURSIVE);
        $get_vars = count($_GET, COUNT_RECURSIVE);
        $cookie_vars = count($_COOKIE, COUNT_RECURSIVE);

        $input_vars = $post_vars + $get_vars + $cookie_vars;

        if($input_vars > $max_input_vars) {
            alert('폼에서 전송된 변수의 개수가 max_input_vars 값보다 큽니다.\\n전송된 값중 일부는 유실되어 DB에 기록될 수 있습니다.\\n\\n문제를 해결하기 위해서는 서버 php.ini의 max_input_vars 값을 변경하십시오.');
        }
    }
}

// HTML 특수문자 변환 htmlspecialchars
function htmlspecialchars2($str)
{
    $trans = array("\"" => "&#034;", "'" => "&#039;", "<"=>"&#060;", ">"=>"&#062;");
    $str = strtr($str, $trans);
    return $str;
}

// date 형식 변환
function conv_date_format($format, $date, $add='')
{
    if($add)
        $timestamp = strtotime($add, strtotime($date));
    else
        $timestamp = strtotime($date);

    return date($format, $timestamp);
}

// 검색어 특수문자 제거
function get_search_string($stx)
{
    $stx_pattern = array();
    $stx_pattern[] = '#\.*/+#';
    $stx_pattern[] = '#\\\*#';
    $stx_pattern[] = '#\.{2,}#';
    $stx_pattern[] = '#[/\'\"%=*\#\(\)\|\+\&\!\$~\{\}\[\]`;:\?\^\,]+#';

    $stx_replace = array();
    $stx_replace[] = '';
    $stx_replace[] = '';
    $stx_replace[] = '.';
    $stx_replace[] = '';

    $stx = preg_replace($stx_pattern, $stx_replace, $stx);

    return $stx;
}

// XSS 관련 태그 제거
function clean_xss_tags($str)
{
    $str_len = strlen($str);
    
    $i = 0;
    while($i <= $str_len){
        $result = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $str);

        if((string)$result === (string)$str) break;

        $str = $result;
        $i++;
    }
    
    return $str;
}

// XSS 어트리뷰트 태그 제거
function clean_xss_attributes($str)
{
    $str = preg_replace('#(onabort|onactivate|onafterprint|onafterupdate|onbeforeactivate|onbeforecopy|onbeforecut|onbeforedeactivate|onbeforeeditfocus|onbeforepaste|onbeforeprint|onbeforeunload|onbeforeupdate|onblur|onbounce|oncellchange|onchange|onclick|oncontextmenu|oncontrolselect|oncopy|oncut|ondataavaible|ondatasetchanged|ondatasetcomplete|ondblclick|ondeactivate|ondrag|ondragdrop|ondragend|ondragenter|ondragleave|ondragover|ondragstart|ondrop|onerror|onerrorupdate|onfilterupdate|onfinish|onfocus|onfocusin|onfocusout|onhelp|onkeydown|onkeypress|onkeyup|onlayoutcomplete|onload|onlosecapture|onmousedown|onmouseenter|onmouseleave|onmousemove|onmoveout|onmouseover|onmouseup|onmousewheel|onmove|onmoveend|onmovestart|onpaste|onpropertychange|onreadystatechange|onreset|onresize|onresizeend|onresizestart|onrowexit|onrowsdelete|onrowsinserted|onscroll|onselect|onselectionchange|onselectstart|onstart|onstop|onsubmit|onunload)\\s*=\\s*\\\?".*?"#is', '', $str);
    return $str;
}

// unescape nl 얻기
function conv_unescape_nl($str)
{
    $search = array('\\r', '\r', '\\n', '\n');
    $replace = array('', '', "\n", "\n");

    return str_replace($search, $replace, $str);
}

// 이메일 주소 추출
function get_email_address($email)
{
    preg_match("/[0-9a-z._-]+@[a-z0-9._-]{4,}/i", $email, $matches);

    return $matches[0];
}

// 파일명에서 특수문자 제거
function get_safe_filename($name)
{
    $pattern = '/["\'<>=#&!%\\\\(\)\*\+\?]/';
    $name = preg_replace($pattern, '', $name);

    return $name;
}

// 파일명 치환
function replace_filename($name)
{
    @session_start();
    $ss_id = session_id();
    $usec = get_microtime();
    $file_path = pathinfo($name);
    $ext = $file_path['extension'];
    $return_filename = sha1($ss_id.$_SERVER['REMOTE_ADDR'].$usec); 
    if( $ext )
        $return_filename .= '.'.$ext;

    return $return_filename;
}

// 문자열 암호화
function get_encrypt_string($str)
{
    if(defined('P1_STRING_ENCRYPT_FUNCTION') && P1_STRING_ENCRYPT_FUNCTION) {
        $encrypt = call_user_func(P1_STRING_ENCRYPT_FUNCTION, $str);
    } else {
        $encrypt = sql_password($str);
    }

    return $encrypt;
}

// MySQL/MariaDB mysql_native_password (* + 40 hex). PHP만으로 검증 (sql_password 와 동일 알고리즘)
function playd_mysql_native_password_hash(string $password): string
{
    $s1 = sha1($password, true);
    $s2 = sha1($s1, true);

    return '*'.strtoupper(bin2hex($s2));
}

// 비밀번호 비교
function check_password($pass, $hash)
{
    if ($hash === null || $hash === '') {
        return false;
    }
    $hash = is_string($hash) ? trim($hash) : '';

    // DB에 PASSWORD()/시드로 넣은 *xxxxxxxx 형태
    if (strlen($hash) === 41 && $hash[0] === '*') {
        return hash_equals($hash, playd_mysql_native_password_hash($pass));
    }

    $password = get_encrypt_string($pass);

    return is_string($password) && hash_equals($password, $hash);
}

// 동일한 host url 인지
function check_url_host($url, $msg='', $return_url=P1_URL, $is_redirect=false)
{
    if(!$msg)
        $msg = 'url에 타 도메인을 지정할 수 없습니다.';

    $p = @parse_url($url);
    $host = preg_replace('/:[0-9]+$/', '', $_SERVER['HTTP_HOST']);
    $is_host_check = false;
    
    // url을 urlencode 를 2번이상하면 parse_url 에서 scheme와 host 값을 가져올수 없는 취약점이 존재함
    if ( $is_redirect && !isset($p['host']) && urldecode($url) != $url ){
        $i = 0;
        while($i <= 3){
            $url = urldecode($url);
            if( urldecode($url) == $url ) break;
            $i++;
        }

        if( urldecode($url) == $url ){
            $p = @parse_url($url);
        } else {
            $is_host_check = true;
        }
    }

    if(stripos($url, 'http:') !== false) {
        if(!isset($p['scheme']) || !$p['scheme'] || !isset($p['host']) || !$p['host'])
            alert('url 정보가 올바르지 않습니다.', $return_url);
    }

    //php 5.6.29 이하 버전에서는 parse_url 버그가 존재함
    //php 7.0.1 ~ 7.0.5 버전에서는 parse_url 버그가 존재함
    if ( $is_redirect && (isset($p['host']) && $p['host']) ) {
        $bool_ch = false;
        foreach( array('user','host') as $key) {
            if ( isset( $p[ $key ] ) && strpbrk( $p[ $key ], ':/?#@' ) ) {
                $bool_ch = true;
            }
        }
        if( $bool_ch ){
            $regex = '/https?\:\/\/'.$host.'/i';
            if( ! preg_match($regex, $url) ){
                $is_host_check = true;
            }
        }
    }

    if ((isset($p['scheme']) && $p['scheme']) || (isset($p['host']) && $p['host']) || $is_host_check) {
        //if ($p['host'].(isset($p['port']) ? ':'.$p['port'] : '') != $_SERVER['HTTP_HOST']) {
        if ( ($p['host'] != $host) || $is_host_check ) {
            echo '<script>'.PHP_EOL;
            echo 'alert("url에 타 도메인을 지정할 수 없습니다.");'.PHP_EOL;
            echo 'document.location.href = "'.$return_url.'";'.PHP_EOL;
            echo '</script>'.PHP_EOL;
            echo '<noscript>'.PHP_EOL;
            echo '<p>'.$msg.'</p>'.PHP_EOL;
            echo '<p><a href="'.$return_url.'">돌아가기</a></p>'.PHP_EOL;
            echo '</noscript>'.PHP_EOL;
            exit;
        }
    }
}

// QUERY STRING 에 포함된 XSS 태그 제거
function clean_query_string($query, $amp=true)
{
    $qstr = trim($query);

    parse_str($qstr, $out);

    if(is_array($out)) {
        $q = array();

        foreach($out as $key=>$val) {
            $key = strip_tags(trim($key));
            $val = trim($val);

            switch($key) {
                case 'wr_id':
                    $val = (int)preg_replace('/[^0-9]/', '', $val);
                    $q[$key] = $val;
                    break;
                case 'sca':
                    $val = clean_xss_tags($val);
                    $q[$key] = $val;
                    break;
                case 'sfl':
                    $val = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\s]/", "", $val);
                    $q[$key] = $val;
                    break;
                case 'stx':
                    $val = get_search_string($val);
                    $q[$key] = $val;
                    break;
                case 'sst':
                    $val = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\s]/", "", $val);
                    $q[$key] = $val;
                    break;
                case 'sod':
                    $val = preg_match("/^(asc|desc)$/i", $val) ? $val : '';
                    $q[$key] = $val;
                    break;
                case 'sop':
                    $val = preg_match("/^(or|and)$/i", $val) ? $val : '';
                    $q[$key] = $val;
                    break;
                case 'spt':
                    $val = (int)preg_replace('/[^0-9]/', '', $val);
                    $q[$key] = $val;
                    break;
                case 'page':
                    $val = (int)preg_replace('/[^0-9]/', '', $val);
                    $q[$key] = $val;
                    break;
                case 'w':
                    $val = substr($val, 0, 2);
                    $q[$key] = $val;
                    break;
                case 'bo_table':
                    $val = preg_replace('/[^a-z0-9_]/i', '', $val);
                    $val = substr($val, 0, 20);
                    $q[$key] = $val;
                    break;
                case 'gr_id':
                    $val = preg_replace('/[^a-z0-9_]/i', '', $val);
                    $q[$key] = $val;
                    break;
                default:
                    $val = clean_xss_tags($val);
                    $q[$key] = $val;
                    break;
            }
        }

        if($amp)
            $sep = '&amp;';
        else
            $sep ='&';

        $str = http_build_query($q, '', $sep);
    } else {
        $str = clean_xss_tags($qstr);
    }

    return $str;
}

function get_device_change_url()
{
    $p = @parse_url(P1_URL);
    $href = $p['scheme'].'://'.$p['host'];
    if(isset($p['port']) && $p['port'])
        $href .= ':'.$p['port'];
    $href .= $_SERVER['SCRIPT_NAME'];

    $q = array();
    $device = 'device='.(P1_IS_MOBILE ? 'pc' : 'mobile');

    if($_SERVER['QUERY_STRING']) {
        foreach($_GET as $key=>$val) {
            if($key == 'device')
                continue;

            $key = strip_tags($key);
            $val = strip_tags($val);

            if($key && $val)
                $q[$key] = $val;
        }
    }

    if(!empty($q)) {
        $query = http_build_query($q, '', '&amp;');
        $href .= '?'.$query.'&amp;'.$device;
    } else {
        $href .= '?'.$device;
    }

    return $href;
}

// 발신번호 유효성 체크
function check_vaild_callback($callback){
   $_callback = preg_replace('/[^0-9]/','', $callback);

   /**
   * 1588 로시작하면 총8자리인데 7자리라 차단
   * 02 로시작하면 총9자리 또는 10자리인데 11자리라차단
   * 1366은 그자체가 원번호이기에 다른게 붙으면 차단
   * 030으로 시작하면 총10자리 또는 11자리인데 9자리라차단
   */

   if( substr($_callback,0,4) == '1588') if( strlen($_callback) != 8) return false;
   if( substr($_callback,0,2) == '02')   if( strlen($_callback) != 9  && strlen($_callback) != 10 ) return false;
   if( substr($_callback,0,3) == '030')  if( strlen($_callback) != 10 && strlen($_callback) != 11 ) return false;

   if( !preg_match("/^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080|007)\-?\d{3,4}\-?\d{4,5}$/",$_callback) &&
       !preg_match("/^(15|16|18)\d{2}\-?\d{4,5}$/",$_callback) ){
             return false;
   } else if( preg_match("/^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080)\-?0{3,4}\-?\d{4}$/",$_callback )) {
             return false;
   } else {
             return true;
   }
}

// 문자열 암복호화
class str_encrypt
{
    var $salt;
    var $lenght;

    function __construct($salt='')
    {
        if(!$salt)
            $this->salt = md5(preg_replace('/[^0-9A-Za-z]/', substr(P1_MYSQL_USER, -1), $_SERVER['SERVER_SOFTWARE'].$_SERVER['DOCUMENT_ROOT']));
        else
            $this->salt = $salt;

        $this->length = strlen($this->salt);
    }

    function encrypt($str)
    {
        $length = strlen($str);
        $result = '';

        for($i=0; $i<$length; $i++) {
            $char    = substr($str, $i, 1);
            $keychar = substr($this->salt, ($i % $this->length) - 1, 1);
            $char    = chr(ord($char) + ord($keychar));
            $result .= $char;
        }

        return strtr(base64_encode($result) , '+/=', '._-');
    }

    function decrypt($str) {
        $result = '';
        $str    = base64_decode(strtr($str, '._-', '+/='));
        $length = strlen($str);

        for($i=0; $i<$length; $i++) {
            $char    = substr($str, $i, 1);
            $keychar = substr($this->salt, ($i % $this->length) - 1, 1);
            $char    = chr(ord($char) - ord($keychar));
            $result .= $char;
        }

        return $result;
    }
}

// 불법접근을 막도록 토큰을 생성하면서 토큰값을 리턴
function get_write_token($bo_table)
{
    $token = md5(uniqid(rand(), true));
    set_session('ss_write_'.$bo_table.'_token', $token);

    return $token;
}

// POST로 넘어온 토큰과 세션에 저장된 토큰 비교
function check_write_token($bo_table)
{
    if(!$bo_table)
        alert('올바른 방법으로 이용해 주십시오.', P1_URL);

    $token = get_session('ss_write_'.$bo_table.'_token');
    set_session('ss_write_'.$bo_table.'_token', '');

    if(!$token || !$_REQUEST['token'] || $token != $_REQUEST['token'])
        alert('올바른 방법으로 이용해 주십시오.', P1_URL);

    return true;
}

function get_head_title($title){
    global $p1;

    if( isset($p1['board_title']) && $p1['board_title'] ){
        $title = $p1['board_title'];
    }

    return $title;
}

function is_use_email_certify(){
    global $config;

    if( $config['cf_use_email_certify'] && function_exists('social_is_login_check') ){
        if( $config['cf_social_login_use'] && (get_session('ss_social_provider') || social_is_login_check()) ){      //소셜 로그인을 사용한다면
            $tmp = (defined('P1_SOCIAL_CERTIFY_MAIL') && P1_SOCIAL_CERTIFY_MAIL) ? 1 : 0;
            return $tmp;
        }
    }

    return $config['cf_use_email_certify'];
}

function get_real_client_ip(){
    $real_ip = $_SERVER['REMOTE_ADDR'];

    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $_SERVER['HTTP_X_FORWARDED_FOR']) ){
        $real_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return preg_replace('/[^0-9.]/', '', $real_ip);
}

function get_call_func_cache($func, $args=array()){
    
    static $cache = array();

    $key = md5(serialize($args));

    if( isset($cache[$func]) && isset($cache[$func][$key]) ){
        return $cache[$func][$key];
    }

    $result = null;

    try{
        $cache[$func][$key] = $result = call_user_func_array($func, $args);
    } catch (Exception $e) {
        return null;
    }
    
    return $result;
}

// include 하는 경로에 data file 경로나 안전하지 않은 경로가 있는지 체크합니다.
function is_include_path_check($path='', $is_input='')
{
    if( $path ){
        if ($is_input){

            if( stripos($path, 'rar:') !== false || stripos($path, 'php:') !== false || stripos($path, 'zlib:') !== false || stripos($path, 'bzip2:') !== false || stripos($path, 'zip:') !== false || stripos($path, 'data:') !== false || stripos($path, 'phar:') !== false ){
                return false;
            }
            
            $replace_path = str_replace('\\', '/', $path);
            $slash_count = substr_count(str_replace('\\', '/', $_SERVER['SCRIPT_NAME']), '/');
            $peer_count = substr_count($replace_path, '../');
            if ( $peer_count && $peer_count > $slash_count ){
                return false;
            }

            try {
                // whether $path is unix or not
                $unipath = strlen($path)==0 || $path[0]!='/';
                $unc = substr($path,0,2)=='\\\\'?true:false;
                // attempts to detect if path is relative in which case, add cwd
                if(strpos($path,':') === false && $unipath && !$unc){
                    $path=getcwd().DIRECTORY_SEPARATOR.$path;
                    if($path[0]=='/'){
                        $unipath = false;
                    }
                }

                // resolve path parts (single dot, double dot and double delimiters)
                $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
                $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
                $absolutes = array();
                foreach ($parts as $part) {
                    if ('.'  == $part){
                        continue;
                    }
                    if ('..' == $part) {
                        array_pop($absolutes);
                    } else {
                        $absolutes[] = $part;
                    }
                }
                $path = implode(DIRECTORY_SEPARATOR, $absolutes);
                // resolve any symlinks
                // put initial separator that could have been lost
                $path = !$unipath ? '/'.$path : $path;
                $path = $unc ? '\\\\'.$path : $path;
            } catch (Exception $e) {
                //echo 'Caught exception: ',  $e->getMessage(), "\n";
                return false;
            }

            if( preg_match('/\/data\/(file|editor|qa|cache|member|member_image|session|tmp)\/[A-Za-z0-9_]{1,20}\//i', $replace_path) ){
                return false;
            }
            if( preg_match('/\.\.\//i', $replace_path) && preg_match('/plugin\//i', $replace_path) && preg_match('/okname\//i', $replace_path) ){
                return false;
            }
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        if($extension && preg_match('/(jpg|jpeg|png|gif|bmp|conf)$/i', $extension)) {
            return false;
        }
    }

    return true;
}

function option_array_checked($option, $arr=array()){
    $checked = '';

    if( !is_array($arr) ){
        $arr = explode(',', $arr);
    }

    if ( !empty($arr) && in_array($option, (array) $arr) ){
        $checked = 'checked="checked"';
    }

    return $checked;
}

// 관리자 페이지 referer 체크
function admin_referer_check($return=false)
{
    $referer = trim($_SERVER['HTTP_REFERER']);
    if(!$referer) {
        $msg = '정보가 올바르지 않습니다.';

        if($return)
            return $msg;
        else
            alert($msg, P1_URL);
    }

    $p = @parse_url($referer);

    $host = preg_replace('/:[0-9]+$/', '', $_SERVER['HTTP_HOST']);
    $msg = '';

    if($host != $p['host']) {
        $msg = '올바른 방법으로 이용해 주십시오.';
    }

    if( $msg ){
        if($return) {
            return $msg;
        } else {
            alert($msg, P1_URL);
        }
    }
}

function admin_check_xss_params($params)
{
    if(!$params) return;

    foreach($params as $key => $value) {

        if (empty($value)) continue;

        if(is_array($value)) {
            admin_check_xss_params($value);
        } else if ( preg_match('/<\s?[^\>]*\/?\s?>/i', $value) && (preg_match('/script.*?\/script/ius', $value) || preg_match('/onload=.*/ius', $value)) ){
            alert('요청 쿼리에 잘못된 스크립트문장이 있습니다.');
            // alert('요청 쿼리에 잘못된 스크립트문장이 있습니다.\\nXSS 공격일수도 있습니다.');
            die();
        }
    }

    return;
}

if(isset($_REQUEST) && $_REQUEST) {
    if(admin_referer_check(true)) {
        admin_check_xss_params($_REQUEST);
    }
}
?>