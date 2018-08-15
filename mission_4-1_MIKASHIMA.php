    		<?php
			date_default_timezone_set('Asia/Tokyo');//場所
			$now_hour = date("Y/m/d H:i:s");//年、月、時間、分
		?>
		<?php
			$dsn = 'データベース名';  //データベース接続
			$user = 'ユーザー名'; 
			$password = 'パスワード'; 
			$pdo = new PDO($dsn,$user,$password);
			$sql= "CREATE TABLE user" //テーブル作成
			." (" 
			. "id INT NOT NULL AUTO_INCREMENT," //IDカラム 自動連番
			. "name char(32)," //名前カラム
			. "comment TEXT," //コメントカラム,TEXT＝長さが決まっていない長文
			. "created_at TIMESTAMP,"//時間カラム
			. "pass char(30),"//パスワードカラム,varchar=字数制限 15文字以内
			. "PRIMARY KEY (id)"
			.");"; 
			$stmt = $pdo->query($sql);

		?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>掲示板</title>
    </head>
    <body>
    		<?php
			if(!empty($_POST["name"]) and !empty($_POST["comment"]) and !empty($_POST["editcomment"]) and !empty($_POST["password1"])){//もし名前とコメントと編集コメントが空白でない状態で送信されたら
				$dsn = 'データベース名';  //データベース接続
				$user = 'ユーザー名'; 
				$password = 'パスワード'; 
				$pdo = new PDO($dsn,$user,$password);
				$id = $_POST['editcomment']; //変数に編集番号を挿入
  				$nm = $_POST["name"];//送信された名前を代入
  				$kome = $_POST["comment"]; //送信されたコメントを代入
  				$pass = $_POST["password1"];//送信されたパスワード
  				$sql = "update user set name='$nm' , comment='$kome' , pass='$pass' where id='$id' AND pass='$pass' order by id='$id' asc";//パスワードとIDから抽出し、送信された名前とコメントとパスワードに編集する。
  				$result = $pdo->query($sql);
			}elseif(!empty($_POST["name"]) and !empty($_POST["comment"]) and !empty($_POST["password1"])){//もし名前とコメントとパスワードが空白でない状態で送信されたら
				$dsn = 'データベース名';  //データベース接続
				$user = 'ユーザー名'; 
				$password = 'パスワード'; 
				$pdo = new PDO($dsn,$user,$password);
				$id = $row['id'];
				$sql = $pdo -> prepare("INSERT INTO user (name, comment, created_at, pass) VALUES (:name, :comment,:created_at,:pass)");//送信された名前とコメントと時間とパスワードを挿入
				$sql -> bindParam(':name', $name, PDO::PARAM_STR); 
				$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
				$sql -> bindParam(':created_at', $created_at, PDO::PARAM_STR);
				$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
				$name = $_POST["name"]; 
				$comment = $_POST["comment"];
				$created_at = $now_hour;
   				$pass = $_POST["password1"]; 
				$sql -> execute();
			}elseif(isset($_POST['editbotton'])){//編集ボタンを送信したら
				$edit = $_POST['edit'];//変数に編集番号を挿入
   				$password3 = $_POST['password3'];//送信された番号を変数に代入
   				$dsn = 'データベース名';  //データベース接続
				$user = 'ユーザー名'; 
				$password = 'パスワード'; 
				$pdo = new PDO($dsn,$user,$password);
				$sql = 'SELECT * FROM user'; 
				$results = $pdo -> query($sql);
				foreach ($results as $row){    //$rowの中にはテーブルのカラム名が入る
				if($edit == $row['id'] and $password3 == $row['pass']){//もし編集番号とパスワードがテーブルの中身と一致したら    
				$name = $row['name'];    
				$comment = $row['comment'];    
				$passw = $row['pass']; 
				$number = $row['id'];
				}
				}
			}elseif (isset($_POST['deletebotton'])) {//削除ボタンを送信したら
 			        $dsn = 'データベース名';  //データベース接続
				$user = 'ユーザー名'; 
				$password = 'パスワード'; 
				$pdo = new PDO($dsn,$user,$password);
 				$id = $_POST['delete'];//削除番号を変数に代入
 				$pass = $_POST['password2'];//パスワードを変数に代入
 				$sql = "delete from user where id='$id' AND pass='$pass' order by id='$id' asc"; //idとパスワードからデータを抽出し、消す。
				$result = $pdo->query($sql);
				}

		?>
		<form method="POST" action="mission_4-1_MIKASHIMA.php">
   			名前：<input type = "text" name = "name" value = "<?php echo $name; ?>"><br><!-- 送信された番号の名前を入れる。-->
   			コメント：<input type = "text" name = "comment" value = "<?php echo $comment; ?>"><br><!-- 送信された番号のコメントを入れる。-->
   			<input type = "hidden" name = "editcomment" value ="<?php echo $number; ?>"><!-- 送信された番号を入れる。-->
      			パスワード：<input type = "text" name = "password1" value ="<?php echo $passw; ?>"><!-- 送信された番号のパスワードを入れる。-->
   			<input type = "submit" value = "送信">
  		</form>
  		<form method="POST" action="mission_4-1_MIKASHIMA.php">
   			削除番号：<input type = "text" name = "delete" value = "削除対象番号" >
     			 パスワード：<input type = "text" name = "password2">
   			<input type = "submit" name = "deletebotton" value = "削除">
  		</form>
  		<form method="POST" action="mission_4-1_MIKASHIMA.php">
   			編集番号：<input type = "text" name = "edit" value = "編集番号" >
      			パスワード：<input type = "text" name = "password3">
   			<input type = "submit" name = "editbotton" value = "編集">
  			</form>
		<?php
				$dsn = 'データベース名';  //データベース接続
				$user = 'ユーザー名'; 
				$password = 'パスワード'; 
				$pdo = new PDO($dsn,$user,$password); 
				$sql = "SELECT * FROM user order by id asc"; 
				$results = $pdo -> query($sql); 
				foreach ($results as $row){    //$rowの中にはテーブルのカラム名が入る    
				echo $row['id'].',';    
				echo $row['name'].',';
				echo $row['comment'].',';    
				echo $row['created_at'].'<br>'; 
				}

		?>
	</body>
<html>