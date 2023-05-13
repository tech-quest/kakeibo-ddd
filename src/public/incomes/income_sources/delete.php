<?php
$pdo = new PDO(
    'mysql:host=mysql; dbname=kakeibo; charset=utf8',
    'root',
    'password'
);
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$sql = 'DELETE FROM income_sources where id = :id';
$statement = $pdo->prepare($sql);
$statement->bindvalue(':id', $id, PDO::PARAM_INT);
$statement->execute();
header('Location: ./index.php');
exit();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>削除画面</title>
</head>

<body>
  <div class="container">
    <h2>削除しました</h2>
    <a href="./index.php">
      <p>投稿一覧へ<p>
    </a>
  </div>
</body>

</html>