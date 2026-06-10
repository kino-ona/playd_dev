<?php
include_once('./_common.php');

// 현재 위치 위,경도
$radius  = ($_POST['radius'])  ? trim($_POST['radius'])  : 1000;
$now_lat = ($_POST['now_lat']) ? trim($_POST['now_lat']) : "35.832415";
$now_lng = ($_POST['now_lng']) ? trim($_POST['now_lng']) : "128.556782";

// 현재 위치 정보
// $now_loc_data  = coord2address($now_lat, $now_lng);
// $now_addr      = $now_loc_data->documents[0]->address->address_name;
// $now_road_addr = $now_loc_data->documents[0]->road_address->address_name;

$i=0;
$sql = " select *,
                get_distance('{$now_lat}', '{$now_lng}', lat, lng) as loc
           from {$y1['store_info_table']}
          where get_distance('{$now_lat}', '{$now_lng}', lat, lng) <= '{$radius}'
            and service_state = '3'
       order by loc ";
$res = sql_query($sql);
while($row=sql_fetch_array($res)) {
    $list[$i]  = $row;
    $loc_m[]   = $row['loc'];
    $point_x[] = $row['lat'];
    $point_y[] = $row['lng'];
    
    $i++;
}

$y1['title'] = '내주변';
$y1['nav_cls'] = 'navSurr';
include_once('./_head.php');
?>
<section class="loc_set_wrap">
    <div class="map_radius">
        <button type="button" onclick="surround_sort_list(this);"><?=$radius?>m</button>
        <div class="map_radius_choice">
            <button type="button" onclick="surround_sort_sel(this, 100);">100m</button>
            <button type="button" onclick="surround_sort_sel(this, 300);">300m</button>
            <button type="button" onclick="surround_sort_sel(this, 500);">500m</button>
            <button type="button" onclick="surround_sort_sel(this, 1000);">1000m</button>
            <button type="button" onclick="surround_sort_sel(this, 3000);">3000m</button>
        </div>
        <div class="black_wall" id="radius_black_wall" onclick="radius_black_wall_out(this);"></div>
    </div>
    <div class="loc_search">
        <button type="button" onclick="surround_loc_search(this);">위치 검색</button>
        <div class="loc_search_inner">
            <div class="_top">
                <p class="tit">위치검색</p>
                <button type="button" class="close" onclick="surround_loc_close();">닫기</button>
            </div>
            
            <form name="fwrite" id="fwrite" method="get">
            <input type="hidden" name="lat" id="lat" value="<?php echo $now_lat ?>">
            <input type="hidden" name="lng" id="lng" value="<?php echo $now_lng ?>">
            
            <!--<input type="text" class="inp_mini readonly" name="zipcode" id="zipcode" placeholder="우편번호" required>
            <input type="button" class="inp_btn" onclick="execDaumPostcode()" value="찾기">-->
            
            <div id="wrap" style="display:none;border:1px solid;width:100%;height:300px;margin:5px 0;position:relative">
            </div>
            
            <!--<input type="text" class="readonly" name="addr1" id="addr1" placeholder="주소">
            <input type="text" name="addr2" id="addr2" placeholder="상세주소">-->
            
            <script>
            // 우편번호 찾기 찾기 화면을 넣을 element
            var element_wrap = document.getElementById('wrap');

            function foldDaumPostcode() {
                // iframe을 넣은 element를 안보이게 한다.
                element_wrap.style.display = 'none';
            }

            function execDaumPostcode() {
                // 현재 scroll 위치를 저장해놓는다.
                var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
                new daum.Postcode({
                    oncomplete: function(data) {
                        // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                        // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                        // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                        var fullAddr = data.address; // 최종 주소 변수

                        // 주소 좌표 input push
                        var geocoder = new daum.maps.services.Geocoder();

                        geocoder.addressSearch(fullAddr, function (result, status) {
                            if (status === daum.maps.services.Status.OK) {
                                $("#lat").val(result[0].address.y);
                                $("#lng").val(result[0].address.x);

                                // iframe을 넣은 element를 안보이게 한다.
                                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                                element_wrap.style.display = 'none';

                                // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                                document.body.scrollTop = currentScroll;
                            
                                $("#fwrite").submit();
                            }
                        });
                    },
                    // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
                    onresize : function(size) {
                        element_wrap.style.height = size.height+'px';
                    },
                    width : '100%',
                    height : '100%'
                }).embed(element_wrap);

                // iframe을 넣은 element를 보이게 한다.
                element_wrap.style.display = 'block';
            }
            </script>
            
            <!--<div class="_bot">
                <button type="submit" class="btn">검색하기</button>
            </div>-->
            
            </form>
        </div>
        <div class="black_wall" id="loc_black_wall" onclick="surround_loc_close();"></div>
    </div>
</section>
<section class="loc_add_wrap">
    <button type="button" onclick="surround('<?=$radius?>');" id="addr_name"><script>returnAddressName('addr_name', '<?=$now_lat?>', '<?=$now_lng?>');</script></button>
</section>
<section class="map_wrap">
    <div class="map_area" id="map"></div>
    <div class="store_list swiper-container">
        <div class="swiper-wrapper">
            <?php
            for ($j=0; $j<count($list); $j++) {
                // 좋아요 수
                $sql_like = " select count(*) as cnt
                                from {$y1['user_pick_store_table']}
                               where store_no = '{$list[$j]['store_no']}' ";
                $row_like = sql_fetch($sql_like);
                
                $icon_img_url = ($list[$j]['icon_img_url']) ? $list[$j]['icon_img_url'] : Y1_NOIMG_STORE;
            ?>
            <article class="swiper-slide" onclick="location.href='./store_view?store_no=<?=$list[$j]['store_no']?>'">
                <div class="img">
                    <img src="<?=$icon_img_url?>">
                </div>
                <div class="txt">
                    <p class="likeit_num"><?=$row_like['cnt']?></p>
                    <p class="name_wrap"><span class="num"><?=($j+1)?></span><span class="name"><?=$list[$j]['store_disp_nm']?></span></p>
                    <p class="address"><?=$list[$j]['addr1']?> <?=$list[$j]['addr2']?></p>
                    <div class="tag">
                        <?php
                        // 해시태그 정보
                        $sql_hash = " select *
                                        from {$y1['store_recom_table']}
                                       where store_no = '{$list[$j]['store_no']}'                                    
                                    order by reg_dttm ";
                        $res_hash = sql_query($sql_hash);
                        while($row_hash=sql_fetch_array($res_hash)) {
                            echo '<p>'.$row_hash['rec_word'].'</p>';
                        }
                        ?>
                    </div>
                </div>
            </article>
            <?php
            }
            ?>
        </div>
    </div>
    <script>
    function map_reload(radius_data) {
        //===========================================================================
        // Store Swiper
        //===========================================================================
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 'auto',
            centeredSlides: true,
        });
        
        swiper.on('slideChangeTransitionEnd', function () {
            var mk_idx = swiper.realIndex;
            var mk_pos = markers[swiper.realIndex].getPosition();
            var mk_lat = mk_pos['jb'];
            var mk_lng = mk_pos['ib'];
            moveToDarwin(mk_lat, mk_lng, mk_idx);
        });
        
        swiper.update();

        //===========================================================================
        // KAKAO Maps
        //===========================================================================
        var mapContainer = document.getElementById('map'), // 지도를 표시할 div 
            mapOption = { 
                center: new daum.maps.LatLng(<?=$now_lat?>, <?=$now_lng?>), // 지도의 중심좌표
                level: 4 // 지도의 확대 레벨
            };

        // 지도를 표시할 div와  지도 옵션으로  지도를 생성합니다
        var map = new daum.maps.Map(mapContainer, mapOption);
        
        // 지도에 표시된 마커 객체를 가지고 있을 배열입니다
        var markers = [];
        
        // 버튼을 클릭하면 아래 배열의 좌표들이 모두 보이게 지도 범위를 재설정합니다
        var points = [
            <?php
            for($i=0; $i<count($loc_m); $i++) {
                $points_txt .= 'new daum.maps.LatLng('.$point_x[$i].', '.$point_y[$i].'),';
            }
            echo substr($points_txt, 0, -1);
            ?>
        ];
        
        // 지도를 재설정할 범위정보를 가지고 있을 LatLngBounds 객체를 생성합니다
        var bounds = new daum.maps.LatLngBounds();

        var imageSrc_blue = '../images/common/marker_blue.png', // 마커이미지의 주소입니다
            imageSrc_red  = '../images/common/marker_red.png', // 마커이미지의 주소입니다
            imageSize     = new daum.maps.Size(25, 40), // 마커이미지의 크기입니다
            imageOption   = {offset: new daum.maps.Point(25, 40)}; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.
       
        // 현재위치 마커 이미지
        var imageSrc_mark    = '../images/surr/map_mark.png', // 마커이미지의 주소입니다
            imageSize_mark   = new daum.maps.Size(34, 34), // 마커이미지의 크기입니다. 
            imageOption_mark = {offset: new daum.maps.Point(34, 34)}; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.
       
        // 마커의 이미지정보를 가지고 있는 마커이미지를 생성합니다
        var markerImage_blue = new daum.maps.MarkerImage(imageSrc_blue, imageSize, imageOption);
        var markerImage_red  = new daum.maps.MarkerImage(imageSrc_red, imageSize, imageOption);
        var markerImage_mark = new daum.maps.MarkerImage(imageSrc_mark, imageSize_mark, imageOption_mark);
        
        var i, marker;
        for (i = 0; i < points.length; i++) {
            // 배열의 좌표들이 잘 보이게 마커를 지도에 추가합니다
            addMarker(points[i], markerImage_blue, i);
           
            // LatLngBounds 객체에 좌표를 추가합니다
            // bounds.extend(points[i]);
        }
        
        // 현재위치 마커
        addMarker(new daum.maps.LatLng(<?=$now_lat?>, <?=$now_lng?>), markerImage_mark, 99999);

        // 지도에 표시할 원을 생성합니다
        var circle = new daum.maps.Circle({
            center : new daum.maps.LatLng(<?=$now_lat?>, <?=$now_lng?>),  // 원의 중심좌표 입니다 
            radius: radius_data, // 미터 단위의 원의 반지름입니다 
            strokeWeight: 0, // 선의 두께입니다 
            strokeColor: '#F6CEF5', // 선의 색깔입니다
            strokeOpacity: 0, // 선의 불투명도 입니다 1에서 0 사이의 값이며 0에 가까울수록 투명합니다
            strokeStyle: 'solid', // 선의 스타일 입니다
            fillColor: '#F6CEF5', // 채우기 색깔입니다
            fillOpacity: 0.4  // 채우기 불투명도 입니다   
        }); 

        // 지도에 원을 표시합니다
        circle.setMap(map);

        // 원을 포함하는 최소의 사각형 영역을 구한다.
        var circle_bound = circle.getBounds();
        var sw = new daum.maps.LatLng(circle_bound['ja'], circle_bound['da']),
            ne = new daum.maps.LatLng(circle_bound['ka'], circle_bound['ia']);

        // LatLngBounds 객체에 좌표를 추가합니다
        bounds.extend(sw);
        bounds.extend(ne);

        setBounds();
        
        function setBounds() {
            // LatLngBounds 객체에 추가된 좌표들을 기준으로 지도의 범위를 재설정합니다
            // 이때 지도의 중심좌표와 레벨이 변경될 수 있습니다
            // map.setBounds(bounds);
        }
        
        // 마커를 생성하고 지도위에 표시하는 함수입니다
        function addMarker(position, markerImage, i) {
            // 마커를 생성합니다
            var marker = new daum.maps.Marker({
                position: position,
                image: markerImage
            });
            
            // 마커가 지도 위에 표시되도록 설정합니다
            marker.setMap(map);
            
            // 생성된 마커를 배열에 추가합니다
            markers.push(marker);
            
            // 첫번째 데이터 마커 표시
            if(i == "0") {
                markers[i].setImage(markerImage_red);
            }
            
            // 마커에 click 이벤트를 등록합니다
            daum.maps.event.addListener(marker, 'click', function() {
                var mak_pos = marker.getPosition();
                var mak_lat = mak_pos['jb'];
                var mak_lng = mak_pos['ib'];
                moveToDarwin(mak_lat, mak_lng, i);
                swiper.slideTo(i, 500);
            });
        }
        
        // 배열에 추가된 마커들을 지도에 표시하거나 삭제하는 함수입니다
        function setMarkers(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }
        
        // "마커 보이기" 버튼을 클릭하면 호출되어 배열에 추가된 마커를 지도에 표시하는 함수입니다
        function showMarkers() {
            setMarkers(map)    
        }

        // "마커 감추기" 버튼을 클릭하면 호출되어 배열에 추가된 마커를 지도에서 삭제하는 함수입니다
        function hideMarkers() {
            setMarkers(null);
        }
        
        // 좌표로 부드럽게 이동 및 마커 이미지 변경
        function moveToDarwin(x, y, idx) {
            var	loc = new daum.maps.LatLng(x, y);
            map.panTo(loc);
            
            for (var i = 0; i < markers.length; i++) {
                // 현재위치 마커는 예외처리
                if (i == markers.length-1) continue;
                
                if (i == idx)
                    markers[i].setImage(markerImage_red);
                else
                    markers[i].setImage(markerImage_blue);
            }
            
            $("html").animate({
                scrollTop: 0
            }, 300);
        }
    }

    $(function() {
        map_reload('<?=$radius?>');
    });
    </script>
</section>
<?php
include_once('./_tail.php');
?>