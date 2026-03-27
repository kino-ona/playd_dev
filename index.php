<?php
$mobile_agent = "/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/";
if(preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])){
    header( 'Location: m/index.html' );
} else {
    header( 'Location: w/index.html' );
}
?>