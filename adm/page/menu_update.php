<?php
include_once('./_common.php');

// 권한검사
if ($member['M_AUTH_TP'] != "1") alert('권한이 없습니다.', P1_URL);

check_admin_token();

// 이전 메뉴정보 삭제
$sql = " delete from {$p1['t_menu_config_table']} ";
sql_query($sql);

$group_code = null;
$primary_code = null;
$count = count($_POST['code']);

for ($i=0; $i<$count; $i++)
{
    $_POST = array_map_deep('trim', $_POST);
    
    $_POST['mn_link'][$i] = is_array($_POST['mn_link']) ? clean_xss_tags($_POST['mn_link'][$i], 1) : '';

    $code    = is_array($_POST['code']) ? strip_tags($_POST['code'][$i]) : '';
    $mn_name = is_array($_POST['mn_name']) ? strip_tags($_POST['mn_name'][$i]) : '';
    $mn_link = (preg_match('/^javascript/i', $_POST['mn_link'][$i]) || preg_match('/script:/i', $_POST['mn_link'][$i])) ? P1_URL : strip_tags($_POST['mn_link'][$i]);
    
    if(!$code || !$mn_name || !$mn_link)
        continue;

    $sub_code = '';
    if($group_code == $code) {
        $sql = " select MAX(SUBSTRING(mn_code,3,2)) as max_me_code
                    from {$p1['t_menu_config_table']}
                    where SUBSTRING(mn_code,1,2) = '$primary_code' ";
        $row = sql_fetch($sql);

        $sub_code = base_convert($row['max_me_code'], 36, 10);
        $sub_code += 36;
        $sub_code = base_convert($sub_code, 10, 36);

        $mn_code = $primary_code.$sub_code;
    } else {
        $sql = " select MAX(SUBSTRING(mn_code,1,2)) as max_me_code
                    from {$p1['t_menu_config_table']}
                    where LENGTH(mn_code) = '2' ";
        $row = sql_fetch($sql);

        $mn_code = base_convert($row['max_me_code'], 36, 10);
        $mn_code += 36;
        $mn_code = base_convert($mn_code, 10, 36);

        $group_code = $code;
        $primary_code = $mn_code;
    }

    // 메뉴 등록
    $sql = " insert into {$p1['t_menu_config_table']}
                set mn_code         = '".$mn_code."',
                    mn_name         = '".$mn_name."',
                    mn_link         = '".$mn_link."',
                    mn_target       = '".strip_tags($_POST['mn_target'][$i])."',
                    mn_order        = '".strip_tags($_POST['mn_order'][$i])."',
                    mn_use          = '".strip_tags($_POST['mn_use'][$i])."' ";
    sql_query($sql);
}

goto_url('./menu.php');
?>
