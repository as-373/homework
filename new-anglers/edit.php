<?php
session_start();
require('library.php');
//セッション情報があればログインできる
if(isset($_SESSION['id']) && isset($_SESSION['name'])){
  $id = $_SESSION['id'];
  $name = $_SESSION['name'];
}else{
  header('Location: login.php');
  exit();
}

$post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$_SESSION['post_id'] = $post_id;

if(isset($_SESSION['error'])){
    $error = [];
    $error = $_SESSION['error'];
}

$db = dbconnect();

//編集画面にもとのmessageを表示

$stmt = $db->prepare('select message, fish_name, size, date, location from posts where id=?');
if(!$stmt){
  die($db->error);
}
$stmt->bind_param('i', $post_id);
$stmt->execute();
$stmt->bind_result($message,$fishName,$size,$date,$location);
$stmt->fetch();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>編集画面</title>
    <link rel="shortcut icon" href="img/title-img.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css"/>
</head>

<body>
<div id="wrap">
    <div id="head">
        <img src="img/title-img.png" alt="">
        <h1>編集画面</h1>
    </div>
    <div id="edit-content">
        <form action="edit_do.php" method="post">
            <dl>
                <dt><?php echo h($name);?>さん、メッセージをどうぞ</dt>
                <dd>
                    <label for="editFishName">釣れた魚</label><br>
                    <input type = "text" name = "editFishName" value="<?php echo h($fishName);?>">
                    <?php if(isset($error['editFishName']) && $error['editFishName'] === 'blank'): ?>
                        <p class="error">釣れた魚を入力してください</p>
                    <?php endif; ?>
                    <br>
                    <label for="editSize">サイズ</label><br>
                    <input type = "number" name = "editSize" value="<?php echo h($size);?>">
                    <?php if(isset($error['editSize']) && $error['editSize'] === 'blank'): ?>
                        <p class="error">サイズを入力してください</p>
                    <?php endif; ?>
                    <br>
                    <label for="editDate">釣行日</label><br>
                    <input class="fishing-day" type = "date" name = "editDate" value="<?php echo h($date);?>">
                    <?php if(isset($error['editDate']) && $error['editDate'] === 'blank'): ?>
                        <p class="error">釣行日を入力してください</p>
                    <?php endif; ?>
                    <br>
                    <label for="editLocation">場所</label><br>
                    <input type = "text" name = "editLocation" value="<?php echo h($location);?>">
                    <?php if(isset($error['editLocation']) && $error['editLocation'] === 'blank'): ?>
                        <p class="error">場所を入力してください</p>
                    <?php endif; ?>
                    <br>
                    <label for="editMessage">感想</label><br>
                    <textarea name="editMessage" cols="50" rows="5"><?php echo h($message);?></textarea>
                    <?php if(isset($error['editMessage']) && $error['editMessage'] === 'blank'): ?>
                        <p class="error">感想を入力してください</p>
                    <?php endif; ?>
                    <div class="button">
                        <p><input class="submit" type="submit" value="編集する"/></p>
                    </div>
                </dd>
            </dl>
        </form>
    </div>
</div>
</body>
</html>