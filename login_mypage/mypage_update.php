<?php
mb_internal_encoding("utf8");

session_start();

try {
    $pdo = new PDO("mysql:dbname=lesson1;host=localhost;","root","");
        //データベースに接続するために必要な行
    
} catch(PDOException $e) {
    die("<p>申し訳ございません。現在サーバーが混み合っており一時的にアクセスが出来ません。<br>しばらくしてから再度ログインをしてください。</p>
    <a href='http://localhost/login_mypage/login.php'>ログイン画面へ</a>");
}

$stmt = $pdo->prepare("update login_mypage set name = ?, mail = ?, password = ?, comments = ? where id = ?");

$stmt->bindValue(1,$_POST['name']);
$stmt->bindValue(2,$_POST['mail']);
$stmt->bindValue(3,$_POST['password']);
$stmt->bindValue(4,$_POST['comments']);
$stmt->bindValue(5,$_SESSION['id']);


$stmt->execute();

$stmt = $pdo->prepare("select * from login_mypage where mail = ? && password = ?");
        //mailとpasswordが一致するデータをデータベースからすべて引っ張って来る
        //stmtはステイトメントということで置いてあるだけで$〇〇は別に何でもよい

$stmt->bindValue(1,$_POST["mail"]);
$stmt->bindValue(2,$_POST["password"]);

$stmt->execute();
    //executeでselect文を実行する

while ($row = $stmt->fetch()) {
    //pdoでselect文を使う決まり文句。取得したデータを表示させるためのループ処理
    $_SESSION['id']=$row['id'];
    $_SESSION['name']=$row['name'];
    $_SESSION['mail']=$row['mail'];
    $_SESSION['password']=$row['password'];
    $_SESSION['picture']=$row['picture'];
    $_SESSION['comments']=$row['comments'];
        //while文で取得したIDをセッションに代入する
}

$stmt = NULL;
    //ここでデータベースを切断

header("Location:http://localhost/login_mypage/mypage.php");

?>
