<?php
define('_MEMBER_', true);

include_once('./_common.php');
include_once(Y1_LIB_PATH.'/register.lib.php');

$login_accnt = trim($_POST['login_accnt']);

set_session('ss_check_login_accnt', '');

if ($msg = empty_login_accnt($login_accnt))    die($msg);
if ($msg = valid_login_accnt($login_accnt))    die($msg);
if ($msg = exist_login_accnt($login_accnt))    die($msg);

set_session('ss_check_login_accnt', $login_accnt);
?>