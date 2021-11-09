<!DOCTYPE html>
<html lang="ja">
<head>
       <meta charset="UTF-8">
       <title>m5-1</title>
</head>
<body>

<?php //DBへの接続
    $dsn = 'データベース名';
    $user = 'ユーザ名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, 
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //DBテーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS tctest"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "pass char(32),"
        . "date DATETIME"
        .");";
    $stmt = $pdo->query($sql);
    if(!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["pass"])&&empty($_POST["editF"])){
    $sql = $pdo ->prepare("INSERT INTO tctest (name,comment,date,pass) VALUES (:name,:comment,:date,:pass)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment',$comment, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
    $name = ($_POST["name"]);
    $comment = ($_POST["comment"]);
    $date = date("Y/m/d H:i:s");
    $pass = ($_POST["pass"]);
    $sql -> execute();
}
?>
<?php //DB削除機能
    if((!empty($_POST["delete"])) && (!empty($_POST["delpass"])))
    {//deleteフォームが埋まっていたら
        $id =  ($_POST["delete"]);
        $delpass =  ($_POST["delpass"]);
        $sql = 'SELECT * FROM tctest WHERE id=:id '; 
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                           
        $results = $stmt->fetchAll(); 
    foreach ($results as $row)
    {  
   if($row["id"] == $id && $row["pass"] ==$delpass)
   {  //idが一致する行のidとpassが削除フォームと一致したら
        $sql = 'delete from tctest where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        }
     }
} 
?>
<?php //DB編集準備
    if(!empty($_POST["edit"])&&!empty($_POST["edipass"]))
    { //編集フォームが埋まっていたら
        $id = ($_POST["edit"]);
        $edipass = ($_POST["edipass"]);
        $sql = 'SELECT * FROM tctest WHERE id=:id'; //idが一致する行を抜き出し
        $stmt = $pdo->prepare($sql);// ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();
        $results = $stmt->fetchAll(); 
    foreach ($results as $row2){
     if($row["id"]==$id && $pass==$edipass){
    $results[0]=$row2["id"];
    $results[1]=$row2["name"];
    $results[2]=$row2["comment"];}}}
     
    if(!empty($_POST["editF"])){//編集判断フォームが埋まっていたら
    $id = ($_POST["editF"]);
    $name = ($_POST["name"]);
    $comment = ($_POST["comment"]);
        $sql = 'UPDATE tctest SET name=:name,comment=:comment WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
                 }
$sql ='SHOW CREATE TABLE tctest';
    $result = $pdo -> query($sql);
    foreach ($result as $row){
        echo $row[1];
}
    echo "<hr>";
?>
    <form action="m5-1.php" method="post">
        <input type="text" name="name" value="<?php if(isset($row2["name"]))echo $row2["name"];?>"placeholder="名前"></br>
        <input type="text" name="comment" value="<?php if(isset($row2["comment"]))echo $row2["comment"];?>"placeholder="コメント"></br>
        <input type="text" name="pass" placeholder="パスワード">
        <input type="submit" name="" value="投稿"></br>
　　　　<input type="hidden" name="editF" value="<?php if(isset($row2["id"])) echo $row2["id"];?>"placeholder="へんしゅう">
    </form></br>

    <form action="m5-1.php" method="post">
        <input type="text" name="delete" placeholder="削除対象番号"></br>
        <input type="text" name="delpass" placeholder="パスワード">
        <input type="submit" name="" value="削除"></br>
    </form></br>

    <form action="m5-1.php" method="post">
        <input type="text" name="edit" placeholder="編集対象番号"></br>
        <input type="text" name="edipass" placeholder="パスワード">
        <input type="submit" name="" value="編集"></br>
    </form></br>
    
<?php
    $sql = 'SELECT * FROM tctest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row3)
    {
   //$rowの中にはテーブルのカラム名が入る
        echo $row3['id'].',';
        echo $row3['name'].',';
        echo $row3['comment'].',';
        echo $row3['date'].',';
             echo "<hr>";
}
?>

</body>
</html>