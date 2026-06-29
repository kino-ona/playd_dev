<?php
if (!defined('_ADM_')) exit; // 개별 페이지 접근 불가

$colspan = 5;

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
                    <?php if ($board['BC_SITE_USE_YN'] == 1) { ?>
                    <select name="site" id="site">
                        <option value="">사이트 선택</option>
                        <?php
                        echo option_selected("플레이디", $site, "플레이디");
                        echo option_selected("메이블", $site, "메이블");
                        echo option_selected("인스타그램", $site, "인스타그램");
                        echo option_selected("페이스북", $site, "페이스북");
                        echo option_selected("네이버블로그", $site, "네이버블로그");
						?>
                    </select>
                    <?php } ?>
                    <?php if ($board['BC_EXPS_USE_YN'] == 1) { ?>
                    <select name="exps_yn" id="exps_yn">
                        <option value="">상단고정 선택</option>
                        <?php
                        echo option_selected("Y", $exps_yn, "고정");
                        echo option_selected("N", $exps_yn, "비고정");
                        ?>
                    </select>
                    <?php } ?>
                    <?php if ($board['BC_TYPE_USE_YN'] == 1) { ?>
                    <select name="type" id="type">
                        <option value="">구분</option>
                        <?php
                        switch($bc_code) {
                            case "nsmexp":
                                $type_arr = array("광고 트렌드", "광고전략", "광고운영TIP", "시스템활용", "Trend Research");
                                break;
                            default:
                                $type_arr = array("업데이트", "공지", "안내");
                                break;
                        }
                        
                        foreach($type_arr as $k=>$v) {
                            echo option_selected($v, $type, $v);
                        }
                        ?>
                    </select>
                    <?php } ?>
                    <?php if ($board['BC_NOTI_USE_YN'] == 1) { ?>
                    <select name="noti_yn" id="noti_yn">
                        <option value="">게시여부</option>
                        <?php
                        echo option_selected("Y", $noti_yn, "노출");
                        echo option_selected("N", $noti_yn, "비노출");
                        ?>
                    </select>
                    <?php } ?>

                    
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

            <?php if ($board['BC_DATE_USE_YN'] == 1) { ?>
            <div class="oh">
                 <div class="fl">
                        뉴스레터 년/월
                        <?php
                        echo date_ym_select(conv_date_format("Y-m", ($m == "u") ? $write['B_YEAR']."-".$write['B_MONTH'] : "0000"), "date");
                        ?>
                   </div>
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
            <th><input type="checkbox" id="allCheck" /></th>
            <th>번호</th>
            <?php if ($board['BC_DATE_USE_YN'] == 1) { ?>
                <th>뉴스레터 년/월</th>
            <?}?>
            <?php if ($board['BC_SITE_USE_YN'] == 1) { ?>
            <th>사이트</th>
            <?php } ?>
            <?php if ($board['BC_TYPE_USE_YN'] == 1) { ?>
            <th>구분</th>
            <?php } ?>
            <th>제목</th>
            <?php if ($board['BC_EXPS_USE_YN'] == 1 && $bc_code != 'nsmnw') { ?>
            <th>상단고정</th>
            <?php } ?>
            <?php if ($board['BC_NOTI_USE_YN'] == 1) { ?>
            <th>게시여부</th>
            <?php } ?>
			<?php if($bc_code=="nsmpr") {?>
			<th>수집시 삭제여부</th>
			<?php } ?>

            
            <?php if($bc_code=="nsmnw") {?>
                <th>발행일</th>
                <th>작성자</th>

            <?} else {?>
            <th>등록일</th>

            <?}?>
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
            <?php if ($board['BC_DATE_USE_YN'] == 1) { ?>
                <td><?=$list[$i]['B_YEAR']?>년 <?=$list[$i]['B_MONTH']?>월</td>
            <?}?>
            <?php if ($board['BC_SITE_USE_YN'] == 1) { ?>
            <td><?=$list[$i]['B_SITE']?></td>
            <?php } ?>
            <?php if ($board['BC_TYPE_USE_YN'] == 1) { ?>
            <td><?=$list[$i]['B_TYPE']?></td>
            <?php } ?>
            <td class="al"><a href="javascript:view('<?=$list[$i]['B_SEQ']?>','<?=$list[$i]['href']?>')"><?=$list[$i]['B_TITLE']?></a></td>
            <?php if ($board['BC_EXPS_USE_YN'] == 1  && $bc_code != 'nsmnw') { ?>
            <td><?=$list[$i]['B_EXPS_YN']?></td>
            <?php } ?>
            <?php if ($board['BC_NOTI_USE_YN'] == 1) { ?>
            <td><?=$list[$i]['B_NOTI_YN']?></td>
            <?php } ?>
			<?php if($bc_code=="nsmpr") {?>
			<td>
				<?php if($list[$i]['B_SITE']=="페이스북" || $list[$i]['B_SITE']=="인스타그램" || $list[$i]['B_SITE']=="네이버블로그") {?>
					<?=$list[$i]['B_SNS_DEL_YN']?>
				<?php } else { ?>
					-
				<?php } ?>
			</td>
			<?php } ?>
            <td><?=$list[$i]['B_REGDATE']?></td>
            <?php if($bc_code=="nsmnw") {?>
                <td><?=$list[$i]['B_WRITER']?></td>

            <?}?>
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
    
<?php if ($write_href && $bc_code=="nsmpr") { ?>
	<div class="fl">
		<a href="javascript:void(0);" onclick="cron_instagram();">[인스타그램 가져오기]</a>&nbsp;
		<a href="javascript:void(0);" onclick="cron_facebook();">[페이스북 가져오기]</a>&nbsp;
		<a href="javascript:void(0);" onclick="cron_naverblog();">[네이버 블로그 가져오기]</a>
    </div>
<?php } ?>

    

	<div class="fr">
    <?if($auth['del']=='Y'){?>
        <input type="submit" class="btn btn-primary" value="선택삭제">
        <?}?>
        <?php if ($bc_code!="nsmnw") { ?>
            <input type="button" class="btn btn-primary" id="sns_block_btn" value="SNS 삭제방지">
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
    
    
    //검색기능
    function search2(){
        var f = document.f;
        //f.pageNo.value = 1;

        f.submit();
    }

</script>