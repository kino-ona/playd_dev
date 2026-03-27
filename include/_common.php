<?php
if (defined('PLAYD_INCLUDE_COMMON')) {
    return;
}
define('PLAYD_INCLUDE_COMMON', true);

$__playd_root = dirname(__DIR__);
$__host = isset($_SERVER['HTTP_HOST']) ? preg_replace('/:\d+$/', '', (string) $_SERVER['HTTP_HOST']) : 'localhost';
$__host = preg_replace("/[<>'\"%'=()\/^*]/", '', $__host);
$__https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
$__proto = $__https ? 'https' : 'http';
$__port = isset($_SERVER['SERVER_PORT']) ? (int) $_SERVER['SERVER_PORT'] : 80;
$__port_str = (!in_array($__port, [80, 443], true)) ? ':'.$__port : '';

$p1_path = array(
    'path' => $__playd_root.'/adm',
    'path2' => $__playd_root,
    'path3' => $__playd_root,
    'url' => $__proto.'://'.$__host.$__port_str,
);

require_once $p1_path['path'].'/common.php';
