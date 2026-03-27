<?php
    include "../../../Libs/dbcon.php";

    if($_POST[page]) $page = $_POST[page];
    else $page = 1;

    $report_no = $_POST[report_no];

    $sql = "SELECT COUNT(*) FROM T_COMMENT WHERE report_no = '$report_no'";
    $result = mysql_query($sql);
    $arr = mysql_fetch_array($result);

    $allPost = $arr[0];
    $onePage = 5;
    $allPage = ceil($allPost / $onePage);
    $oneSection = 5;
    $currentSection = ceil($page / $oneSection);
    $allSection = ceil($allPage / $oneSection);
    $firstPage = ($currentSection * $oneSection) - ($oneSection - 1);

    if ($allPost == 0) {
        $lastPage = 1;
        $currentSection = 1;
        $allSection = 1;
    } else if($currentSection == $allSection) {
        $lastPage = $allPage;
    } else {
        $lastPage = $currentSection * $oneSection;
    }

    $prevPage = (($currentSection - 1) * $oneSection);
    $nextPage = (($currentSection + 1) * $oneSection) - ($oneSection - 1);
    $currentLimit = ($onePage * $page) - $onePage;
    $sqlLimit = "LIMIT ".$currentLimit.", ".$onePage;

    $sql = "SELECT * FROM T_COMMENT WHERE report_no = '$report_no' ORDER BY wdate DESC $sqlLimit";
    $result = mysql_query($sql);

    $comment_list = array();

    while($arr = mysql_fetch_array($result)) {
        $data[parents] = $arr[parents];
        $data[comment] = $arr[comment];
        $data[writer] = $arr[writer];
        $data[date] = date("Y-m-d H:i", strtotime($arr[wdate]));

        $comment_list[] = $data;
    }

    $paging = "";

    if($currentSection > 1) $paging .= '<a href="javascript:get_comment_list(1)" class="pprev">&lt;&lt;</a>';
    if($page > 1) $paging .= '<a href="javascript:get_comment_list('.($page - 1).')" class="prev">&lt;</a>';

    for($i=$firstPage; $i<=$lastPage; $i++) {
        if($i == $page) $paging .= '<a class="active">'.$i.'</a>';
        else $paging .= '<a href="javascript:get_comment_list('.$i.')">'.$i.'</a>';
    }

    if($page < $allPage && $allPage != 0) $paging .= '<a href="javascript:get_comment_list('.($page + 1).')" class="next">&gt;</a>';
    if($currentSection < $allSection) $paging .= '<a href="javascript:get_comment_list('.$allPage.')" class="nnext">&gt;&gt;</a>';

    echo json_encode(array("comment_list" => $comment_list, "paging" => $paging));
?>
