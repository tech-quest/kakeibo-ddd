<?php
$pdo = new PDO(
    'mysql:host=mysql; dbname=kakeibo; charset=utf8',
    'root',
    'password'
);
$clickButton = $_SERVER['REQUEST_METHOD'] == 'POST';

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$name = filter_input(INPUT_POST, 'name');
$categoryId = filter_input(
    INPUT_POST,
    'categoryId',
    FILTER_SANITIZE_NUMBER_INT
);
$amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_INT);
$accrualDate = filter_input(INPUT_POST, 'accrualDate');

if (
    !empty($name) ||
    empty($categoryId) ||
    empty($amount) ||
    empty($accrualDate)
) {
    $sql =
        'UPDATE spendings SET name=:name, category_id=:categoryId, amount=:amount, accrual_date=:accrualDate WHERE id=:id';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->bindValue(':name', $name, PDO::PARAM_STR);
    $statement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
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