<?php
session_start();
require('library.php');
//初期化
$fishName = '';
$date = '';
$location = '';
$error = [];
//セッション情報があればログインできる
if(isset($_SESSION['id']) && isset($_SESSION['name'])){
  $id = $_SESSION['id'];
  $name = $_SESSION['name'];
}else{
  header('Location: login.php');
  exit();
}

$db = dbconnect();

//メッセージの投稿
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  
  $message = filter_input(INPUT_POST, 'message' ,FILTER_SANITIZE_STRING);
  if($message === ''){
    $error['message'] = 'blank';
  }
  $fishName = filter_input(INPUT_POST, 'fishName' ,FILTER_SANITIZE_STRING);
  if($fishName === ''){
    $error['fishName'] = 'blank';
  }
  $size = filter_input(INPUT_POST, 'size' ,FILTER_SANITIZE_STRING);
  if($size === ''){
    $error['size'] = 'blank';
  }
  $date = filter_input(INPUT_POST, 'date' ,FILTER_SANITIZE_STRING);
  if($date === ''){
    $error['date'] = 'blank';
  }
  $location = filter_input(INPUT_POST, 'location' ,FILTER_SANITIZE_STRING);
  if($location === ''){
    $error['location'] = 'blank';
  }

  //投稿画像の取得
  $post_image = $_FILES['post_image'];
  if($post_image['name'] !== '' && $post_image['error'] === 0){
    $type = mime_content_type($post_image['tmp_name']);
    //var_dump($type);
    if($type !== 'image/png' && $type !== 'image/jpeg'){
        $error['post_image'] = 'type';
    }
  }

  if(empty($error)){
    //画像のアップロード
    if($post_image['name'] !==''){
        $filename = date('YmdHis').'_'.$post_image['name'];
        if(!move_uploaded_file($post_image['tmp_name'],'post_picture/'.$filename)){
          die('ファイルのアップロードに失敗しました');
        }
    }else{
      $filename = '';
    }

    $stmt = $db->prepare('insert into posts (message,member_id,fish_name,size,date,location,picture) values(?,?,?,?,?,?,?)');
    if(!$stmt){
      die($db->error);
    }
  
    $stmt->bind_param('sisisss',$message,$id,$fishName,$size,$date,$location,$filename);
    $success = $stmt->execute();
    if(!$success){
      die($db->error);
    }
    header('Location: index.php');
    exit();
  }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ANGLERS</title>
    <link rel="shortcut icon" href="img/title-img.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<div id="wrap">
    <div id="head">
        <img src="img/title-img.png" alt="">
        <h1>ANGLERS</h1>
    </div>
    <div id="content">
        <div class="logout" style="text-align: right"><a href="logout.php">ログアウト</a></div>
        <form action="" method="post" enctype="multipart/form-data">
                <p><?php echo h($name); ?>さん、メッセージをどうぞ</p><br>

                <div class="post-content">
                    <label for="fishName">釣れた魚</label>
                    <input type = "text" name = "fishName" value="<?php echo h($fishName,ENT_QUOTES); ?>">
                    <?php if(isset($error['fishName']) && $error['fishName'] === 'blank'): ?>
                      <p class="error">釣れた魚を入力してください</p>
                    <?php endif; ?>
                    <br>
                </div>

                <div class="post-content">
                    <label for="size">サイズ</label>
                    <input type = "number" name = "size" value="<?php echo h($size,ENT_QUOTES); ?>">
                    <?php if(isset($error['size']) && $error['size'] === 'blank'): ?>
                      <p class="error">サイズを入力してください</p>
                    <?php endif; ?>
                    <br>
                </div>

                <div class="post-content">
                    <label for="date">釣行日</label>
                    <input class="fishing-day" type = "date" name = "date" value="<?php echo h($date,ENT_QUOTES); ?>">
                    <?php if(isset($error['date']) && $error['date'] === 'blank'): ?>
                      <p class="error">サイズを入力してください</p>
                    <?php endif; ?>
                    <br>
                </div>

                <div class="post-content">
                    <label for="location">釣れた場所</label>
                    <input type = "text" name = "location" value="<?php echo h($location,ENT_QUOTES); ?>">
                    <?php if(isset($error['location']) && $error['location'] === 'blank'): ?>
                      <p class="error">釣れた場所を入力してください</p>
                    <?php endif; ?>
                    <br>
                </div>

                <div class="post-content">
                    <label>画像</label>
                    <input class="file" type="file" name="post_image" size="35" value="test"/>
                    <?php if(isset($error['post_image']) && $error['post_image'] === 'type'): ?>
                        <p class="error">* 写真などは「.png」または「.jpg」の画像を指定して下さい</p>
                        <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
                    <?php endif; ?>
                    <br>
                </div>

                <div class="post-content">
                    <label for="message">感想</label><br><br>
                    <textarea name="message" cols="50" rows="5"></textarea>
                    <?php if(isset($error['message']) && $error['message'] === 'blank'): ?>
                      <p class="error">感想を入力してください</p>
                    <?php endif; ?>
                </div>
            
                <div class="button">
                      <p><input class="submit" type="submit" value="投稿する"/></p>
                </div>
        </form>
        <?php $stmt = $db->prepare('select p.id, p.member_id, p.message, p.created, p.fish_name, p.size, p.date,p.location,p.picture, m.name, m.picture from posts p, members m where m.id=p.member_id order by id desc');
        if(!$stmt){
          die($db->error);
        }
        $success = $stmt->execute();
        if(!$success){
          die($db->error);
        }

        $stmt->bind_result($id,$member_id,$message,$created,$fishName,$size,$date,$location,$post_picture,$name,$picture);
        ?>
      <h2 class="post-head">みんなの釣果</h2>
      <div id="msg-flex">
        <?php while($stmt->fetch()):?>
        <div class="msg">
            <?php if($picture): //pictureが存在していれば?>
                <img class="user-img" src="member_picture/<?php echo h($picture); ?>" width="48" height="48" alt=""/>
            <?php endif; ?>
            <p class="day"><a href="view.php?id=<?php echo h($id); ?>"><?php echo h($created); ?><span class="name">（<?php echo h($name); ?>）</span></a>
            <?php if($_SESSION['id'] === $member_id):?>
                [<a href="delete.php?id=<?php echo h($id); ?>" style="color: #F33;">削除</a>]
                [<a href="edit.php?id=<?php echo h($id); ?>" style="color: #F33;">編集</a>]
            <?php endif; ?>
            </p>
            <br><br>
            
            <div class="flex">
              <div class="flex-item">
                <?php if($post_picture): ?>
                  <p><img class="post-picture" src="post_picture/<?php echo h($post_picture); ?>" width="200" alt="" /></p>
					      <?php endif; ?>
              </div>
              <div class="flex-item">
                <p class="item">魚種：<?php echo h($fishName); ?></p>
                <p class="item">サイズ：<?php echo h($size); ?>cm</p>
                <p class="item">釣行日：<?php echo h($date); ?></p>
                <p class="item">場所：<?php echo h($location); ?></p>
                <p class="item">感想：</p>
                <p><?php echo h($message); ?></p>
              </div>
            </div>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>

<footer id="footer">
  <p>&copy;ANGLERS</p>
</footer>
</body>

</html>