<?php
session_start();
if (!isset($_SESSION['user']['id'])) {
    header('Location: ./signin.php');
    exit();
}

$pdo = new PDO(
    'mysql:host=mysql; dbname=kakeibo; charset=utf8',
    'root',
    'password'
);
$name = filter_input(INPUT_POST, 'name');
$categoryId = filter_input(
    INPUT_POST,
    'categoryId',
    FILTER_SANITIZE_NUMBER_INT
);
$amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_INT);
$accrualDate = filter_input(INPUT_POST, 'accrualDate');

if (
    empty($name) ||
    empty($categoryId) ||
    empty($amount) ||
    empty($accrualDate)
) {
    echo '<h2>入力が正しくありません</h2>';
    echo '<a href="./create.php">戻る</a>';
    die();
}

$userId = $_SESSION['user']['id'];
$sql =
    'INSERT INTO `spendings`(`id`, `name`, `user_id`, `category_id`, `amount`, `accrual_date`) VALUES(0, :name, :userId, :categoryId, :amount, :accrualDate)';
$statement = $pdo->prepare($sql);
$statement->bindValue(':name', $name, PDO::PARAM_STR);
$statement->bindValue(':userId', $userId, PDO::PARAM_INT);
$statement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
$statement->bindValue(':amount', $amount, PDO::PARAM_INT);
$statement->bindValue(':accrualDate', $accrualDate, PDO::PARAM_STR);
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