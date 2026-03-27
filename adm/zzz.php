<?php
include_once('./common.php');
include_once('./head.sub.php');
// 자바스크립트 변수 php로 테스트
?>
<script>
// $(function() {
     // if(window.hasOwnProperty("android")) {
         // var device_info = JSON.parse(window.android.getdeviceInfo());
     // } else {
         // window.location = "jscall://getdeviceInfo";
         // var device_info = (ios_data) ? ios_data : {"appVersion" : "test", "DeviceOS" : "test", "DeviceId" : "test", "DeviceModel" : "test", "OSVer" : "test", "TokenId" : "test"};
     // }
     // console.log(ios_data);
     // alert(device_info.DeviceId);
     // document.write(device_info.DeviceId);
// });
</script>
<?php
echo '<script>
      if(window.hasOwnProperty("android")) {
          var device_info = JSON.parse(window.android.getdeviceInfo());
      } else {
          window.location = "jscall://getdeviceInfo";
          var device_info = (ios_data) ? ios_data : {"appVersion" : "test", "DeviceOS" : "test", "DeviceId" : "test", "DeviceModel" : "test", "OSVer" : "test", "TokenId" : "test"};
      }
      </script>';
$appVersion  = '<script>document.write(device_info.appVersion);</script>';
$DeviceOS    = '<script>document.write(device_info.DeviceOS);</script>';
$DeviceId    = '<script>document.write(device_info.DeviceId);</script>';
$DeviceModel = '<script>document.write(device_info.DeviceModel);</script>';
$OSVer       = '<script>document.write(device_info.OSVer);</script>';
$TokenId     = '<script>document.write(device_info.TokenId);</script>';

echo $TokenId;
include_once('./tail.sub.php');
exit;

include_once(Y1_LIB_PATH.'/thumbnail.lib.php');
echo '<form method="post" enctype="multipart/form-data"><input type="file" name="test"><button type="submit">ddd</button></form>';
// 이미지 썸네일 테스트
print_r($_FILES['test']);

$ym = date('ym');
$data_sv_dir = '/'.Y1_DATAFILES_DIR.'/review/'.$ym.'';
$data_dir    = Y1_DATAFILES_PATH.'/review/'.$ym.'';
$data_url    = 'http://'.$_SERVER['HTTP_HOST'].'/dataFiles/review/'.$ym.'';

@mkdir($data_dir, Y1_DIR_PERMISSION);
@chmod($data_dir, Y1_DIR_PERMISSION);
        
// move_uploaded_file($_FILES['mb_img']['tmp_name'], $dest_path);
// chmod($dest_path, Y1_FILE_PERMISSION);

// jpg 또는 png 파일 적용
echo $data_dir."<br>";
echo $thumb = thumbnail($_FILES['test']['name'], $_FILES['test']['tmp_name'], $data_dir, "300", "300", true, true);

exit;

// 메일 테스트
include_once(Y1_LIB_PATH.'/mailer.lib.php');
mailer("테스트", "rhwk389@naver.com", "oio0@nate.com", "테스트 입니다.", "테스트 입니다.", 1);
exit;


// 회원가입 복수선택 처리 테스트
$food = "5,9,11,99";
$food_name = "ㅇㅇ";

$food_arr = explode(",", $food);
print_r($food_arr);

foreach($food_arr as $v) {
    echo $v.'<br>';
}

exit;


// 주소 좌표변환 테스트
$data =  search_address("대구 성당동 208-7");
print_r2($data);
echo $data->documents[0]->road_address->x;
exit;



// 암호화 테스트
// echo string_to_hashtag_content("A", "하하하 블라블라 ####잡담ㅇ2, #태그, #추출 #흠냐리#이어짐 #abcd 테스트입니다");
function String2Hex($string){
    $hex='';
    for ($i=0; $i < strlen($string); $i++){
        $hex .= dechex(ord($string[$i]));
    }
    return $hex;
}

function Hex2String($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}

function test($string){
    $hex='';
    for ($i=0; $i < strlen($string); $i++){
        $hex .= decbin(ord($string[$i]));
    }
    return $hex;
}

function test2($hex){
    $string='';
    $string = (bindec($hex));
    // for ($i=0; $i < strlen($hex)-1; $i+=2){
        // $string .= chr(bindec($hex[$i]));
    // }
    return $string;
}

echo test("010-6686-8385테스트입니다.가!!택시라마바사");
// echo test2("110000110001110000101101110110110110111000110110101101111000110011111000110101111011011000010110001100111011001000101010100100111011011000101010111000111011001001111010000101111010111000101110001000111010111000101110100100101110111010101011000010000000100001100001111011011000001110011101111011001000101110011100111010111001110110111100111010111010011110001000111010111011000010010100111011001000001010101100");
exit;


function encrypt_data($data, $size, $key, $mode=0) {
    $XOR_MASKING_VALUE = "This's Korea";
    $XOR_MASKING_SIZE  = 12;
    
    $hash = 2015;
    
    if($mode == 0) {
        $decode_size = mb_strlen($data, "utf-8")."?";
    }
    
    if($mode == 1) {
        // $data = (($data - $hash) >> 5);
        // return $data;
        $data = Hex2String($data);
    }
    
	if($key < 0) $key = -$key;

	if($size > $XOR_MASKING_SIZE)
	{
		$key %= ($size - $XOR_MASKING_SIZE);
		
		for($i=0; $i<$XOR_MASKING_SIZE; ++$i, ++$key)
            $data[$key] = $data[$key] ^ $XOR_MASKING_VALUE[$i];
			// $data[$key] ^= $XOR_MASKING_VALUE[$i];
	}
    if($mode == 0) {
        $data = String2Hex($data);
        $data = (($data << 5));
    }
    
    return $decode_size.$data;
    
    // if($mode == 0)
        // return $data;
    // else if($mode == 1)
        // return Hex2String($data);
}

function decode_data($data, $size, $key) {
	return encrypt_data($data, $size, $key, 1);
}
// $file = fopen("data.txt", "w");
// fwrite($file, encrypt_data("010-6686-8385테스트입니다.가!!택시라마바사", 27, 1919)); 
// fclose($file);

// echo Hex2String("ec9588eb8595ed9598ec84b8ec9a94");
echo(encrypt_data("010-6686-8385테스트입니다.가!!택시라마바사", 29, 1919));
echo "<br>";
echo "<br>";
echo "<br>";
echo(decode_data("3031302d363638362d38333835edd1e485f9839eaaf383ece08a8b88eb8ba42eeab0802121ed839dec8b9ceb9dbceba788ebb094ec82ac", 29, 1919));
echo "<br>";
echo "<br>";
echo "<br>";
echo(Hex2String("3031302d363638362d38333835edd1e485f9839eaaf383ece08a8b88eb8ba42eeab0802121ed839dec8b9ceb9dbceba788ebb094ec82ac"));
/*
define("MAX_CRYPT_TABLE_SIZE", 16);
define("MAX_TABLE_DATA_SIZE", 256);
define("CRYPT_TYPE", 8);
global $m_nTableSize = 0;
global $m_nTotalSize = 4096;
define("CRYPTION_KEY", "0x35867361");

function load() {
	if($m_nTotalSize == 0)
		return false;

	$m_nTableSize = $m_nTotalSize / MAX_TABLE_DATA_SIZE;
	if($m_nTableSize > MAX_CRYPT_TABLE_SIZE)
		return false;
}

function rand_key() {
    $n = (1103515245 * $m_dwRandom + 12345);
    $m_dwRandom = ($n % 2147483648);     // 2^32
    return $m_dwRandom;
}

function GetRandKey() {
    global $m_nTableSize;
    return (rand_key() % $m_nTableSize);
}


function encrypt_data2($data)
{
    load();
    
	$key;
	$iEncryptSize = 0;
	$iPtr = 0;
	$index = 0;

	// 랜덤키 생성
	$key_key1 = GetRandKey();
	$key_key2 = GetRandKey();
	$key_key3 = GetRandKey();
	$key_key4 = GetRandKey();
	$key_index = rand_key() % MAX_TABLE_DATA_SIZE;
	$key_type = CRYPT_TYPE;

	random.SeedMT(key.dwKey);


	// 암호화할 길이를 구한다.
	$iEncryptSize = strlen($data);

	$index = $key_index;

	for($i=0; $i<$iEncryptSize; $i++)
	{
		// 치환할 값을 rand 함수에서 만든다.

		*((DWORD*)(data->m_Buffer + iPtr)) = *((DWORD*)(data->m_Buffer + iPtr)) ^ key.dwKey ^ random.RandomMT();

		$index++;
		$iPtr += 4;
	}

	data->m_wMsgType = (WORD)(data->m_wMsgType ^ key.dwKey ^ CRYPTION_KEY);
	data->m_Key.dwKey = key.dwKey;
}
*/



exit;

/* api Test */
$url = 'http://27.101.28.22/api/village';

// $data = array (
            // 'q' => 'nokia'
        // );
        
$params = '';
foreach($data as $key=>$value)
    $params .= $key.'='.$value.'&';

$params = trim($params, '&');

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url.'?'.$params ); //Url together with parameters
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 7); //Timeout after 7 seconds
curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
curl_setopt($ch, CURLOPT_HEADER, 0);
    
$res = curl_exec($ch);
curl_close($ch);

$tt = json_decode($res, true);
// var_dump($tt);
// print_r($tt['선남']);
// echo $res['선남']['sys_code'];
// for($i=0; $row=$tt['선남']; $i++){
    // echo $row['sys_code']."<br>";
// 

foreach($tt['선남'][0] as $k=> $v)
    echo $k.$v;
?>