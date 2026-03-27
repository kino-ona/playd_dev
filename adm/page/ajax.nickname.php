<?php
define('_MEMBER_', true);

include_once('./_common.php');
include_once(Y1_LIB_PATH.'/register.lib.php');

$nickname  = trim($_POST['nickname']);
$login_key = trim($_POST['login_key']);

set_session('ss_check_nickname', '');

if ($msg = empty_nickname($nickname))                  die($msg);
if ($msg = valid_nickname($nickname))                  die($msg);
if ($msg = count_nickname($nickname))                  die($msg);
if ($msg = exist_nickname($nickname, $login_key))      die($msg);

set_session('ss_check_nickname', $nickname);
?>