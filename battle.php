<?php
if(!empty($_POST)){
    header('Location:battleStart.php'); //戦闘画面へ
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>OBUKATU QUEST オブジェクト指向の動作確認</title>
</head>
<body>
    <main class="main">
        <h1>OBUKATU QUEST</h1>
        <form method="post">
        <input type="submit" name="btn" value="冒険をスタートする">
        </form>
    </main>
</body>
</html>