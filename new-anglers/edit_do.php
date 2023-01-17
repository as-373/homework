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
  $post_id = $_SESSION['post_id'];
  if(!$post_id){
    header('Location: index.php');
    exit();
  }
  /*$editMessage = filter_input(INPUT_POST, 'editMessage' ,FILTER_SANITIZE_STRING);
  をedit.phpではなくedit_do.phpで行う事に注意  edit_do.phpで受け取る
  何時間もそれで悩んでいた
  */
  $error = [];//
  if($_SERVER['REQUEST_METHOD'] === 'POST'){

  $editMessage = filter_input(INPUT_POST, 'editMessage' ,FILTER_SANITIZE_STRING);
  if($editMessage === ''){
    $error['editMessage'] = 'blank';
  }
  $editFishName = filter_input(INPUT_POST, 'editFishName' ,FILTER_SANITIZE_STRING);
  if($editFishName === ''){
    $error['editFishName'] = 'blank';
  }
  $editSize = filter_input(INPUT_POST, 'editSize' ,FILTER_SANITIZE_STRING);
  if($editSize === ''){
    $error['editSize'] = 'blank';
  }
  $editDate = filter_input(INPUT_POST, 'editDate' ,FILTER_SANITIZE_STRING);
  if($editDate === ''){
    $error['editDate'] = 'blank';
  }
  $editLocation = filter_input(INPUT_POST, 'editLocation' ,FILTER_SANITIZE_STRING);
  if($editLocation === ''){
    $error['editLocation'] = 'blank';
  }
  //var_dump($editMessage);
  $_SESSION['error'] = $error;//
  
  if(empty($error)){//
    $db = dbconnect();
    $stmt = $db->prepare('update posts set message=?, fish_name=?, size=? ,date=?, location=? where id=?');
    if(!$stmt){
      die($db->error);
    }

    $stmt->bind_param('ssissi',$editMessage,$editFishName,$editSize,$editDate,$editLocation,$post_id);
    $success = $stmt->execute();
    if(!$success){
      die($db->error);
    }
    //var_dump($editMessage);
    header('Location:index.php');
    exit();
  }else{
    $url = "edit.php?id=?".$post_id;//これをしないと edit.php?id= ではなくurlのidが無いedit.phpに飛び、上手く動作しなくなる
    header('Location:' .$url);
    exit();
  }
}
?>