<?php
session_start();
if (!isset($_SESSION['user']['id'])) {
    header('Location: ./signin.php');
    exit();
}

$name = $_POST['name'];
$checkError = empty($name);
if ($checkError) {
    $message = '入力が正しくありません';
    die($message);
}

$pdo = new PDO(
    'mysql:host=mysql; dbname=kakeibo; charset=utf8',
    'root',
    'password'
);
$userId = $_SESSION['user']['id'];
$sql =
    'INSERT INTO `categories`(`id`, `name`, `user_id`) VALUES(0, :name, :userId)';
$statement = $pdo->prepare($sql);
$statement->bindValue(':name', $name, PDO::PARAM_STR);
$statement->bindValue(':userId', $userId, PDO::PARAM_STR);
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
  <title>保存失敗</title>
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