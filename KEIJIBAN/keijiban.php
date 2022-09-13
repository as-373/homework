<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>掲示板</title>
</head>
<body>
    
    <header>
    
        <h1 class="title">掲示板</h1>
    
    </header>
    
    <?php
    //完成系  右上の青いphpマークで実行　そして右クリックでphp server stopする
        //未定義エラー回避
        $editComment="";//編集ボタン押したあと、投稿フォームのコメント欄に表示される値を格納する変数
        $editName="";//編集ボタンを押したあと、投稿フォームの名前の欄に表示される値を格納する変数
        $id="";//新規投稿か編集かを判断するために使用　　idありなら編集　idなしなら新規投稿
        $name = "";//名前
        $comment = "";//コメント
        $text="";//投稿内容に表示するテキスト(名前　コメント　日時など)
        $password="";//パスワード
        $delete = "";//削除したい番号
        $edit="";//編集したい番号
        
        //エラーメッセージ消すためif
        if(isset($_POST["name"])){
            $name = $_POST["name"];
        }
        if(isset($_POST["comment"])){
            $comment = $_POST["comment"];
        }
        if(isset($_POST["delete"])){
            $delete = $_POST["delete"];
        }
        if(isset($_POST["edit"])){
            $edit = $_POST["edit"];
        }
        if(isset($_POST["id"])){
            $id = $_POST["id"];
        }
        if(isset($_POST["password"])){
            $password = $_POST["password"];
        }
        if(isset($_POST["delete_password"])){
            $delete_password = $_POST["delete_password"];
        }
        if(isset($_POST["edit_password"])){
            $edit_password = $_POST["edit_password"];
        }
        
        $date = date("Y年m月d日 H時i分s秒");//日時
        $filename="keijiban.txt";//ファイル
        $lines = file($filename,FILE_IGNORE_NEW_LINES);//ファイルを配列へ

        
        //投稿フォーム　新規投稿or編集
        if(!empty($name && $comment && $password)){
            
            //idあり　新規投稿する
            if(empty($id)){
                
                //削除後に新規投稿する際の投稿番号のズレの修正かつ投稿番号の取得
                $n = count($lines);
                for($i = 0; $i < count($lines); $i++){
                        $line = explode("<>",$lines[$i]);
                }
                if($n!=0){//前の番号+1
                    $n=intval($line[0]) + 1;
                }
                else{
                    $n = 1;
                }
                
                $text = $n."<>".$name."<>".$comment."<>".$date."<>".$password;
                $fp = fopen($filename,"a");
                fwrite($fp, $text.PHP_EOL);
                fclose($fp);
            }
            //idなし　編集する
            else{
                $fp = fopen($filename,"w");
                for($i = 0; $i < count($lines); $i++){
                        
                    $line = explode("<>",$lines[$i]);
                    $count = $line[0];
                      
                    if($count == $id){
                                
                        $line[1]=$name;//名前の編集
                        $line[2]=$comment;//コメントの編集
                        $line[4]=$password;//パスワードの編集
                                
                    }
                    if(count($line)>4){
                        $text = $line[0]."<>".$line[1]."<>".$line[2]."<>".$line[3]."<>".$line[4];
                    }
                    fwrite($fp, $text.PHP_EOL);
                }
                fclose($fp);
            }
        }
        //名前とコメントを投稿フォームに表示
        else if(!empty($edit && $edit_password)){
            
            for($i = 0; $i < count($lines); $i++){
                $line = explode("<>",$lines[$i]);
                if($line[0]==$edit){
                    break;//削除したい投稿番号を取得してループ抜ける
                }
            }
            $correct_password = $line[4];//指定した投稿番号の正しいパスワードの値の取得
                
            if($edit_password == $correct_password){
                            
                //echo "編集内容";
                           
                $fp = fopen($filename,"w");//削除の時はwであることに注意
                for($i = 0; $i < count($lines); $i++){
                    $line = explode("<>",$lines[$i]);
                    $count = $line[0];
                              
                    if($count == $edit){
                        $editName = $line[1];
                        $editComment = $line[2];
                    }
                    if(count($line)>4){
                        $text = $line[0]."<>".$line[1]."<>".$line[2]."<>".$line[3]."<>".$line[4];
                    }
                    fwrite($fp, $text.PHP_EOL);
                    }
                fclose($fp);
                }
        }
        //削除
        else if(!empty($delete && $delete_password)){
            //削除したい投稿番号のパスワード取得
            for($i = 0; $i < count($lines); $i++){
                $line = explode("<>",$lines[$i]);
                if($line[0]==$delete){
                    break;//削除したい投稿番号を取得してループ抜ける
                }
            }
            $correct_password = $line[4];//指定した投稿番号の正しいパスワードの値の取得
            
            //パスワードが正しいかどうか
            if($delete_password == $correct_password){
                //削除の処理(前のミッションと同じ)
                $fp = fopen($filename,"w");//削除の時はwであることに注意
                for($i = 0; $i < count($lines); $i++){
                    
                    $line = explode("<>",$lines[$i]);
                    $count = $line[0];
                        
                    if($count != $delete){
                            fwrite($fp, $lines[$i].PHP_EOL);
                    }
                }
                fclose($fp);
            }
        }
        
    ?>
    
    <div class="form">
        【 投稿フォーム 】
        <form action="" method="post">
            <label>
            名前　　　：
            <input type="text" name="name" placeholder="名前" value= "<?php if(!empty($_POST["edit"])){echo $editName; } ?>">
            </label>
            
            <br>
            
            <label>
            コメント　：
            <input type="text" name="comment" placeholder="コメント" value= "<?php if(!empty($_POST["edit"])){echo $editComment; } ?>">
            <input type="hidden" name="id" value="<?php if(!empty($_POST["edit"])){echo $_POST["edit"];}else{echo "";} ?>">
            </label>
            
            <br>
            
            <label>
            パスワード：
                <input type="text" name="password" placeholder="パスワード">
            </label>
            <br>
            
            <input class="button" type="submit" name="submit" value="送信">
        </form>
    </div>
    
    <br>
    
    <div class="form">
        【 削除フォーム 】
        <form action="" method="post">
            <label>
            削除番号　：
            <input type="number" name="delete" placeholder="削除したい投稿番号">
            </label>
            <br>
            
            <label>
            パスワード：
                <input type="text" name="delete_password" placeholder="パスワード">
            </label>
            <br>
            
            <input class="button" type="submit" name="submit" value="削除">
        </form>
    </div>
    
    <br>
    
    <div class="form">
        【 編集フォーム 】
        <form action="" method="post">
            <label>
            編集番号　：
            <input type="number" name="edit" placeholder="編集したい番号" value= "<?php if(!empty($_POST["edit"])){echo $_POST["edit"];}else{echo "";} ?>">
            </label>
            <br>
            
            <label>
            パスワード：
                <input type="text" name="edit_password" placeholder="パスワード">
            </label>
            <br>
            
            <input class="button" type="submit" name="submit" value="編集">
        </form>
    </div>
    
    <br>
    
    <h2 class="post-content">投稿内容</h2>
    
    <br>

    <div class="posts-list">
    <?php
        //エラーメッセージ消すためif isset
        //テキストファイルをブラウザでの表示
        //echo "<投稿内容><br>";
        if(isset($filename)){
            if(file_exists($filename)){
                $lines = file($filename,FILE_IGNORE_NEW_LINES);//配列で読み込む
                foreach($lines as $line){
                    $lin = explode("<>",$line);
                    //エラーメッセージ消すためにif文用いる　要素数が4つより大きい場合
                    if(count($lin)>4){
                        echo "<P>".$lin[0].". ".$lin[1]." ".$lin[2]." ".$lin[3]."</P>";
                    }
                }
            }
        }
    ?>
    </div>

  

</body>
</html>
        
   