<?php
$id = filter_input(INPUT_POST, 'id');
$incomeSourceId = filter_input(INPUT_POST, 'incomeSourceId');
$amount = filter_input(INPUT_POST, 'amount');
$accrualDate = filter_input(INPUT_POST, 'accrualDate');
$pdo = new PDO(
    'mysql:host=mysql; dbname=kakeibo; charset=utf8',
    'root',
    'password'
);
if (!empty($incomeSourceId) || empty($amount) || empty($accrualDate)) {
    $sql =
        'UPDATE incomes SET income_source_id=:incomeSourceId, amount=:amount, accrual_date=:accrualDate WHERE id=:id';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $id);
    $statement->bindValue(':incomeSourceId', $incomeSourceId, PDO::PARAM_INT);
    $statement->bindValue(':amount', $amount, PDO::PARAM_INT);
    $statement->bindValue(':accrualDate', $accrualDate, PDO::PARAM_STR);
    $statement->execute();
}
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