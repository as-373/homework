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
$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
if(!$id){
  header('Location: index.php');
  exit();
}
$db = dbconnect();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TURIBITO</title>
    <link rel="shortcut icon" href="img/title-img.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css"/>
</head>

<body>
<div id="wrap">
    <div id="head">
        <div class="head-flex">
          <img src="/img/title-img.jpg" alt="">
          <h1>TURIBITO</h1>
        </div>
    </div>
    <div id="content">
        <p>&laquo;<a href="index.php">一覧にもどる</a></p>
        <?php $stmt = $db->prepare('select p.id, p.member_id, p.message, p.created, p.fish_name, p.size, p.date, p.location, p.picture, m.name, m.picture from posts p, members m where p.id=? and  m.id=p.member_id order by id desc');
        if(!$stmt){
            die($db->error);
        }
        $stmt-> bind_param('i', $id);
        $success = $stmt->execute();
        if(!$success){
            die($db->error);
        }
        $stmt->bind_result($id, $member_id, $message, $created, $fishName, $size, $date, $location, $post_picture, $name, $picture);

        if($stmt->fetch())://fetchで取得したら 今回は1件のみ取得するのでwhileでなくif使用
        ?>
        <div class="msg">
            <?php if($picture): //pictureが存在していれば?>
                <img class="user-img" src="member_picture/<?php echo h($picture); ?>" width="48" height="48" alt=""/>
            <?php endif; ?>
            <p class="day"><a href="view.php?id=<?php echo h($id); ?>"><?php echo h($created); ?><span class="name">（<?php echo h($name); ?>）</span></a>
            <br><br><br>

            <div class="flex">
              <div class="flex-item">
                <?php if($post_picture): ?>
                  <p><img class="post-picture" src="post_picture/<?php echo h($post_picture); ?>" width="200" alt="" /></p>
				        <?php endif; ?>
              </div>
              <div class="flex-item">
                <p>魚種：<?php echo h($fishName); ?></p>
                <p>サイズ：<?php echo h($size); ?>cm</p>
                <p>日付：<?php echo h($date); ?></p>
                <p>場所：<?php echo h($location);?></p>
                <p>感想：<br><?php echo h($message); ?></p>
              </div>
            </div>
          </div>
                <!-- ここにGoogle Mapを表示する -->
                <div id="map" class="map"></div>
                <!-- APIキーを指定してjsファイルを読み込む -->
                <script async src="https://maps.googleapis.com/maps/api/js?key=●●●"></script>

                <script type="text/javascript">
                // Google Mapを表示する関数
                function initMap() {
                  const geocoder = new google.maps.Geocoder();
                  // ここでaddressのvalueに住所のテキストを指定する
                  geocoder.geocode( { address: '<?php echo $location; ?>'}, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                      const latlng = {
                        lat: results[0].geometry.location.lat(),
                        lng: results[0].geometry.location.lng()
                      }
                      const opts = {
                        zoom: 15,
                        center: new google.maps.LatLng(latlng)
                      }
                      const map = new google.maps.Map(document.getElementById('map'), opts)
                      new google.maps.Marker({
                        position: latlng,
                        map: map 
                      })
                    } else {
                      console.error('Geocode was not successful for the following reason: ' + status)
                    }
                  })
                }
                </script>
        
        <!--取得できなかったら-->
        <?php else:?>
        <p>その投稿は削除されたか、URLが間違えています</p>
        <?php endif; ?>

    </div>
</div>
</body>
</html>
