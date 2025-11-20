<?php
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

session_set_cookie_params([
    'lifetime'=>0,
    'path'=>'/',
    'domain'=>$_SERVER['HTTP_HOST'],
    'secure'=>$secure,
    'httponly'=>true,
    'samesite'=>'Lax'
]);

if(session_status()===PHP_SESSION_NONE) session_start();

$DB_HOST="localhost";
$DB_USER="root";
$DB_PASS="";
$DB_NAME="bookmarks";

$conn = new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);

if($conn->connect_error){
    error_log("DB error: ".$conn->connect_error);
    die("Database connection failed.");
}

$conn->set_charset("utf8mb4");
