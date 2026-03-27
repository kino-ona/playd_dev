<?php
declare(strict_types=1);

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
$docRoot = __DIR__;
$file = $docRoot.$uri;

// 저장소에 없는 w/m 이미지 → 더미 (Docker Apache 와 동일 정책)
if (!is_file($file)) {
    $dummyPng = $docRoot.'/assets/images/dummy/@thum01.png';
    $dummyJpg = $docRoot.'/assets/images/dummy/@banner01.jpg';
    if (preg_match('#^/assets/images/[wm]/.*\.(png|gif|webp|svg)$#i', $uri) && is_file($dummyPng)) {
        header('Content-Type: image/png');
        readfile($dummyPng);

        return true;
    }
    if (preg_match('#^/assets/images/[wm]/.*\.jpe?g$#i', $uri) && is_file($dummyJpg)) {
        header('Content-Type: image/jpeg');
        readfile($dummyJpg);

        return true;
    }
}

if ($uri !== '/' && is_file($file)) {
    if (preg_match('/\.(?:php|html)$/i', $uri)) {
        if (substr(strtolower($file), -5) === '.html') {
            chdir(dirname($file));
            require $file;

            return true;
        }

        return false;
    }
    if (preg_match('/\.(?:png|jpe?g|gif|webp|svg|ico|css|js|woff2?|ttf|eot|mp4|webm|pdf|xml|txt|map)$/i', $uri)) {
        return false;
    }
}

if (is_file($file) && !is_dir($file)) {
    return false;
}

if ($uri === '/' || $uri === '') {
    require $docRoot.'/index.php';

    return true;
}

http_response_code(404);
echo '404 Not Found';

return true;
