<?php
$id = $_POST['id'];
$name = $_POST['name'];
$pdo = new PDO(
    'mysql:host=mysql; dbname=kakeibo; charset=utf8',
    'root',
    'password'
);
$sql = 'UPDATE income_sources SET name=:name WHERE id=:id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':id', $id);
$statement->bindValue(':name', $name, PDO::PARAM_STR);
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
  <title>更新結果画面</title>
</head>

<body>
  <div class="container">
    <h2><?php echo $message; ?></h2>
    <a href="./index.php">
      <p>投稿一覧へ<p>
    </a>
  </div>
</body>

</html>