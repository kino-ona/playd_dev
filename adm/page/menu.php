<?php
include_once('./_common.php');

// 권한검사
if ($member['M_AUTH_TP'] != "1") alert('권한이 없습니다.', P1_URL);

$sql = " select * from {$p1['t_menu_config_table']} order by mn_seq ";
$result = sql_query($sql);

$p1['title']    = '';
$p1['subtitle'] = '메뉴 설정 관리';
include_once('./_head.php');

$colspan = 6;
?>
<form name="fmenulist" id="fmenulist" method="post" action="./menu_update.php" onsubmit="return fmenulist_submit(this);">

<div id="menulist" class="tbl_head01 tbl_wrap">
    <table>
        <thead>
            <tr>
                <th scope="col">메뉴</th>
                <th scope="col">링크</th>
                <th scope="col">새창</th>
                <th scope="col">순서</th>
                <th scope="col">사용여부</th>
                <th scope="col">관리</th>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($i=0; $row=sql_fetch_array($result); $i++)
            {
                $bg = 'bg'.($i%2);
                $sub_menu_class = '';
                if(strlen($row['MN_CODE']) == 4) {
                    $sub_menu_class = ' sub_menu_class';
                    $sub_menu_info = '<span class="sound_only">'.$row['MN_NAME'].'의 서브</span>';
                    $sub_menu_ico = '<span class="sub_menu_ico"></span>';
                }

                $search  = array('"', "'");
                $replace = array('&#034;', '&#039;');
                $mn_name = str_replace($search, $replace, $row['MN_NAME']);
            ?>
            <tr class="<?php echo $bg; ?> menu_list menu_group_<?php echo substr($row['MN_CODE'], 0, 2); ?>">
                <td class="td_category<?php echo $sub_menu_class; ?>">
                    <input type="hidden" name="code[]" value="<?php echo substr($row['MN_CODE'], 0, 2) ?>">
                    <label for="mn_name_<?php echo $i; ?>" class="sound_only"><?php echo $sub_menu_info; ?> 메뉴<strong class="sound_only"> 필수</strong></label>
                    <input type="text" name="mn_name[]" value="<?php echo $mn_name; ?>" id="mn_name_<?php echo $i; ?>" required class="required tbl_input full_input">
                </td>
                <td>
                    <label for="mn_link_<?php echo $i; ?>" class="sound_only">링크<strong class="sound_only"> 필수</strong></label>
                    <input type="text" name="mn_link[]" value="<?php echo $row['MN_LINK'] ?>" id="mn_link_<?php echo $i; ?>" required class="required tbl_input full_input">
                </td>
                <td class="td_mng">
                    <label for="mn_target_<?php echo $i; ?>" class="sound_only">새창</label>
                    <select name="mn_target[]" id="mn_target_<?php echo $i; ?>">
                        <option value="self"<?php echo get_selected($row['MN_TARGET'], 'self', true); ?>>사용안함</option>
                        <option value="blank"<?php echo get_selected($row['MN_TARGET'], 'blank', true); ?>>사용함</option>
                    </select>
                </td>
                <td class="td_num">
                    <label for="mn_order_<?php echo $i; ?>" class="sound_only">순서</label>
                    <input type="text" name="mn_order[]" value="<?php echo $row['MN_ORDER'] ?>" id="mn_order_<?php echo $i; ?>" class="tbl_input" size="5">
                </td>
                <td class="td_mng">
                    <label for="mn_use_<?php echo $i; ?>" class="sound_only">PC사용</label>
                    <select name="mn_use[]" id="mn_use_<?php echo $i; ?>">
                        <option value="1"<?php echo get_selected($row['MN_USE'], '1', true); ?>>사용함</option>
                        <option value="0"<?php echo get_selected($row['MN_USE'], '0', true); ?>>사용안함</option>
                    </select>
                </td>
                <td class="td_mng">
                    <?php if(strlen($row['MN_CODE']) == 2) { ?>
                    <button type="button" class="btn_add_submenu btn_03">추가</button>
                    <?php } ?>
                    <button type="button" class="btn_del_menu btn_02">삭제</button>
                </td>
            </tr>
            <?php
            }

            if ($i==0)
                echo '<tr id="empty_menu_list"><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
            ?>
        </tbody>
    </table>
</div>

<!-- both button -->
<div class="bothButton">
    <div class="fr">
        <button type="button" onclick="return add_menu();" class="btn btn-primary">메뉴추가<span class="sound_only"> 새창</span></button>
        <input type="submit" name="act_button" value="확인" class="btn btn-primary">
    </div>
</div>

</form>

<script>
$(function() {
    $(document).on("click", ".btn_add_submenu", function() {
        var code = $(this).closest("tr").find("input[name='code[]']").val().substr(0, 2);
        add_submenu(code);
    });

    $(document).on("click", ".btn_del_menu", function() {
        if(!confirm("메뉴를 삭제하시겠습니까?"))
            return false;

        var $tr = $(this).closest("tr");
        if($tr.find("td.sub_menu_class").size() > 0) {
            $tr.remove();
        } else {
            var code = $(this).closest("tr").find("input[name='code[]']").val().substr(0, 2);
            $("tr.menu_group_"+code).remove();
        }

        if($("#menulist tr.menu_list").size() < 1) {
            var list = "<tr id=\"empty_menu_list\"><td colspan=\"6\" class=\"empty_table\">자료가 없습니다.</td></tr>\n";
            $("#menulist table tbody").append(list);
        } else {
            $("#menulist tr.menu_list").each(function(index) {
                $(this).removeClass("bg0 bg1")
                    .addClass("bg"+(index % 2));
            });
        }
    });
});

function add_menu()
{
    var max_code = base_convert(0, 10, 36);
    $("#menulist tr.menu_list").each(function() {
        var mn_code = $(this).find("input[name='code[]']").val().substr(0, 2);
        if(max_code < mn_code)
            max_code = mn_code;
    });

    add_menu_list(max_code, "new");
    return;
}

function add_submenu(code)
{
    add_menu_list(code, "no_new");
    return;
}

function base_convert(number, frombase, tobase) {
  //  discuss at: http://phpjs.org/functions/base_convert/
  // original by: Philippe Baumann
  // improved by: Rafał Kukawski (http://blog.kukawski.pl)
  //   example 1: base_convert('A37334', 16, 2);
  //   returns 1: '101000110111001100110100'

  return parseInt(number + '', frombase | 0)
    .toString(tobase | 0);
}

function fmenulist_submit(f)
{

    var mn_links = document.getElementsByName('mn_link[]');
    var reg = /^javascript/; 

	for (i=0; i<mn_links.length; i++){
        
	    if( reg.test(mn_links[i].value) ){ 
        
            alert('링크에 자바스크립트문을 입력할수 없습니다.');
            mn_links[i].focus();
            return false;
        }
    }

    return true;
}

function add_menu_list(code, w_new)
{
    if (w_new == "new" || !code) {
        code2 = base_convert(code.substr(0, 2), 36, 10);
        code2 = parseInt(code2) + 36;
        code2 = base_convert(code2, 10, 36);
    } else {
        code2 = code;
    }
    
    var ms = new Date().getTime();
    var sub_menu_class;
    
    if (w_new == "new") {
        sub_menu_class = " class=\"td_category\"";
    } else {
        sub_menu_class = " class=\"td_category sub_menu_class\"";
    }

    var list = "<tr class=\"menu_list menu_group_"+code2+"\">";
    list += "<td"+sub_menu_class+">";
    list += "<label for=\"mn_name_"+ms+"\"  class=\"sound_only\">메뉴<strong class=\"sound_only\"> 필수</strong></label>";
    list += "<input type=\"hidden\" name=\"code[]\" value=\""+code2+"\">";
    list += "<input type=\"text\" name=\"mn_name[]\" value=\"\" id=\"mn_name_"+ms+"\" required class=\"required frm_input full_input\">";
    list += "</td>";
    list += "<td>";
    list += "<label for=\"mn_link_"+ms+"\"  class=\"sound_only\">링크<strong class=\"sound_only\"> 필수</strong></label>";
    list += "<input type=\"text\" name=\"mn_link[]\" value=\"\" id=\"mn_link_"+ms+"\" required class=\"required frm_input full_input\">";
    list += "</td>";
    list += "<td class=\"td_mng\">";
    list += "<label for=\"mn_target_"+ms+"\"  class=\"sound_only\">새창</label>";
    list += "<select name=\"mn_target[]\" id=\"mn_target_"+ms+"\">";
    list += "<option value=\"self\">사용안함</option>";
    list += "<option value=\"blank\">사용함</option>";
    list += "</select>";
    list += "</td>";
    list += "<td class=\"td_numsmall\">";
    list += "<label for=\"mn_order_"+ms+"\"  class=\"sound_only\">순서<strong class=\"sound_only\"> 필수</strong></label>";
    list += "<input type=\"text\" name=\"mn_order[]\" value=\"0\" id=\"mn_order_"+ms+"\" required class=\"required frm_input\" size=\"5\">";
    list += "</td>";
    list += "<td class=\"td_mngsmall\">";
    list += "<label for=\"mn_use_"+ms+"\"  class=\"sound_only\">PC사용</label>";
    list += "<select name=\"mn_use[]\" id=\"mn_use_"+ms+"\">";
    list += "<option value=\"1\">사용함</option>";
    list += "<option value=\"0\">사용안함</option>";
    list += "</select>";
    list += "</td>";
    list += "<td class=\"td_mngsmall\">";
    
    if (w_new == "new") {
        list += "<button type=\"button\" class=\"btn_add_submenu btn\">추가</button>";
    }
    
    list += "<button type=\"button\" class=\"btn_del_menu btn\">삭제</button>";
    list += "</td>";
    list += "</tr>";

    var $menu_last = null;

    if(code)
        $menu_last = $("#menulist").find("tr.menu_group_"+code+":last");
    else
        $menu_last = $("#menulist").find("tr.menu_list:last");

	if($menu_last.size() > 0) {
        $menu_last.after(list);
    } else {
        if($("#menulist").find("#empty_menu_list").size() > 0)
            $("#menulist").find("#empty_menu_list").remove();

        $("#menulist").find("table tbody").append(list);
    }

    $("#menulist").find("tr.menu_list").each(function(index) {
        $(this).removeClass("bg0 bg1")
            .addClass("bg"+(index % 2));
    });
}
</script>
<?php
include_once('./_tail.php');
?>