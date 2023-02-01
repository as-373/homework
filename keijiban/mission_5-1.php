<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_1-21</title>
</head>
<body>
    
    <?php

    
    //データベースへ接続
    $dsn = 'mysql:dbname=tb240307db;host=localhost';
    $user = 'tb-240307';
    $password = 'KRZ9mS99fy';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
 
 /*//テーブル削除
    $sql = 'DROP TABLE tb_2';
    $stmt = $pdo->query($sql);
*/
    
    $sql = "CREATE TABLE IF NOT EXISTS tb_2"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"//投稿番号
        . "name char(32),"//名前
        . "comment TEXT,"// ,いる  コメント
        . "compass TEXT,"// ,いらない　パスワード
        . "date DATE"//日時
        .");";
        $stmt = $pdo->query($sql);
        
        
    //変数に代入
    $date = date("Y/m/d/ H:i:s");
  //送信ボタンが押されたとき
  if(!empty($_POST['submit'])) {
    $name = $_POST['name'];
    $comment = $_POST['comment'];
    $compass = $_POST['compass'];
    $editnum = $_POST['editnum'];
  }

  //削除ボタンが押されたとき
  if(!empty($_POST['delete'])) {
    $delete = $_POST['delete'];
    $delpass = $_POST['delpass'];
  }

  //編集ボタンが押されたとき
  if(!empty($_POST['edit'])) {
    $edit = $_POST['edit'];
    $editnum = $_POST['editnum'];
    $editpass = $_POST['editpass'];
  }

   //削除機能
if(!empty($delete)) {  
    //データの抽出
    $sql = 'SELECT * FROM tb_2';
    $stmt = $pdo->query($sql);//データベースにsql文を発行してデータを取得
    $results = $stmt->fetchAll();
    
    foreach ($results as $row){
        //投稿番号(id)が$delete(削除したい投稿番号)と同じとき ＊これをしないとパスワードが違いますが何度も繰り返される
        if($delete == $row['id']){
            if ($delpass === $row['compass']){
               //削除のコード
                $sql = 'delete from tb_2 where id=:id';//自分で直した部分
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
                $stmt->execute();
                echo "削除成功";
            }else{
                echo "パスワードが違います<br>";
                //echo $row['compass'];
            }
        }
    }
}

  //編集機能  ＊編集ボタン押すと投稿フォームに編集した番号の名前とコメントが表示される
  if(!empty($edit)) {
    //データの抽出
    $sql = 'SELECT * FROM tb_2';
    $stmt = $pdo->query($sql);//データベースにsql文を発行してデータを取得
    $results = $stmt->fetchAll();

    foreach($results as $row) {
        
        if($edit == $row['id']){
            if($editpass == $row['compass']) {
                $editname_form = $row['name'];
                $editcom_form = $row['comment'];
        
            } else {
                echo "パスワードが違います";
            }
        }
    }
 }

 
 
 //名前とコメントとパスワードがあるなら
 if(!empty($name) && !empty($comment) && !empty($compass)){
    //echo $editnum;
  //編集番号が送信されたなら編集モード
  if(!empty($editnum)) {
      //echo "ああああ";
      $sql = 'UPDATE tb_2 SET name=:name,comment=:comment,compass=:compass,date=:date WHERE id=:id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
      $stmt->bindParam(':compass', $compass, PDO::PARAM_STR);
      $stmt->bindParam(':date', $date, PDO::PARAM_STR);
      $stmt->bindParam(':id', $editnum, PDO::PARAM_INT);//$editは値が無くなるのでダメだよ
      $stmt->execute();

  //編集番号が送信されてないなら追記モード
  }else{    
      //echo "いいいい";
      $sql = $pdo -> prepare("INSERT INTO tb_2 (name, comment, compass, date) VALUES (:name, :comment, :compass, :date)");
      $sql -> bindParam(':name', $name, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
      $sql -> bindParam(':compass', $compass, PDO::PARAM_STR);
      $sql -> bindParam(':date', $date, PDO::PARAM_STR);
      $sql -> execute();
  }
  
}

?>
<h1>掲示板</h1>

<form action="" method="post">
    
【投稿フォーム】<br>
  <input type="text" name="name"
  placeholder="名前" value="<?php if(!empty($editname_form)){echo $editname_form;}?>">
  
  <br>
  <input type="text" name="comment"
   placeholder="コメント" value="<?php if(!empty($editcom_form)){echo $editcom_form;} ?>">
   <input type="hidden" name="editnum" placeholder=""
   value="<?php if(!empty($edit)){echo $edit;} ?>">
   <br>
   <input type="text" name="compass" placeholder="パスワード">
   <input type="submit" name="submit" >
   <br>
   <hr>
   
【削除フォーム】<br>
  <input type="text" name="delete" placeholder="削除対象番号">
   <br>
   <input type="text" name="delpass" placeholder="パスワード">
   <input type="submit" name="submit" value="削除" >
   <br>
   <hr>
    
【編集フォーム】<br>
   <input type="text" name="edit" placeholder="編集対象番号">
   <br>
   <input type="text" name="editpass" placeholder="パスワード">
   <input type="submit" name="submit" value="編集">
  <hr>
</form>

  <?php
    //ブラウザに表示
    echo "【投稿内容】<br>";
    $sql = 'SELECT * FROM tb_2';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].' ';
        echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['compass'].' ';
        echo $row['date'].'<br>';
    }
    
    
    
  ?>


        
    
        

        
</body>
</html>
        

        
