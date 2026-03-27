<script src="https://nhnent.github.io/tui.editor/api/latest/lib/jquery/dist/jquery.js"></script>
<script src='https://nhnent.github.io/tui.editor/api/latest/lib/markdown-it/dist/markdown-it.js'></script>
<script src="https://nhnent.github.io/tui.editor/api/latest/lib/to-mark/dist/to-mark.js"></script>
<script src="https://nhnent.github.io/tui.editor/api/latest/lib/tui-code-snippet/dist/tui-code-snippet.js"></script>
<script src="https://nhnent.github.io/tui.editor/api/latest/lib/codemirror/lib/codemirror.js"></script>
<script src="https://nhnent.github.io/tui.editor/api/latest/lib/highlightjs/highlight.pack.js"></script>
<script src="https://nhnent.github.io/tui.editor/api/latest/lib/squire-rte/build/squire-raw.js"></script>

<script src="/plugin/tui.editor-1.2.6/dist/lib/tui-color-picker/dist/tui-color-picker.js"></script>
<script src="/plugin/tui.editor-1.2.6/dist/tui-editor-Viewer.js"></script>
<script src="/plugin/tui.editor-1.2.6/dist/tui-editor-Editor.js"></script>
<script src="/plugin/tui.editor-1.2.6/dist/tui-editor-extColorSyntax.js"></script>
<script src="/plugin/tui.editor-1.2.6/dist/tui-editor-extScrollSync.js"></script>

<link rel="stylesheet" href="https://nhnent.github.io/tui.editor/api/latest/lib/codemirror/lib/codemirror.css">
<link rel="stylesheet" href="https://nhnent.github.io/tui.editor/api/latest/lib/highlightjs/styles/github.css">

<link rel="stylesheet" href="/plugin/tui.editor-1.2.6/dist/tui-editor.css">
<link rel="stylesheet" href="/plugin/tui.editor-1.2.6/dist/tui-editor-contents.css">
<link rel="stylesheet" href="/plugin/tui.editor-1.2.6/dist/lib/tui-color-picker/dist/tui-color-picker.css">
<?php
function editor_html($id, $content, $is_dhtml_editor=true)
{
    static $js = true;
    
    $html = "";
   
    $text = str_replace(array("\r\n","\r"),'\n',$content);
    
    if ($is_dhtml_editor && $js) {
        $html .= "\n"."<div id='msg_contents'></div>";
        $html .= "\n"."<script>var editor = new tui.Editor({";
        $html .= "\n"."el: document.querySelector('#msg_contents'),";
        $html .= "\n"."initialEditType: 'wysiwyg',";
        $html .= "\n"."initialValue: '".$text."',";
        $html .= "\n"."previewStyle: 'vertical',";
        $html .= "\n"."height: '500px',";
        $html .= "\n"."exts: ['colorSyntax', 'scrollSync'],";
        $html .= "\n"."language: 'ko'";
        $html .= "\n"."});";
        $html .= "\n</script>";
        $js = false;
    }

    $html .= "\n<textarea id=\"$id\" name=\"$id\" maxlength=\"65536\" style=\"width:100%;height:400px;display:none;\">$content</textarea>";

    return $html;
}

function editor_html_view($content)
{
    $html = "";
   
    $text = str_replace(array("\r\n","\r","\n"),'',$content);
    $text = str_replace(array("<br/>"),'\n',$text);

    $html .= "\n"."<script>var editor = tui.Editor.factory({";
    $html .= "\n"."el: document.querySelector('.bbs_view_cont'),";
    $html .= "\n"."viewer: true,";
    $html .= "\n"."height: '500px',";
    $html .= "\n"."initialValue: $('#cont_none').text()";
    $html .= "\n"."});";
    $html .= "\n</script>";

    return $html;
}

// textarea 로 값을 넘긴다. javascript 반드시 필요
function get_editor_js($id, $is_dhtml_editor=true)
{
    if ($is_dhtml_editor) {
        return "document.getElementById('{$id}').value = editor.getValue();\n";
    } else {
        return "var {$id}_editor = document.getElementById('{$id}');\n";
    }
}

//  textarea 의 값이 비어 있는지 검사
function chk_editor_js($id, $is_dhtml_editor=true)
{
    if ($is_dhtml_editor) {
        return "if (!{$id}_editor_data || jQuery.inArray({$id}_editor_data.toLowerCase(), ['&nbsp;','<p>&nbsp;</p>','<p><br></p>','<p></p>','<br>']) != -1) { alert(\"내용을 입력해 주십시오.\"); oEditors.getById['{$id}'].exec('FOCUS'); return false; }\n";
    } else {
        return "if (!{$id}_editor.value) { alert(\"내용을 입력해 주십시오.\"); {$id}_editor.focus(); return false; }\n";
    }
}

/*
https://github.com/timostamm/NonceUtil-PHP
*/

if (!defined('FT_NONCE_UNIQUE_KEY'))
    define( 'FT_NONCE_UNIQUE_KEY' , sha1($_SERVER['SERVER_SOFTWARE'].G5_MYSQL_USER.session_id().G5_TABLE_PREFIX) );

if (!defined('FT_NONCE_SESSION_KEY'))
    define( 'FT_NONCE_SESSION_KEY' , substr(md5(FT_NONCE_UNIQUE_KEY), 5) );

if (!defined('FT_NONCE_DURATION'))
    define( 'FT_NONCE_DURATION' , 60 * 60 ); // 300 makes link or form good for 5 minutes from time of generation,  300은 5분간 유효, 60 * 60 은 1시간

if (!defined('FT_NONCE_KEY'))
    define( 'FT_NONCE_KEY' , '_nonce' );

// This method creates a key / value pair for a url string
if(!function_exists('ft_nonce_create_query_string')){
    function ft_nonce_create_query_string( $action = '' , $user = '' ){
        return FT_NONCE_KEY."=".ft_nonce_create( $action , $user );
    }
}

if(!function_exists('ft_get_secret_key')){
    function ft_get_secret_key($secret){
        return md5(FT_NONCE_UNIQUE_KEY.$secret);
    }
}

// This method creates an nonce. It should be called by one of the previous two functions.
if(!function_exists('ft_nonce_create')){
    function ft_nonce_create( $action = '',$user='', $timeoutSeconds=FT_NONCE_DURATION ){

        $secret = ft_get_secret_key($action.$user);

        session_register('token_'.FT_NONCE_SESSION_KEY, $secret);

		$salt = ft_nonce_generate_hash();
		$time = time();
		$maxTime = $time + $timeoutSeconds;
		$nonce = $salt . "|" . $maxTime . "|" . sha1( $salt . $secret . $maxTime );
		return $nonce;

    }
}

// This method validates an nonce
if(!function_exists('ft_nonce_is_valid')){
    function ft_nonce_is_valid( $nonce, $action = '', $user='' ){

        $secret = ft_get_secret_key($action.$user);

        $token = $_SESSION['token_'.FT_NONCE_SESSION_KEY];

        if ($secret != $token){
            return false;
        }

		if (is_string($nonce) == false) {
			return false;
		}
		$a = explode('|', $nonce);
		if (count($a) != 3) {
			return false;
		}
		$salt = $a[0];
		$maxTime = intval($a[1]);
		$hash = $a[2];
		$back = sha1( $salt . $secret . $maxTime );
		if ($back != $hash) {
			return false;
		}
		if (time() > $maxTime) {
			return false;
		}
		return true;
    }
}

// This method generates the nonce timestamp
if(!function_exists('ft_nonce_generate_hash')){
    function ft_nonce_generate_hash(){
		$length = 10;
		$chars='1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
		$ll = strlen($chars)-1;
		$o = '';
		while (strlen($o) < $length) {
			$o .= $chars[ rand(0, $ll) ];
		}
		return $o;
    }
}
?>