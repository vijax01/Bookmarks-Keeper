<?php
if (empty($_GET['u'])) exit;

$url = urldecode($_GET['u']);
$host = parse_url($url, PHP_URL_HOST) ?? '';

if (strpos($host, 'googleusercontent.com') === false) exit;

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT => 8,
    CURLOPT_USERAGENT => "Mozilla/5.0", // important
]);
$body = curl_exec($ch);
$ctype = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

if (!$body || stripos($ctype, 'image/') !== 0) {
    header("Content-Type: image/png");
    readfile("./assets/default-avatar.svg");
    exit;
}

header("Content-Type: ".$ctype);
echo $body;
