<?php
declare(strict_types=1);

$docRoot = realpath(__DIR__) ?: __DIR__;
$_SERVER['DOCUMENT_ROOT'] = $docRoot;

$rawPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = urldecode(is_string($rawPath) ? $rawPath : '/');
if ($uri === '' || ($uri[0] ?? '') !== '/') {
    $uri = '/'.$uri;
}
if (str_contains($uri, "\0") || str_contains($uri, '..')) {
    http_response_code(400);
    echo 'Bad Request';

    return true;
}

$file = $docRoot.$uri;
$isFile = is_file($file);

// 저장소에 m 전용 이미지가 없을 때: w 쪽 동일·유사 경로로 대체 (로컬/스테이징용)
if (!$isFile && str_starts_with($uri, '/assets/images/m/')) {
    $rel = substr($uri, strlen('/assets/images/m/'));
    $base = basename($uri);
    $try = [];
    if (str_starts_with($rel, 'index/block_')) {
        $try[] = $docRoot.'/assets/images/w/visual/'.substr($rel, strlen('index/'));
    }
    $try[] = $docRoot.'/assets/images/w/'.$rel;
    $try[] = $docRoot.'/assets/images/w/common/icons/'.$base;
    $try[] = $docRoot.'/assets/images/w/sub/'.$base;
    $try[] = $docRoot.'/assets/images/w/common/'.$base;
    $try[] = $docRoot.'/assets/images/w/visual/'.$base;
    foreach ($try as $p) {
        if (is_file($p)) {
            $file = $p;
            $isFile = true;
            break;
        }
    }
}

$dummyPng = $docRoot.'/assets/images/dummy/@thum01.png';
$dummyJpg = $docRoot.'/assets/images/dummy/@banner01.jpg';

if (!$isFile) {
    if (preg_match('#^/assets/images/(?!dummy/).+\.(png|gif|webp|svg)$#i', $uri) && is_file($dummyPng)) {
        header('Content-Type: image/png');
        readfile($dummyPng);

        return true;
    }
    if (preg_match('#^/assets/images/(?!dummy/).+\.jpe?g$#i', $uri) && is_file($dummyJpg)) {
        header('Content-Type: image/jpeg');
        readfile($dummyJpg);

        return true;
    }
    if ($uri === '/favicon.ico' && is_file($dummyPng)) {
        header('Content-Type: image/png');
        readfile($dummyPng);

        return true;
    }
}

if ($uri === '/' || $uri === '') {
    require $docRoot.'/index.php';

    return true;
}

if ($isFile && !is_dir($file)) {
    $lc = strtolower($file);

    if (str_ends_with($lc, '.php')) {
        return false;
    }

    if (str_ends_with($lc, '.html')) {
        chdir(dirname($file));
        require $file;

        return true;
    }

    if (preg_match('/\.(?:png|jpe?g|gif|webp|svg|ico|css|js|mjs|woff2?|ttf|eot|mp4|webm|pdf|xml|txt|map|json)$/i', $uri)) {
        $mime = match (strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
            'css' => 'text/css; charset=UTF-8',
            'js', 'mjs' => 'application/javascript; charset=UTF-8',
            'json', 'map' => 'application/json; charset=UTF-8',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'pdf' => 'application/pdf',
            'xml' => 'application/xml',
            'txt' => 'text/plain; charset=UTF-8',
            default => 'application/octet-stream',
        };
        header('Content-Type: '.$mime);
        $len = filesize($file);
        if ($len !== false) {
            header('Content-Length: '.$len);
        }
        readfile($file);

        return true;
    }

    header('Content-Type: application/octet-stream');
    $len = filesize($file);
    if ($len !== false) {
        header('Content-Length: '.$len);
    }
    readfile($file);

    return true;
}

http_response_code(404);
echo '404 Not Found';

return true;
