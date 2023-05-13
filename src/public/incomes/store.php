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
$incomeSourceId = filter_input(
    INPUT_POST,
    'incomeSourceId',
    FILTER_SANITIZE_NUMBER_INT
);
$amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_INT);
$accrualDate = filter_input(INPUT_POST, 'accrualDate');

if (empty($incomeSourceId) || empty($amount) || empty($accrualDate)) {
    echo '<h2>入力が正しくありません</h2>';
    echo '<a href="./create.php">戻る</a>';
    die();
}

$userId = $_SESSION['user']['id'];
$sql =
    'INSERT INTO `incomes`(`id`, `user_id`, `income_source_id`, `amount`, `accrual_date`) VALUES(0, :userId, :incomeSourceId, :amount, :accrualDate)';
$statement = $pdo->prepare($sql);
$statement->bindValue(':userId', $userId, PDO::PARAM_INT);
$statement->bindValue(':incomeSourceId', $incomeSourceId, PDO::PARAM_INT);
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
  <title>家計簿アプリ</title>
</head>

<body>
  <div class="container">
  </div>
</body>

</html>