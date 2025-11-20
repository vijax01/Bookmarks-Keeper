<?php
include "./partials/connection.php";

header("Content-Type: application/json");

// 1. Read Google code from URL (?code=....)
$code = $_GET['code'] ?? '';
if ($code === '') {
    echo json_encode(['ok'=>false,'msg'=>'no code found']);
    exit;
}

// 2. Google client details
$client_id     = "1057071399510-0l23e2bbmjqsioft56366qhs47nl3dad.apps.googleusercontent.com";
$client_secret = "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";
$redirect_uri  = "http://localhost/php_projects/Bookmarks/google-login.php";

// 3. Exchange code → access token
$tokenUrl = "https://oauth2.googleapis.com/token";
$postData = http_build_query([
    'code' => $code,
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'redirect_uri' => $redirect_uri,
    'grant_type' => 'authorization_code'
]);

$ch = curl_init($tokenUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$token = json_decode($response, true);
if (empty($token['access_token'])) {
    echo json_encode(['ok'=>false,'msg'=>'token exchange failed']);
    exit;
}

// 4. Fetch user info
$ch = curl_init("https://www.googleapis.com/oauth2/v2/userinfo");
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer ".$token['access_token']]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$userInfo = json_decode(curl_exec($ch), true);
curl_close($ch);

$gid  = $userInfo['id'] ?? '';
$email = $userInfo['email'] ?? '';
$name  = $userInfo['name'] ?? '';
$pic   = $userInfo['picture'] ?? '';

if (!$gid) {
    echo json_encode(['ok'=>false,'msg'=>'userinfo failed']);
    exit;
}

// 5. Save / update user in DB
$stmt = $conn->prepare("INSERT INTO users (google_id, email, name, picture)
VALUES (?, ?, ?, ?)
ON DUPLICATE KEY UPDATE email=VALUES(email), name=VALUES(name), picture=VALUES(picture)");
$stmt->bind_param("ssss", $gid, $email, $name, $pic);
$stmt->execute();

// 6. Load user for session
$q = $conn->prepare("SELECT user_id, name, picture FROM users WHERE google_id=?");
$q->bind_param("s", $gid);
$q->execute();
$user = $q->get_result()->fetch_assoc();

// 7. Set session
$_SESSION['user_id'] = $user['user_id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_picture'] = $user['picture'];

session_regenerate_id(true);

// 8. Redirect to home
header("Location: index.php");
exit;
