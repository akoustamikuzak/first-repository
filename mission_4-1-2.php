<?php

	$name=$_POST['name'];
	$comment=$_POST['comment'];
	$pass=$_POST['password'];
	$delete=$_POST['delete'];
	$edit=$_POST['edit'];
	$editnumber=$_POST['editnumber'];
	$deletepass=$_POST['deletepass'];
	$editpass=$_POST['editpass'];
	//変数に送信された情報を代入

	$dsn='データベース名';
	$user='ユーザー名';
	$password='パスワード';
	$pdo=new PDO($dsn,$user,$password);
	//データベースに接続

	$sql="CREATE TABLE IF NOT EXISTS toukou"
	."("
	."id INT UNSIGNED AUTO_INCREMENT,"
	."name char(32),"
	."comment TEXT,"
	."date DATETIME,"
	."password char(32),"
	."PRIMARY KEY(id)"
	.");";
	$stmt=$pdo->query($sql);
	//テーブルが無いときにテーブルを作る

if(!empty($editnumber))
{
	$sql="update toukou set name='$name',comment='$comment',date=cast(now() as datetime),password='$pass' where id=$editnumber";
	$pdo->query($sql);
	unset($editnumber);
}else{
	if(!empty($comment)and($name))
	{
		if(($edit=="")and($delete==""))
		{
			$sql=$pdo->prepare("INSERT INTO toukou(name,comment,date,password) VALUE(:name,:comment,cast(now() as datetime),:password)");
			$sql->bindParam(':name',$name,PDO::PARAM_STR);
			$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
			$sql->bindValue(':password',$pass,PDO::PARAM_INT);
			$sql->execute();
		}
	}
}

if(!empty($delete))
{
	$passcheck='SELECT*FROM toukou';
	$check=$pdo->query($passcheck);
	foreach($check as $rows)
	{
		if(($rows['id']==$delete)and($rows['password']==$deletepass))
		{
			$sql="delete from toukou where id=$delete";
			$result=$pdo->query($sql);
		}elseif(($rows['id']==$delete)and($rows['password']!=$deletepass))
		{
			echo "パスワードが違います";
		}
	}
}

if(!empty($edit))
{
	$sql='SELECT*FROM toukou';
	$results=$pdo->query($sql);
	foreach($results as $row)
	{
		if(($row['id']==$edit)and($row['password']==$editpass))
		{
			$editnumber=$edit;
			$editname=$row['name'];
			$editcomment=$row['comment'];
			$editpassword=$row['password'];
		}elseif(($row['id']==$edit)and($row['password']!=$editpass))
		{
			echo "パスワードが違います";
		}
	}
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta http-equiv="content-type" charset="utf-8">
	<!--文字コードの指定-->
	<title>掲示板</title>
</head>

<body>
	<form action="mission_4-1.php" method="post">
	<!--送り先と方法の指定-->
	<p>
		<input type="text" name="name" size="30" value="<?php echo $editname ?>" placeholder="お名前">
	</p>	<!--名前フォーム-->
	<p>
		<textarea name="comment" rows="3" cols="50" placeholder="コメントを記入"><?php echo $editcomment ?></textarea>
	</p>	<!--コメントフォーム-->
	<p>
		<input type="text" name="password" size="30" value="<?php echo $editpassword ?>" placeholder="パスワード">
		<!--パスワードフォーム-->
		<input type="hidden" name="editnumber" value="<?php echo $editnumber ?>">
		<!--編集フォーム-->
		<input type="submit" value="送信">
	</p>	<!--送信ボタン-->
	<p>
		<input type="text" name="delete" value="" placeholder="削除番号">
	</p>	<!--削除フォーム-->
	<p>
		<input type="text" name="deletepass" value="" placeholder="パスワード">
		<input type="submit" value="削除">
	</p>	<!--削除ボタン-->
	<p>
		<input type="text" name="edit" value="" placeholder="編集番号">
	</p>	<!--編集フォーム-->
	<p>
		<input type="text" name="editpass" value="" placeholder="パスワード">
		<input type="submit" value="編集">
	</p>	<!--編集ボタン-->
	</form>
</body>
</html>

<?php
	$dsn='データベース名';
	$user='ユーザー名';
	$password='パスワード';
	$pdo=new PDO($dsn,$user,$password);
	//データベースに接続

	$sql='SELECT*FROM toukou ORDER BY id';
	$results=$pdo->query($sql);
	if(!empty($results))
	{
		foreach($results as $row)
		{
			echo $row['id'].':';
			echo $row['name'].':';
			echo $row['comment'].':';
			echo $row['date'].'<br>';
		}
	}
?>
