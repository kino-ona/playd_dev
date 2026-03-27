<?php
/**
 * contents/* 공통: 알림 후 이동, 단일 컬럼 조회
 */
if (!defined('PLAYD_DBCON_LOADED')) {
    require_once __DIR__.'/dbcon.php';
}

class db_handle
{
    public function popup_msg_js($msg, $url = '')
    {
        $msgJson = json_encode((string) $msg, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
        echo '<meta charset="UTF-8"><script>';
        echo 'alert('.$msgJson.');';
        if ($url !== '' && $url !== null) {
            $urlJson = json_encode((string) $url, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
            echo 'location.href='.$urlJson.';';
        }
        echo '</script>';
        exit;
    }

    /**
     * SELECT 한 행의 첫 번째 컬럼 값 (예: SELECT MAX(...) ...)
     */
    public function queryR($sql)
    {
        $r = mysql_query($sql);
        if ($r === false) {
            return null;
        }
        $row = mysql_fetch_row($r);
        if ($row === false || $row === null) {
            return null;
        }

        return $row[0];
    }
}
