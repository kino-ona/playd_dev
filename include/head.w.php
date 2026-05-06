<?
  $title_status = array(
                        'index.html' => 'PLAYD', 
                        'campaign.html' => 'PLAYD | 사업영역 | 캠페인전략', 
                        'creative.html' => 'PLAYD | 사업영역 | 크레이티브', 
                        'media.html' => 'PLAYD | 사업영역 | 미디어전략', 
                        'awards.html' => 'PLAYD | 플레이디 | 수상내역',
                        'contact.html' => 'PLAYD | Contact',
                        'personal.html' => 'PLAYD | 개인정보처리방침',
                        'portfolio.html' => 'PLAYD | 사업영역 | 포트폴리오',
                        'techhub.html' => 'PLAYD | 솔루션 | tech HUB',
                        'letter.html' => 'PLAYD | 인사이트 | 뉴스레터',
                        'letter-detail.html' => 'PLAYD | 인사이트 | 뉴스레터',
                        'report.html' => 'PLAYD | 인사이트 | 리포트',
                        'report-detail.html' => 'PLAYD | 인사이트 | 리포트',
                        'column.html' => 'PLAYD | 인사이트 | 광고컬럼',
                        'column-detail.html' => 'PLAYD | 인사이트 | 광고컬럼',
                        'about.html' => 'PLAYD | 플레이디 | ABOUT',
                        'ir.html' => 'PLAYD | 플레이디 | IR',
                        'ir-detail.html' => 'PLAYD | 플레이디 | IR',
                        'ethical.html' => 'PLAYD | 플레이디 | 윤리경영',
                        'recruit.html' => 'PLAYD | 인재채용 | 채용',
                        'welfare.html' => 'PLAYD | 인재채용 | 복리후생',
                        'cretive.html' => 'PLAYD | 사업영역 | 크리에이티브',
                        'esg.html' => 'PLAYD | 플레이디 | ESG');

?>
<!DOCTYPE html>
<html lang="ko_KR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self' 'unsafe-inline' data: playd.com kt.recruiter.co.kr unpkg.com p.typekit.net use.typekit.net developers.kakao.com  *.facebook.com  connect.facebook.net developer.mozilla.org www.google.com img.stibee.com nsm.co.kr"> -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="keywords" content="PlayD"/>
  <meta name="description" content="디지털 중심 종합광고대행사 플레이디는 고객 성장에 필요한 모든 마케팅 서비스를 제공합니다."/>  
  <meta property="og:locale" content="ko_KR" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="PlayD" />
  <meta property="og:keywords" content="PlayD" />
  <meta property="og:description" content="디지털 중심 종합광고대행사 플레이디는 고객 성장에 필요한 모든 마케팅 서비스를 제공합니다." />
  <meta property="og:url" content="https://www.playd.com" />
  <meta property="og:image" content="../assets/images/common/sns_share.jpg">
  <meta property="og:site_name" content="PlayD" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:description" content="디지털 중심 종합광고대행사 플레이디는 고객 성장에 필요한 모든 마케팅 서비스를 제공합니다." />
  <meta name="twitter:title" content="PlayD" />
  <meta http-equiv="Cache-Control" content="No-Cache"/><!--230202-->
  <link rel="icon" href="../assets/images/common/favicon.png" sizes="32x32" />
  <link rel="icon" href="../assets/images/common/favicon.png" sizes="192x192" />
  <link rel="apple-touch-icon" href="" />
  <!-- webmaster-->
	<span itemscope="" itemtype="http://schema.org/Organization">
	 <link itemprop="url" href="https://www.playd.com">
	 <a itemprop="sameAs" href="https://blog.naver.com/playd_log"></a>
	 <a itemprop="sameAs" href="https://www.instagram.com/playd.official/"></a>
	</span>


	<title><?= $title_status[basename($_SERVER['PHP_SELF'])] ?></title>
	<link rel="stylesheet" href="../assets/css/w/ui.css">
	<link rel="stylesheet" href="//use.typekit.net/gjs0snj.css">
  
  <script src="/static/js/jquery-1.12.4.min.js"></script>
  <script src="/static/js/app.js"></script>

<!-- Head Logscript  Start -------------------------------------------------------------------------------------------------------->	
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '199800123042931'); // Insert your pixel ID here.
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=199800123042931&ev=PageView&noscript=1"
/></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->

<!-- 상단 선언 스크립트 : 모든페이지 공통 상단 필수 -->
<!-- PlayD TERA Log Definition Script Start -->
<script>
(function(win,name){
win['LogAnalyticsObject']=name;
win[name]=win[name]||function(){(win[name].q=win[name].q||[]).push(arguments)}
})(window,'_LA');
</script>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-SWFN2VH49E"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-SWFN2VH49E');
gtag('config', 'AW-10793888998');
</script>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-K9D4DP');</script>
<!-- End Google Tag Manager -->
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K9D4DP"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
  <!-- Head Logscript  End -------------------------------------------------------------------------------------------------------->	
</head>