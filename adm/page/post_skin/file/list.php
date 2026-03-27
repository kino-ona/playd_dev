<?php
if (!defined('_ADM_')) exit; // 개별 페이지 접근 불가

$colspan = 6;

if ($board['BC_SITE_USE_YN'] == 1) $colspan++;
if ($board['BC_EXPS_USE_YN'] == 1) $colspan++;
if ($board['BC_TYPE_USE_YN'] == 1) $colspan++;
if ($board['BC_NOTI_USE_YN'] == 1) $colspan++;


?>
<form name="f" method="post">
<input type="hidden" name="bc_code" value="<?php echo $bc_code ?>">
    <!-- search -->
    <div class="pageCount">


        <fieldset>
            <legend class="hid1">검색 테이블</legend>

            <div class="oh">
               
                <div class="fr">
                    <select name="search_type" id="search_type" class="fl">
                        <?php
                        echo option_selected("file", $search_type, "파일명");
                        echo option_selected("writer", $search_type, "작성자");
                        ?>
                    </select>
                    <label for="search_txt" class="fl ML10"> 
                        <input type="text" id="search_txt" name="search_txt" value="<?=$search_txt?>">
                    </label>
                    <div class="acButton dib fl ML10">
                    <button class="btn btn-default" type="button" onclick="reset2()">초기화</button>
                        <button class="btn btn-success" type="button" onClick="search2()">검색</button>
                    </div>
                </div>
            </div>
        </fieldset>

    </div>
</form>

<form name="multi_del_frm" id="multi_del_frm" action="<?php echo P1_PAGE_URL ?>/post_update.php" onsubmit="return multi_del();" method="post">
<input type="hidden" name="bc_code" value="<?php echo $bc_code ?>">
<input type="hidden" name="sns_block" id="sns_block" value="">
<input type="hidden" name="fi_name" id="fi_name" value="">

<table class="rowTable">
    <thead>
        <tr>
            <th width="5%"><input type="checkbox" id="allCheck" /></th>
            <th width="5%">번호</th>
            <th>파일명</th>
            <th>링크</th>
            <th width="30%">첨부파일</th>
            <th width="10%">등록일</th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i=0; $i<count($list); $i++) {
           
            $sql = " select * from T_BOARD_FILES where B_SEQ = ".$list[$i]['B_SEQ']." AND FI_INDEX = '1' order by FI_SORT asc ";
            $file_res1 = sql_fetch($sql);

        ?>
        <tr>
            <td>
                <input type="hidden" name="seq[<?php echo $i ?>]" value="<?php echo $list[$i]['B_SEQ'] ?>" id="seq_<?php echo $i ?>">
                <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
            </td>
            <td><?=$list[$i]['num']?></td>
            <td class="al"><?=$file_res1['FI_ORG']?></td>
            <td><a href="<?=$file_res1['FI_NAME']?>" target="_blank"><?=$file_res1['FI_NAME']?></a></td>
            <td>
                <button class="btn btn-default" type="button" onclick="javascript:download('<?=$file_res1['FI_SEQ']?>');">다운로드</button>
                <button class="btn btn-default" type="button" onclick="javascript:linkcopy('https://www.playd.com<?=$file_res1['FI_NAME']?>');">링크복사</button>
            </td>
            <td><?=substr($list[$i]['B_REGDATE'],0,10)?></td>
        </tr>
        <?php
        }
        if ($i == 0)
            echo "<tr><td colspan=\"".$colspan."\">자료가 없습니다.</td></tr>";
        ?>
    </tbody>
</table>

<!-- both button -->
<div class="bothButton">
<div class="fl">
        전체 <span style="font-size:14px;color:#aa0000; padding-left:5px;"><?=number_format($total_count)?></span>
    </div>
    
	<div class="fr">
    <?if($auth['del']=='Y'){?>
        <input type="submit" class="btn btn-primary" value="선택삭제">
        <?}?>
        <?if($auth['write']=='Y'){?>
		<button class="btn btn-primary" type="button" onclick="location.href='<?=$write_href?>'">등록</button>
        <?}?>
    </div>
</div>

</form>

<!-- pagination -->
<?=$pages?>

<!-- 뷰 페이지 파라미터 넘겨주기 -->
<form name="view" action="" method="post">
    <input type="hidden" name="m" value="u">
    <input type="hidden" name="page" value="<?=$page?>">
    <input type="hidden" name="search_type" value="<?=$search_type?>">
    <input type="hidden" name="search_txt" value="<?=$search_txt?>">
    <?php if ($board['BC_SITE_USE_YN'] == 1) { ?>
    <input type="hidden" name="site" value="<?=$site?>">
    <?php } ?>
    <?php if ($board['BC_EXPS_USE_YN'] == 1) { ?>
    <input type="hidden" name="exps_yn" value="<?=$exps_yn?>">
    <?php } ?>
    <?php if ($board['BC_TYPE_USE_YN'] == 1) { ?>
    <input type="hidden" name="type" value="<?=$type?>">
    <?php } ?>
    <?php if ($board['BC_NOTI_USE_YN'] == 1) { ?>
    <input type="hidden" name="noti_yn" value="<?=$noti_yn?>">
    <?php } ?>
    <input type="hidden" name="seq" value="">
    <input type="hidden" name="bc_code" value="<?=$bc_code?>">
</form>

<script type="text/javascript">
	$(document).ready(function(){
		$("#sns_block_btn").click(function(){
			//hidden sns_block값 강제설정.
			$("#sns_block").val("sns_block");

			//form submit.
			$("#multi_del_frm").submit();
		});
	});

	function cron_instagram(){
		alert("완료 메세지가 뜰 때까지 기다려 주세요.");
		location.href="cron_mayor_instagram.php";
	}

	function cron_facebook(){
		alert("완료 메세지가 뜰 때까지 기다려 주세요.");
		location.href="cron_mayor_facebook.php";
	}

	function cron_naverblog(){
		alert("완료 메세지가 뜰 때까지 기다려 주세요.");
		location.href="cron_mayor_naver.php";
	}

    function reset2(){
        document.location.href = '<?=basename($_SERVER["PHP_SELF"])?>?bc_code=<?=$bc_code?>';
    }

    function sort2(val,order){
        document.location.href = '<?=basename($_SERVER["PHP_SELF"])?>?bc_code=<?=$bc_code?>&sst=' + val + '&sod='+order;
    }
    
    
    //검색기능
    function search2(){
        var f = document.f;
        //f.pageNo.value = 1;
        <?if($bc_code == 'nsmexp'){?>
        if(f.type.value =='' && f.search_type.value == 'all' && f.search_txt.value  == '' && f.noti_yn.value == '' ){
            alert('검색 구분 값 선택을 하거나 검색어를 입력해 주세요.');
            return;
        }
        <?}else{?>
         if(f.search_type.value == 'all' && f.search_txt.value  == '' && f.noti_yn.value == '' ){
            alert('검색 구분 값 선택을 하거나 검색어를 입력해 주세요.');
            return;
        }

        <?}?>

        f.submit();
    }


    function download(val){
        document.location.href = 'file_download.php?seq=' + val;
    }

    


    function linkcopy(value) {
        var tempInput = document.createElement("input");
        tempInput.style = "position: absolute; left: -1000px; top: -1000px";
        tempInput.value = value;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);

        alert('복사되었습니다.');
    }


</script>