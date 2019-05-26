<!-- メッセージの削除画面 -->

<?php

session_start();
require('dbconnect.php');
error_reporting(E_ALL & ~E_NOTICE);

//自分の投稿なのかを確認する
if(isset($_SESSION['id'])){
    //DBから投稿のメンバーidを引く
    $id = $_REQUEST['id'];

    $messages = $db->prepare('SELECT * FROM posts WHERE id=? ');
    $messages->execute(array($id));
    $message = $messages->fetch();

    //$_SESSION['id']と投稿したのメンバーIDが同一か
    if($message['member_id'] == $_SESSION['id']){
        //同一だったら削除の処理
        $del = $db->prepare('DELETE FROM posts WHERE id=?');
        $del->execute(array($id)); 
    }
}

header('Location: index.php');
exit();