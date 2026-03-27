<?php
if (!defined('_ADM_')) exit;

function empty_m_id($reg_m_id)
{
    if (trim($reg_m_id)=='')
        return "회원아이디를 입력해 주십시오.";
    else
        return "";
}

function valid_m_id($reg_m_id)
{
    if (preg_match("/[^0-9a-z_]+/i", $reg_m_id))
        return "회원아이디는 영문자, 숫자, _ 만 입력하세요.";
    else
        return "";
}

function count_m_id($reg_m_id)
{
    if (strlen($reg_m_id) < 3)
        return "회원아이디는 최소 3글자 이상 입력하세요.";
    else
        return "";
}

function exist_m_id($reg_m_id)
{
    global $p1;

    $reg_m_id = trim($reg_m_id);
    if ($reg_m_id == "") return "";

    $sql = " select count(*) as cnt from `{$p1['t_mgr_table']}` where m_id = '$reg_m_id' ";
    $row = sql_fetch($sql);
    if ($row['cnt'])
        return "이미 사용중인 회원아이디 입니다.";
    else
        return "";
}

function empty_m_email($reg_m_email)
{
    if (!trim($reg_m_email))
        return "E-mail 주소를 입력해 주십시오.";
    else
        return "";
}

function valid_m_email($reg_m_email)
{
    if (!preg_match("/([0-9a-zA-Z_-]+)@([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)/", $reg_m_email))
        return "E-mail 주소가 형식에 맞지 않습니다.";
    else
        return "";
}

function exist_m_email($reg_m_email, $reg_m_id)
{
    global $p1;
    $row = sql_fetch(" select count(*) as cnt from `{$p1['t_mgr_table']}` where m_mail = '$reg_m_email' and m_id <> '$reg_m_id' ");
    if ($row['cnt'])
        return "이미 사용중인 E-mail 주소입니다.";
    else
        return "";
}
?>