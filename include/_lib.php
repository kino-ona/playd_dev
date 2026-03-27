<?php
if (defined('PLAYD_INCLUDE_LIB')) {
    return;
}
define('PLAYD_INCLUDE_LIB', true);

function playd_fi_seq_sql()
{
    return '(SELECT k.FI_SEQ FROM T_BOARD_FILES k WHERE a.B_SEQ=k.B_SEQ ORDER BY k.FI_SORT ASC LIMIT 1) AS FI_SEQ';
}

function select_board_one($b_code, $b_seq)
{
    $code = sql_escape_string($b_code);
    $seq = (int) $b_seq;
    if ($seq < 1) {
        return array();
    }
    $fi = playd_fi_seq_sql();
    $sql = "SELECT a.*, {$fi},
        (SELECT k2.FI_SEQ FROM T_BOARD_FILES k2 WHERE a.B_SEQ=k2.B_SEQ AND k2.FI_INDEX='2' ORDER BY k2.FI_SORT ASC LIMIT 1) AS FI_SEQ2
        FROM T_BOARD a WHERE a.B_CODE='{$code}' AND a.B_SEQ={$seq} LIMIT 1";

    return sql_fetch($sql);
}

function select_board_list($b_code, $limit, $exclude_seq, $mode)
{
    $code = sql_escape_string($b_code);
    $lim = (int) $limit;
    if ($lim < 1) {
        $lim = 1;
    }
    $fi = playd_fi_seq_sql();

    if ($mode === 'other') {
        $ex = (int) $exclude_seq;
        $sql = "SELECT a.*, {$fi} FROM T_BOARD a WHERE a.B_CODE='{$code}' AND a.B_NOTI_YN='Y' AND a.B_SEQ <> {$ex} ORDER BY a.B_SEQ DESC LIMIT {$lim}";
    } elseif ($mode === '') {
        $sql = "SELECT a.*, {$fi} FROM T_BOARD a WHERE a.B_CODE='{$code}' AND a.B_NOTI_YN='L' ORDER BY a.B_SEQ DESC LIMIT {$lim}";
    } else {
        $sql = "SELECT a.*, {$fi} FROM T_BOARD a WHERE a.B_CODE='{$code}' AND a.B_NOTI_YN='Y' ORDER BY a.B_SEQ DESC LIMIT {$lim}";
    }

    return sql_query($sql);
}

function select_board_list_count($b_code)
{
    $code = sql_escape_string($b_code);
    $row = sql_fetch("SELECT COUNT(*) AS cnt FROM T_BOARD WHERE B_CODE='{$code}' AND B_NOTI_YN='Y'");

    return (int) ($row['cnt'] ?? 0);
}

function select_board_years()
{
    return sql_query("SELECT DISTINCT B_YEAR AS B_YEAR FROM T_BOARD WHERE B_CODE='nsmnw' AND B_YEAR IS NOT NULL AND B_YEAR <> '' ORDER BY B_YEAR DESC");
}

function select_board_max_one($b_code)
{
    $code = sql_escape_string($b_code);
    $fi = playd_fi_seq_sql();
    $sql = "SELECT a.*, {$fi} FROM T_BOARD a WHERE a.B_CODE='{$code}' ORDER BY a.B_SEQ DESC LIMIT 1";

    return sql_fetch($sql);
}

function select_board_next_one($b_code, $b_seq)
{
    $code = sql_escape_string($b_code);
    $seq = (int) $b_seq;
    if ($seq < 1) {
        return array();
    }
    $fi = playd_fi_seq_sql();
    $sql = "SELECT a.*, {$fi} FROM T_BOARD a WHERE a.B_CODE='{$code}' AND a.B_SEQ < {$seq} ORDER BY a.B_SEQ DESC LIMIT 1";

    return sql_fetch($sql);
}

function select_board_file_list($b_seq)
{
    $seq = (int) $b_seq;

    return sql_query("SELECT * FROM T_BOARD_FILES WHERE B_SEQ={$seq} ORDER BY FI_SORT ASC");
}

function select_board_file_list_count($b_seq)
{
    $seq = (int) $b_seq;
    $row = sql_fetch("SELECT COUNT(*) AS cnt FROM T_BOARD_FILES WHERE B_SEQ={$seq}");

    return (int) ($row['cnt'] ?? 0);
}

function select_report_list($mail, $pwd)
{
    if ($mail === '' || $mail === null || $pwd === '' || $pwd === null) {
        return sql_query('SELECT * FROM T_REPORT WHERE 1=0');
    }
    $mail = sql_escape_string($mail);
    $pwd = sql_escape_string($pwd);

    return sql_query("SELECT * FROM T_REPORT WHERE A_MAIL='{$mail}' AND A_PASSWD='{$pwd}' ORDER BY A_SEQ DESC");
}

function select_support_list($s_type, $limit, $keyword, $job, $obj, $ext2)
{
    global $p1;

    $type = sql_escape_string($s_type);
    $lim = (int) $limit;
    if ($lim < 1) {
        $lim = 1000;
    }
    $where = " WHERE s_type='{$type}' ";
    if ($keyword !== '' && $keyword !== null) {
        $kw = sql_escape_string($keyword);
        $where .= " AND (s_field LIKE '%{$kw}%' OR s_cont LIKE '%{$kw}%') ";
    }
    if ($obj !== '' && $obj !== null) {
        $where .= " AND s_obj = '".sql_escape_string($obj)."' ";
    }
    if ($job !== '' && $job !== null) {
        $where .= " AND s_job = '".sql_escape_string($job)."' ";
    }
    if ($ext2 !== '' && $ext2 !== null) {
        $where .= " AND s_ext2 = '".sql_escape_string($ext2)."' ";
    }
    $tbl = $p1['t_support_table'];

    return sql_query("SELECT * FROM {$tbl} {$where} ORDER BY s_date DESC LIMIT {$lim}");
}

function select_support_list_count($s_type, $keyword, $job, $obj, $ext2)
{
    global $p1;

    $type = sql_escape_string($s_type);
    $where = " WHERE s_type='{$type}' ";
    if ($keyword !== '' && $keyword !== null) {
        $kw = sql_escape_string($keyword);
        $where .= " AND (s_field LIKE '%{$kw}%' OR s_cont LIKE '%{$kw}%') ";
    }
    if ($obj !== '' && $obj !== null) {
        $where .= " AND s_obj = '".sql_escape_string($obj)."' ";
    }
    if ($job !== '' && $job !== null) {
        $where .= " AND s_job = '".sql_escape_string($job)."' ";
    }
    if ($ext2 !== '' && $ext2 !== null) {
        $where .= " AND s_ext2 = '".sql_escape_string($ext2)."' ";
    }
    $tbl = $p1['t_support_table'];
    $row = sql_fetch("SELECT COUNT(*) AS cnt FROM {$tbl} {$where}");

    return (int) ($row['cnt'] ?? 0);
}

function update_board_hits($b_seq)
{
    $seq = (int) $b_seq;
    if ($seq > 0) {
        sql_query("UPDATE T_BOARD SET B_HITS = B_HITS + 1 WHERE B_SEQ = {$seq}");
    }
}

function select_paging($write_pages, $cur_page, $total_page, $url, $add = '')
{
    return get_paging($write_pages, $cur_page, $total_page, $url, $add);
}
