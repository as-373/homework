<?php
session_start();
require('library.php');

//初期化忘れずに!
$error = [];
$email ='';
$password = '';

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //echo 'submit'; submitボタン押した時に正確に動作するかの確認
    $email = filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST,'password',FILTER_SANITIZE_STRING);
    //var_dump($email); メールが受け取れているかの確認
    if($email === '' || $password === ''){
      $error['login'] = 'blank';
    }else{
      //ログインのチェック
      $db =dbconnect();
      $stmt = $db->prepare('select id, name, password from members where email=? limit 1');
      if(!$stmt){
        die($db->error);
      }

      $stmt->bind_param('s',$email);
      $success = $stmt->execute();
      if(!$success){
        die($db->error);
      }

      $stmt->bind_result($id,$name,$hash);//prepareのid name passwordを$id,$name,$hashに代入
      $stmt->fetch();

      //$passwordと$hashが同じか確認  
      if(password_verify($password,$hash)){
        //ログイン成功
        session_regenerate_id();//idを再生成
        $_SESSION['id'] = $id;
        $_SESSION['name'] =$name;
        header('Location: index.php');
        exit();
      }else{
        $error['login'] = 'failed';
      }
    }
  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="shortcut icon" href="img/title-img.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <title>ログインする</title>
</head>
<body>
<div id="wrap">
    <div id="head">
        <img src="img/title-img.png" alt="">
        <h1>ログインする</h1>
    </div>
    <div id="login-content">
        <div id="lead">
            <p>メールアドレスとパスワードを記入してログインしてください。</p>
            <p>入会手続きがまだの方はこちらからどうぞ。</p>
            <p>&raquo;<a href="join/">入会手続きをする</a></p>
        </div>
        <form action="" method="post">
            <dl>
                <dt>メールアドレス</dt>
                <dd>
                    <input type="text" name="email" size="35" maxlength="255" value="<?php echo h($email); ?>"/>
                    <?php if(isset($error['login']) && $error['login'] === 'blank'): ?>
                        <p class="error">* メールアドレスとパスワードをご記入ください</p>
                    <?php endif; ?>
                    <?php if(isset($error['login']) && $error['login'] === 'failed'): ?>
                        <p class="error">* ログインに失敗しました。正しくご記入ください。</p>
                    <?php endif; ?>
                </dd>
                <dt>パスワード</dt>
                <dd>
                    <input type="password" name="password" size="35" maxlength="255" value="<?php echo h($password); ?>"/>
                </dd>
            </dl>
            <div class="button">
                <input class="submit" type="submit" value="ログインする"/>
            </div>
        </form>
    </div>
</div>
</body>
</html>