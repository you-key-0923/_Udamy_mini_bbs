<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);

require('../dbconnect.php');

//$_POSTが空じゃなかった時に、下記のerrorチェックを走らせる
	//***--「!」付けると逆になる。$_POSTが空じゃなかったら、になる。
if(!empty($_POST)){
	//???--なんで$errorは配列なの？ てかそもそも意味がわからない。$error['name'] = 'blank'
	if($_POST['name'] === ''){
		$error['name'] = 'blank';
	}
	if($_POST['email'] === ''){
		$error['email'] = 'blank';
	}
	if(strlen($_POST['password']) < 4){
		$error['password'] = 'length';
	}
	if($_POST['password'] === ''){
		$error['password'] = 'blank';
	}
	$fileName = $_FILES['image']['name'];
	if(!empty($fileName)){
		//ファイル名の後ろ３文字を取り出して
		$ext = substr($fileName, -3);
		//jpgかgifかpngじゃなかったら、
		if($ext != 'jpg' && $ext != 'gif' && $ext != 'png' ){
			$error['image'] = 'type';
		}
	}

	//アカウントの重複チェック
	//???--エラーでない・・・。デバッグの仕方を聞く！
	if(empty($error)){
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if($record['cut'] > 0){
			$error['email'] = 'duplicate';
		}
	}

	//errorがなかったら、「check.php」にジャンプする
	//empty→()内が空か確認する
	if(empty($error)){
		//画像のファイル名を作成・・20190524151722filename.jpg
		$image = date('YmdHis') . $_FILES['image']['name'];
		//(今ある場所→保管先)
		move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;
		header('Location: check.php');
		exit();
	}
}
//check.phpで「書き直す」ボタンを押して戻ってきたときの処理
//最初に開いたのか、戻ったのかを判別するために、戻った場合にはURLパラメータつけてる「index.php?action=rewrite」
//パラメータついてたら、最初にPOSTした内容を表示してちょ！って処理
//???--['action']どっから急に出てきたよ？
if($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])){
	$_POST = $_SESSION['join'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>

	<link rel="stylesheet" href="../style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>会員登録</h1>
</div>

<div id="content">
<p>次のフォームに必要事項をご記入ください。</p>
<!-- ファイルをアップロードする場合は、「enctype="multipart/form-data」をつける -->
<form action="" method="post" enctype="multipart/form-data">
	<dl>
		<dt>ニックネーム<span class="required">必須</span></dt>
		<dd>
			<input type="text" name="name" size="35" maxlength="255" value="<?php echo $_POST['name']; ?>" />
			<?php if($error['name'] === 'blank'): ?>
			<p class="error">※ニックネームを入力してください</p>
			<?php endif; ?>
		</dd>
		<dt>メールアドレス<span class="required">必須</span></dt>
		<dd>
			<input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['email'],ENT_QUOTES)); ?>" />
			<?php if($error['email'] === 'blank'): ?>
			<p class="error">※メールアドレスを入力してください</p>
			<?php endif; ?>
			<?php if($error['email'] === 'duplicate'): ?>
			<p class="error">※指定されたメールアドレスは、既に登録されています</p>
			<?php endif; ?>
		<dt>パスワード<span class="required">必須</span></dt>
		<dd>
			<input type="password" name="password" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['password'],ENT_QUOTES)); ?>" />
			<?php if($error['password'] === 'blank'): ?>
			<p class="error">※パスワードを入力してください</p>
			<?php endif; ?>
			<?php if($error['password'] === 'length'): ?>
			<p class="error">※パスワードは4文字以上で入力してください</p>
			<?php endif; ?>
        </dd>
		<dt>写真など</dt>
		<dd>
			<input type="file" name="image" size="35" value="test"  />
			<?php if($error['image'] === 'type'): ?>
			<p class="error">※写真などは「.gif」または「.jpg」または「.png」の画像を指定してください</p>
			<?php endif; ?>
			<?php if(!empty($error)): ?>
			<p class="error">※恐れ入りますが再度画像を指定してください</p>
			<?php endif; ?>
        </dd>
	</dl>
	<div><input type="submit" value="入力内容を確認する" /></div>
</form>
</div>
</body>
</html>
