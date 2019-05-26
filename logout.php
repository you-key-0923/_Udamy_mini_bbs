<?php

session_start();

//セッションを削除する
$_SESSION = array();
if(ini_set('session.use_cookies')){
    $params = session_get_cookie_params();
    setcookie(session_name() . '', time() - 42000, $params['path'],$params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

//クッキーも削除する
setcookie('email', '',time()-3600);

header('Location: index.php');
eixt();

