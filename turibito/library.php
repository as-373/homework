<?php
//htmlspecialcharsを短くする
  function h($value){
      return htmlspecialchars($value,ENT_QUOTES);
  }

  //dbへの接続
  function dbconnect(){
    $db = new mysqli('localhost', '●●●', '●●●', '●●●');
    if(!$db){
      die($db->error);
    }
    return $db;
  }
?>
