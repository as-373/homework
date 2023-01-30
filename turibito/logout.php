<?php
  session_start();

  //ログアウト処理　
  unset($_SESSION['id']);
  unset($_SESSION['name']);

  header('Location: join\home.php');
  exit();
?>