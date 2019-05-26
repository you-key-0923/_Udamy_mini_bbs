<?php
$host = "localhost";
$user = "root";
$password = "1234";
$name = "mini_bbs";
$dsn = "mysql:host={$host}; dbname={$name}; charset=utf8";

try{
  $db = new PDO($dsn,$user,$password);
    }catch(PDOException $e){
  echo 'DB接続エラー:' . $e->getMessage();
}
