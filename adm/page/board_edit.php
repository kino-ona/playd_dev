<?php
include_once('./_common.php');

// 권한검사
$auth = get_check_admin_auth($member); //alert('권한이 없습니다.', P1_URL);
if($auth['read'] != 'Y') {
    alert('권한이 없습니다.', '/adm/page/login.php');
    exit;
}

if ($m == "") {
    $html_title = "등록";
    
    $required_bc_code = 'required';
} else if ($m == "u") {
    $bo = get_board($seq);
    
    if (!$bo['BC_SEQ'])
        alert('존재하지 않는 정보입니다.');
    
    $html_title = "수정";
    
    $required_bc_code = 'readonly';
    
    $bo['BC_SEQ']              = get_text($bo['BC_SEQ']);                 # 게시판번호
    $bo['BC_CODE']             = get_text($bo['BC_CODE']);                # 게시판 관리 코드(종류)
    $bo['BC_NAME']             = get_text($bo['BC_NAME']);                # 게시판이름
    $bo['BC_TYPE']             = get_text($bo['BC_TYPE']);                # 게시판종류
    $bo['BC_SKIN']             = get_text($bo['BC_SKIN']);                # 게시판스킨
    $bo['BC_EDITOR']           = get_text($bo['BC_EDITOR']);              # 게시판에디터
    $bo['BC_ROWS']             = get_text($bo['BC_ROWS']);                # 리스트 row
    $bo['BC_PAGES_ROWS']       = get_text($bo['BC_PAGES_ROWS']);          # 페이지 row
    $bo['BC_UPLOAD_NM']        = get_text($bo['BC_UPLOAD_NM']);           # 파일 업로드 이름
    $bo['BC_SITE_USE_YN']      = get_text($bo['BC_SITE_USE_YN']);         # 사이트 사용여부
    $bo['BC_EXPS_USE_YN']      = get_text($bo['BC_EXPS_USE_YN']);         # 상단고정 사용여부
    $bo['BC_TYPE_USE_YN']      = get_text($bo['BC_TYPE_USE_YN']);         # 구분 사용여부
    $bo['BC_NOTI_USE_YN']      = get_text($bo['BC_NOTI_USE_YN']);         # 노출 사용여부
    $bo['BC_DATE_USE_YN']      = get_text($bo['BC_DATE_USE_YN']);         # 년/월 사용여부
    $bo['BC_CORP_NAME_USE_YN'] = get_text($bo['BC_CORP_NAME_USE_YN']);    # 회사명 사용여부
    $bo['BC_NAME_USE_YN']      = get_text($bo['BC_NAME_USE_YN']);         # 대표자 사용여부
    $bo['BC_SHARE_USE_YN']     = get_text($bo['BC_SHARE_USE_YN']);        # 게시글불러오기 사용여부
    $bo['BC_REG_DTTM']         = get_text($bo['BC_REG_DTTM']);            # 등록일시
    $bo['BC_UPD_DTTM']         = get_text($bo['BC_UPD_DTTM']);            # 최종변경일시
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

$p1['title']    = '';
$p1['subtitle'] = '게시판 설정 관리';
include_once('./_head.php');
?>
<form name="frm" method="post" action="<?php echo P1_PAGE_URL ?>/board_insert.php" onsubmit="return send();" enctype="multipart/form-data">
<input type="hidden" name="m" value="<?php echo $m ?>">
<?php if ($bo['BC_SEQ']) { ?>
<input type="hidden" name="seq" value="<?=$bo['BC_SEQ']?>">
<?php } ?>
    <fieldset>
    <legend class="hid1">게시판 등록 및 수정</legend>
        <table class="colTable">
            <tr>
                <th><span>*</span>게시판코드</th>
                <td>
                    <label for="bc_code"><input type="text" id="bc_code" name="bc_code" value="<?=$bo['BC_CODE']?>" <?php echo $required_bc_code ?>></label>
                </td>
            </tr>
            <tr>
                <th><span>*</span>게시판그룹</th>
                <td>
                    <label for="bc_group">
                        <select name="bc_group" id="bc_group" required>
                            <option value="">게시판종류 선택</option>
                            <?php
                            echo option_selected("a", $bo['BC_GROUP'], "게시판 관리");
                            echo option_selected("b", $bo['BC_GROUP'], "문의관리");
                            echo option_selected("c", $bo['BC_GROUP'], "채용관리(PLAYD)");
                            echo option_selected("d", $bo['BC_GROUP'], "채용관리(MABLE)");
                            ?>
                        </select>
                    </label>
                </td>
            </tr>
            <tr>
                <th><span>*</span>게시판이름</th>
                <td>
                    <label for="bc_name"><input type="text" id="bc_name" name="bc_name" value="<?=$bo['BC_NAME']?>" required></label>
                </td>
            </tr>
            <tr>
                <th><span>*</span>파일업로드이름</th>
                <td>
                    <label for="bc_upload_nm"><input type="text" id="bc_upload_nm" name="bc_upload_nm" value="<?=$bo['BC_UPLOAD_NM']?>"></label>
                </td>
            </tr>
            <tr>
                <th><span>*</span>게시판종류</th>
                <td>
                    <label for="bc_type">
                        <select name="bc_type" id="bc_type" required>
                            <option value="">게시판종류 선택</option>
                            <?php
                            echo option_selected("post", $bo['BC_TYPE'], "일반");
                            echo option_selected("qna", $bo['BC_TYPE'], "문의");
                            ?>
                        </select>
                    </label>
                </td>
            </tr>
            <tr>
                <th><span>*</span>게시판스킨</th>
                <td>
                    <label for="bc_skin">
                        <?php echo get_skin_select('post_skin', 'bc_skin', 'bc_skin', $bo['BC_SKIN'], "required"); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th><span>*</span>게시판에디터</th>
                <td>
                    <label for="bc_skin">
                        <select name="bc_editor" id="bc_editor" required>
                        <?php
                        $arr = get_skin_dir('', P1_EDITOR_PATH);
                        for ($i=0; $i<count($arr); $i++) {
                            if ($i == 0) echo "<option value=\"\">사용안함</option>";
                            echo "<option value=\"".$arr[$i]."\"".get_selected($bo['BC_EDITOR'], $arr[$i]).">".$arr[$i]."</option>\n";
                        }
                        ?>
                        </select>
                    </label>
                </td>
            </tr>
            <tr>
                <th><span>*</span>보여질 리스트 개수</th>
                <td>
                    <label for="bc_rows"><input type="number" id="bc_rows" name="bc_rows" value="<?=$bo['BC_ROWS']?>" required></label>
                </td>
            </tr>
            <tr>
                <th><span>*</span>보여질 페이지 개수</th>
                <td>
                    <label for="bc_pages_rows"><input type="number" id="bc_pages_rows" name="bc_pages_rows" value="<?=$bo['BC_PAGES_ROWS']?>" required></label>
                </td>
            </tr>
            
            <tr>
                <th><span>*</span>사이트</th>
                <td>
                    <label for="bc_site_use_yn">
                        <select name="bc_site_use_yn" id="bc_site_use_yn" required>
                            <option value="">선택</option>
                            <?php
                            echo option_selected("1", $bo['BC_SITE_USE_YN'], "사용");
                            echo option_selected("0", $bo['BC_SITE_USE_YN'], "미사용");
                            ?>
                        </select>
                    </label>
                </td>
            </tr>
            <tr>
                <th><span>*</span>상단고정</th>
                <td>
                    <label for="bc_exps_use_yn">
                        <select name="bc_exps_use_yn" id="bc_exps_use_yn" required>
                            <option value="">선택</option>
                            <?php
                            echo option_selected("1", $bo['BC_EXPS_USE_YN'], "사용");
                            echo option_selected("0", $bo['BC_EXPS_USE_YN'], "미사용");
                            ?>
                        </select>
                    </label>
                </td>
            </tr>
            <tr>
                <th><span>*</span>구분</th>
                <td>
                    <label for="bc_type_use_yn">
                        <select name="bc_type_use_yn" id="bc_type_use_yn" required>
                            <option value="">선택</option>
                            <?php
                            echo option_selected("1", $bo['BC_TYPE_USE_YN'], "사용");
                            echo option_selected("0", $bo['BC_TYPE_USE_YN'], "미사용");
                            ?>
                        </select>
                    </label>
                </td>
            </tr>
            <tr>
                <th><span>*</span>노출</th>
                <td>
                    <label for="bc_noti_use_yn">
                        <select name="bc_noti_use_yn" id="bc_noti_use_yn" required>
                            <option value="">선택</option>
                            <?php
                            echo option_selected("1", $bo['BC_NOTI_USE_YN'], "사용");
                            echo option_selected("0", $bo['BC_NOTI_USE_YN'], "미사용");
                            ?>
                        </select>
                    </label>
                </td>
            </tr>
            <tr>
                <th><span>*</span>년/월</th>
                <td>
                    <label for="bc_date_use_yn">
                        <select name="bc_date_use_yn" id="bc_date_use_yn" required>
                            <option value="">선택</option>
                            <?php
                            echo option_selected("1", $bo['BC_DATE_USE_YN'], "사용");
                            echo option_selected("0", $bo['BC_DATE_USE_YN'], "미사용");
                            ?>
                        </select>
                    </label>
                </td>
            </tr>
            <tr>
                <th><span>*</span>회사명</th>
                <td>
                    <label for="bc_corp_name_use_yn">
                        <select name="bc_corp_name_use_yn" id="bc_corp_name_use_yn" required>
                            <option value="">선택</option>
                            <?php
                            echo option_selected("1", $bo['BC_CORP_NAME_USE_YN'], "사용");
                            echo option_selected("0", $bo['BC_CORP_NAME_USE_YN'], "미사용");
                            ?>
                        </select>
                    </label>
                </td>
            </tr>
            <tr>
                <th><span>*</span>대표자</th>
                <td>
                    <label for="bc_name_use_yn">
                        <select name="bc_name_use_yn" id="bc_name_use_yn" required>
                            <option value="">선택</option>
                            <?php
                            echo option_selected("1", $bo['BC_NAME_USE_YN'], "사용");
                            echo option_selected("0", $bo['BC_NAME_USE_YN'], "미사용");
                            ?>
                        </select>
                    </label>
                </td>
            </tr>
            <tr>
                <th><span>*</span>게시글 불러오기</th>
                <td>
                    <label for="bc_share_use_yn">
                        <select name="bc_share_use_yn" id="bc_share_use_yn" required>
                            <option value="">선택</option>
                            <?php
                            echo option_selected("1", $bo['BC_SHARE_USE_YN'], "사용");
                            echo option_selected("0", $bo['BC_SHARE_USE_YN'], "미사용");
                            ?>
                        </select>
                    </label>
                </td>
            </tr>
        </table>
        <!-- both button -->
        <div class="bothButton">
            <div class="fl">
                <button class="btn btn-inverse" type="button" onclick="fnGoView('<?php echo P1_PAGE_URL ?>/board.php');">목록</button>
            </div>
            <div class="fr">
                <?php if ($m == "u") { ?>
                <?if($auth['write'] == 'Y') {?>
                <input type="submit" class="btn btn-primary" value="수정">
                <?}?>
                <?if($auth['del'] == 'Y') {?>
                <button class="btn btn-inverse" type="button" onclick="deletes('<?php echo P1_PAGE_URL ?>/board_del.php');">삭제</button>
                <?}?>
                <?php } ?>
                <?php if ($m == "") { ?>
                <?if($auth['write'] == 'Y') {?>
                <input type="submit" class="btn btn-inverse" value="등록">
                <?}?>
                <?php } ?>
            </div>
        </div>
    </fieldset>
</form>
<!-- 목록으로 돌아가기 -->
<form name="golist" action="" method="post">
    <input type="hidden" name="page" value="<?=$page?>">
    <input type="hidden" name="search_type" value="<?=$search_type?>">
    <input type="hidden" name="search_txt" value="<?=$search_txt?>">
    <input type="hidden" name="group" value="<?=$group?>">
    <input type="hidden" name="type" value="<?=$type?>">
    <input type="hidden" name="skin" value="<?=$skin?>">
</form>

<script type="text/javascript">
//등록 & 수정
function send() {
    if (confirm("<?=$html_title?> 하시겠습니까?")) {
        if ($("#bc_code").val() == null || $("#bc_code").val() == "") {
            alert("게시판코드를 입력해주세요");
            return false;
        }
        
        if($("#bc_name").val() == null || $("#bc_name").val() == "") {
            alert("게시판이름을 입력해주세요");
            return false;
        }
        
        if(!$("#bc_type > option:selected".val())) {
            alert("게시판유형을 선택해주세요");
            return false;
        }
        
        if(!$("#bc_use_yn > option:selected".val())) {
            alert("사용여부를 선택해주세요");
            return false;
        }
        
        return;
    }
}
</script>
<?php
include_once('./_tail.php');
?>