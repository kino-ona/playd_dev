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
               
                <div class="fl">


                    <?if($bc_code == 'nsmexp'){?>

                    <select name="type" id="type">
                        <option value="">구분 선택</option>
                          
                        <option value="MEDIA" <?=$type=='MEDIA'?'selected':''?>>MEDIA</option>
                        <option value="SOLUTION" <?=$type=='SOLUTION'?'selected':''?>>SOLUTION</option>
                        <option value="CASE STUDY" <?=$type=='CASE STUDY'?'selected':''?>>CASE STUDY</option>
                        
                    </select>

                    <?}?>

                    <select name="noti_yn" id="noti_yn" style="margin-right:10px;">
                        <option value="">게시여부</option>
                        <?php
                        echo option_selected("Y", $noti_yn, "노출");
                        echo option_selected("N", $noti_yn, "비노출");
                        ?>
                    </select>

                   
                    
                </div>
                <div class="fr">

                    

                    <select name="search_type" id="search_type" class="fl">
                        <?php
                        echo option_selected("all", $search_type, "전체");
                        echo option_selected("title", $search_type, "제목");
                        echo option_selected("cont", $search_type, "내용");
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

            <?if($bc_code == 'nsmnw'){?>
            <div class="oh fr">
            뉴스레터
                        <?php
                        echo date_ym_select(conv_date_format("Y-m", ($m == "u") ? $write['B_YEAR']."-".$write['B_MONTH'] : "0000"), "date");
                        ?>
            </div>
            <?}?>

        </fieldset>

    </div>
</form>

<form name="multi_del_frm" id="multi_del_frm" action="<?php echo P1_PAGE_URL ?>/post_update.php" onsubmit="return multi_del();" method="post">
<input type="hidden" name="bc_code" value="<?php echo $bc_code ?>">
<input type="hidden" name="sns_block" id="sns_block" value="">

<table class="rowTable">
    <thead>
        <tr>
            <th width="5%"><input type="checkbox" id="allCheck" /></th>
            <th width="5%">번호</th>

            
            <?if($bc_code == 'nsmnw'){?>
            <th width="10%">뉴스레터 년/월</th>
            <?}?>
            <?if($bc_code == 'nsmexp'){?>
            <th width="10%">구분</th>
            <?}?>
            <th>제목</th>
            <?if($bc_code == 'nsmexp'){?>
            <th width="10%"><a href="#" onclick="javascript:sort2('b_hits','<?=$sod=='asc'?'desc':'asc'?>');" style="cursor:pointer;">조회수 <span class="fa fa-arrow-<?=$sod=='desc'?'up':'down'?>"></span></a></th>
            <?}?>
            <?if($bc_code == 'ir_notice'){?>
                <th width="10%">첨부파일</th>
            <?}?>
            <?if($bc_code == 'pr_notice'){?>
                <th width="10%">제출인</th>
            <?}?>
            <th width="10%">게시여부</th>
            <th width="12%">발행일</th>
            <th width="10%">작성자</th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i=0; $i<count($list); $i++) {
            $list[$i]['B_SITE']    = ($list[$i]['B_SITE']) ? $list[$i]['B_SITE'] : "전체";
            $list[$i]['B_EXPS_YN'] = ($list[$i]['B_EXPS_YN'] == "Y") ? "고정" : "비고정";
            $list[$i]['B_NOTI_YN'] = ($list[$i]['B_NOTI_YN'] == "Y") ? "노출" : "비노출";
			$list[$i]['B_SNS_DEL_YN'] = ($list[$i]['B_SNS_DEL_YN'] != "N") ? "삭제" : "삭제안함";
        ?>
        <tr>
            <td>
                <input type="hidden" name="seq[<?php echo $i ?>]" value="<?php echo $list[$i]['B_SEQ'] ?>" id="seq_<?php echo $i ?>">
                <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
            </td>
            <td><?=$list[$i]['num']?></td>
            <?if($bc_code == 'nsmnw'){?>
                <td><?=$list[$i]['B_YEAR']?>년 <?=$list[$i]['B_MONTH']?>월</td>
            <?}?>

            <?if($bc_code == 'nsmexp'){?>
            <td><?=$list[$i]['B_TYPE']?></td>
            <?}?>
            <td class="al"><a href="javascript:view('<?=$list[$i]['B_SEQ']?>','<?=$list[$i]['href']?>')"><?=$list[$i]['B_TITLE']?></a></td>
            <?if($bc_code == 'nsmexp'){?>
            <td><?=$list[$i]['B_HITS']?></td>
            <?}?>
            <?if($bc_code == 'ir_notice'){
                $sql = " select * from T_BOARD_FILES where B_SEQ = ".$list[$i]['B_SEQ']." AND FI_INDEX = '2' order by FI_SORT asc ";
                $file_res1 = sql_query($sql);
                ?>
                <td style="text-align:left;">
                    <?for ($j=0; $row=sql_fetch_array($file_res1); $j++) {?>
                        <a href="<?=$row['FI_NAME']?>" target="_blank"><?=$row['FI_ORG']?></a>
                    <?}?>
                </td>
            <?}?>
            <?if($bc_code == 'pr_notice'){?>
                <td><?=$list[$i]['B_EXT2']?></td>
            <?}?>
            <td><?=$list[$i]['B_NOTI_YN']?></td>
            <td><?=substr($list[$i]['B_SEND_DT'],0,16)?></td>
            <td><?=$list[$i]['B_WRITER']?></td>
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

</script>