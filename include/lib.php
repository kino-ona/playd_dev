<?
  //모바일이면 모바일로 
  $mobile_agent = "/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/";
  //$filename = basename($_SERVER["PHP_SELF"]);
  $requri = explode('/',  $_SERVER['REQUEST_URI']);
  $filename = $requri[2];

  $is_mobile = false;
  if(preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])){
    $is_mobile = true;
    if(strpos($_SERVER["REQUEST_URI"],'/m/') === false){
      header( 'Location: /m/'.$filename );
    }
  }else{
    if(strpos($_SERVER["REQUEST_URI"],'/w/') === false){
      header( 'Location: /w/'.$filename );
    }
  }

  // 연도만 가져오기
  function select_board_years(){
    $sql = "  select B_YEAR from T_BOARD where B_CODE='nsmnw' and B_NOTI_YN ='Y' GROUP BY B_YEAR order by B_YEAR DESC limit 100";
    $result = sql_query($sql);
    return $result;
  }
  

  // 게시판 리스트 가져오기
  function select_board_list($b_code, $limit, $b_seq = 0, $mode = 'list'){
    $sql = "  select a.*, (select k.FI_SEQ from T_BOARD_FILES k where a.B_SEQ=k.B_SEQ limit 1 )as FI_SEQ from T_BOARD a ";
    $sql.= " where a.B_NOTI_YN='Y' and a.B_CODE='".$b_code."' and a.B_NOTI_YN ='Y' ";
    if($mode=='other') {
      $sql .= " and a.B_SEQ NOT IN (".$b_seq.") ";
    }
    if($mode == 'recomm'){
      $sql.= " order by a.B_HITS DESC, a.B_SEQ DESC limit ".$limit;
    } else {
      $sql.= " order by a.B_SEQ DESC limit ".$limit;
    }
    
    $result = sql_query($sql);
    return $result;
  }

  // 게시판 카운트 가져오기
  function select_board_list_count($b_code){
    $sql = "  select count(*) as count from T_BOARD where B_NOTI_YN='Y' and  B_CODE='".$b_code."' and B_NOTI_YN ='Y' ";
    $result = sql_fetch($sql);
    return $result['count'];
  }


  function select_board_file_list($seq){
    $sql = "  select a.* from T_BOARD_FILES a where a.B_SEQ='".$seq."' and a.FI_INDEX=1 order by a.FI_SORT asc ";
    $result = sql_query($sql);
    return $result;
  }
  function select_board_file_list_count($seq){
    $sql = "  select count(*) as count from T_BOARD_FILES a where a.B_SEQ='".$seq."' and a.FI_INDEX=1 ";
    $result = sql_fetch($sql);
    return $result['count'];
  }


  // 게시판 리스트 가져오기
  function select_board_one($b_code, $b_seq){
    $sql = "  select a.*, (select k.FI_SEQ from T_BOARD_FILES k where a.B_SEQ=k.B_SEQ limit 1 ) as FI_SEQ ";
    $sql.= " ,  (select k.FI_NAME from T_BOARD_FILES k where a.B_SEQ=k.B_SEQ AND k.FI_INDEX =1 limit 1 ) as FI_NAME  ";
    $sql.= " ,  (select k.FI_NAME from T_BOARD_FILES k where a.B_SEQ=k.B_SEQ AND k.FI_INDEX =2 limit 1 ) as FI_NAME2  ";
    $sql.= " ,  (select k.FI_SEQ from T_BOARD_FILES k where a.B_SEQ=k.B_SEQ AND k.FI_INDEX =2 limit 1 ) as FI_SEQ2  ";
    $sql.= " from T_BOARD a where a.B_NOTI_YN = 'Y' and a.B_CODE='".$b_code."' and a.B_SEQ = ".$b_seq;
    $result = sql_fetch($sql);
    return $result;
  }

  function select_board_max_one($b_code){
    $sql = "  select a.* ";
    $sql.= " from T_BOARD a where a.B_NOTI_YN='Y' and a.B_CODE='".$b_code."' and a.B_NOTI_YN ='Y' order by a.B_SEQ desc limit 1 ";
    $result = sql_fetch($sql);
    return $result;
  }

  function select_board_next_one($b_code, $b_seq){
    $sql = "  select a.* ";
    $sql.= " from T_BOARD a where a.B_NOTI_YN='Y' and a.B_CODE='".$b_code."' and a.B_NOTI_YN ='Y' and a.B_SEQ < ".$b_seq." order by a.B_SEQ desc limit 1 ";
    $result = sql_fetch($sql);
    return $result;
  }
  
  function update_board_hits($b_seq){
    if($b_seq){
      $sql = 'update T_BOARD set B_HITS = B_HITS+1 where B_SEQ = '.$b_seq;
      sql_query($sql);
    }
  }


  function select_report_list($a_mail, $a_pwd, $limit = 1000){
    $sql = "  select a.* ";
    $sql.= " from T_REPORT a where a.A_MAIL ='".$a_mail."' and a.A_PASSWD = '".$a_pwd."' ";
    $sql.= " order by a.A_SEQ DESC limit ".$limit;
    $result = sql_query($sql);
    return $result;
  }

  function select_support_list($s_type, $limit = 1000, $keyword = '', $job = '', $obj = '', $ext2 = ''){
    $sql = "  select a.* ";
    $sql.= " from T_SUPPORT a where a.S_NOTI_YN='Y' and a.S_TYPE = '".$s_type."' ";
    if($keyword){
      $sql.= " and ( a.S_FIELD like '%".$keyword."%' or a.S_EXT3 like '%".$keyword."%' ) ";
    }
    if($job){
      $sql.= " and a.S_JOB like '%".$job."%' ";
    }
    if($obj){
      $sql.= " and a.S_OBJ like '%".$obj."%' ";
    }
    if($ext2){
      $sql.= " and a.S_EXT2 like '%".$ext2."%' ";
    }
    
    $sql.= " order by a.S_SEQ DESC limit ".$limit;
    
    $result = sql_query($sql);
    return $result;
  }


  function select_support_list_count($s_type,  $keyword = '', $job = '', $obj = '', $ext2 = ''){
    $sql = "  select count(*) as count ";
    $sql.= " from T_SUPPORT a where a.S_NOTI_YN='Y' and a.S_TYPE = '".$s_type."' ";
    if($keyword){
      $sql.= " and ( a.S_FIELD like '%".$keyword."%' or a.S_EXT3 like '%".$keyword."%' ) ";
    }
    if($job){
      $sql.= " and a.S_JOB like '%".$job."%' ";
    }
    if($obj){
      $sql.= " and a.S_OBJ like '%".$obj."%' ";
    }
    if($ext2){
      $sql.= " and a.S_EXT2 like '%".$ext2."%' ";
    }
    
    $result = sql_fetch($sql);
    return $result['count'];
  }



    // $html .= '<button type="button" class="more-next"><span class="a11y">더 다음으로</span></button>';
 

  
// 한페이지에 보여줄 행, 현재페이지, 총페이지수, URL
function select_paging($write_pages, $cur_page, $total_page, $url, $add="")
{
    //$url = preg_replace('#&amp;page=[0-9]*(&amp;page=)$#', '$1', $url);
    $url = preg_replace('#&amp;page=[0-9]*#', '', $url) . '&amp;page=';

    $str = '';
    if ($cur_page > 1) {
        $str .= '<button type="button" class="more-prev " onclick="javascript:location.href=\''.$url.'1'.$add.'\'"><span class="a11y">더 이전으로</span></button>'.PHP_EOL;
    } else {
      $str .= '<button type="button" class="more-prev"><span class="a11y">더 이전으로</span></button>'.PHP_EOL;
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($start_page > 1) {
      $str .= '<button type="button"  class="prev prev--active" onclick="javascript:location.href=\''.$url.($start_page-1).$add.'\'"><span  class="a11y">이전으로</span></button>'.PHP_EOL;
    }  else {
      $str .= '<button type="button"  class="prev" ><span  class="a11y">이전으로</span></button>'.PHP_EOL;
    }
    
    $str .= '<div class="arrow-number__list">';
    if ($total_page >= 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<button type="button" onclick="javascript:location.href=\''.$url.$k.$add.'\'">'.$k.'</button>'.PHP_EOL;
            else
                $str .= '<button type="button" class="arrow-number--active">'.$k.'</button>'.PHP_EOL;
        }
    }

    $str .= '</div>';

 
    if ($cur_page < $total_page) {
        $str .= '<button type="button" class="next next--active" onclick="javascript:location.href=\''.$url.$total_page.$add.'\'"><span class="a11y">더 다음으로</span></button>'.PHP_EOL;
    } else {
      $str .= '<button type="button" class="next"><span class="a11y">더 다음으로</span></button>'.PHP_EOL;
    }

    if ($total_page > $end_page) {
      $str .= '<button type="button"  class="more-next next--active" onclick="javascript:location.href=\''.$url.($end_page+1).$add.'\'"><span  class="a11y">다음으로</span></button>'.PHP_EOL;
    } else {
      $str .= '<button type="button"  class="more-next " ><span  class="a11y">다음으로</span></button>'.PHP_EOL;
    }


    if ($str)
        return $str;
    else
        return "";
}

function getFullUrl(){
    return $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

function getBaseUrl(){
  return $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
}

?>