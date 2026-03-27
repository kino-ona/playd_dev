<?php
/**
 * contents/* 레거시 스크립트용 DB 연결 + mysql_* 호환 레이어 (PHP 7+ / mysqli)
 * 환경변수 PLAYD_DB_* 가 있으면 우선 (docker-compose 와 adm/dbconfig 와 동일 키)
 */
if (defined('PLAYD_DBCON_LOADED')) {
    return;
}
define('PLAYD_DBCON_LOADED', true);

$playdDbEnv = static function (string $key, string $default): string {
    $v = getenv($key);

    return $v === false ? $default : $v;
};

$dbHost = $playdDbEnv('PLAYD_DB_HOST', '127.0.0.1');
$dbUser = $playdDbEnv('PLAYD_DB_USER', 'root');
$dbPass = $playdDbEnv('PLAYD_DB_PASSWORD', '');
$dbName = $playdDbEnv('PLAYD_DB_NAME', 'playd');

$GLOBALS['playd_mysql_link'] = @mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
if (!$GLOBALS['playd_mysql_link']) {
    header('HTTP/1.1 503 Service Unavailable');
    header('Content-Type: text/plain; charset=utf-8');
    exit('Database connection failed.');
}
mysqli_set_charset($GLOBALS['playd_mysql_link'], 'utf8mb4');

if (!function_exists('mysql_query')) {
    function mysql_query($sql, $link = null)
    {
        $l = $link ?? $GLOBALS['playd_mysql_link'];

        return mysqli_query($l, $sql);
    }

    function mysql_fetch_array($result, $result_type = MYSQLI_BOTH)
    {
        if (!$result instanceof mysqli_result) {
            return false;
        }

        return mysqli_fetch_array($result, $result_type);
    }

    function mysql_fetch_row($result)
    {
        if (!$result instanceof mysqli_result) {
            return false;
        }

        return mysqli_fetch_row($result);
    }
}
unset($playdDbEnv);
