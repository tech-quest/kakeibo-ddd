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
$userId = $_SESSION['user']['id'];
$sqlValuesTableDisplay[':userId']['value'] = $userId;
$sqlValuesTableDisplay[':userId']['type'] = PDO::PARAM_INT;
$sqlTableDisplay =
    'SELECT s.*, c.name AS categories_name FROM spendings AS s JOIN categories AS c ON s.category_id = c.id WHERE s.user_id=?';

$categoryId = filter_input(INPUT_GET, 'categoryId', FILTER_SANITIZE_NUMBER_INT);

if (!empty($categoryId)) {
    $sqlTableDisplay = $sqlTableDisplay . ' && s.category_id=?';
    $sqlValuesTableDisplay[':categoryId']['value'] = $categoryId;
    $sqlValuesTableDisplay[':categoryId']['type'] = PDO::PARAM_INT;
}

$startDate = filter_input(INPUT_GET, 'startDate', FILTER_SANITIZE_NUMBER_INT);

if (!empty($startDate)) {
    $sqlTableDisplay = $sqlTableDisplay . ' && s.accrual_date >=?';
    $sqlValuesTableDisplay[':startDate']['value'] = $startDate;
    $sqlValuesTableDisplay[':startDate']['type'] = PDO::PARAM_STR;
}

$endDate = filter_input(INPUT_GET, 'endDate', FILTER_SANITIZE_NUMBER_INT);

if (!empty($endDate)) {
    $sqlTableDisplay = $sqlTableDisplay . ' && s.accrual_date <=?';
    $sqlValuesTableDisplay[':endDate']['value'] = $endDate;
    $sqlValuesTableDisplay[':endDate']['type'] = PDO::PARAM_STR;
}

$sqlTableDisplay = $sqlTableDisplay . ' order by accrual_date desc';

$statementTableDisplay = $pdo->prepare($sqlTableDisplay);
$index = 1;
foreach ($sqlValuesTableDisplay as $k => $data) {
    $statementTableDisplay->bindValue($index, $data['value'], $data['type']);
    $index++;
}
$statementTableDisplay->execute();
$spendings = $statementTableDisplay->fetchAll(PDO::FETCH_ASSOC);

$sqlValuesTotalAmount[':userId']['value'] = $userId;
$sqlValuesTotalAmount[':userId']['type'] = PDO::PARAM_INT;
$sqlTotalAmount = 'SELECT SUM(amount) from spendings WHERE user_id=?';

if (!empty($categoryId)) {
    $sqlTotalAmount = $sqlTotalAmount . ' && category_id=?';
    $sqlValuesTotalAmount[':categoryId']['value'] = $categoryId;
    $sqlValuesTotalAmount[':categoryId']['type'] = PDO::PARAM_INT;
}

if (!empty($startDate)) {
    $sqlTotalAmount = $sqlTotalAmount . ' && accrual_date >=?';
    $sqlValuesTotalAmount[':startDate']['value'] = $startDate;
    $sqlValuesTotalAmount[':startDate']['type'] = PDO::PARAM_STR;
}

if (!empty($endDate)) {
    $sqlTotalAmount = $sqlTotalAmount . ' && accrual_date <=?';
    $sqlValuesTotalAmount[':endDate']['value'] = $endDate;
    $sqlValuesTotalAmount[':endDate']['type'] = PDO::PARAM_STR;
}

$statementsqlTotalAmount = $pdo->prepare($sqlTotalAmount);
$index = 1;
foreach ($sqlValuesTotalAmount as $k => $data) {
    $statementsqlTotalAmount->bindValue($index, $data['value'], $data['type']);
    $index++;
}
$statementsqlTotalAmount->execute();
$totalAmount = $statementsqlTotalAmount->fetch(PDO::FETCH_COLUMN) ?? 0;

$sqlSpendingsCategories = "SELECT id, name FROM categories WHERE user_id=$userId";
$statementSpendingsCategories = $pdo->prepare($sqlSpendingsCategories);
$statementSpendingsCategories->execute();
$spendingsCategories = $statementSpendingsCategories->fetchAll(
    PDO::FETCH_ASSOC
);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>家計簿アプリ</title>
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-300">
  <div class="mx-auto md:w-7/12 w-11/12 bg-white mb-6 h-screen">
    <header class="text-right mb-14 bg-blue-500">
      <ul class="flex w-4/5 m-auto justify-between">
        <li>
          <a href="/index.php" class="text-white leading-9">HOME</a>
        </li>
        <li>
          <a href="/incomes/index.php" class="text-white leading-9">収入TOP</a>
        </li>
        <li>
          <a href="/spendings/index.php" class="text-white leading-9">支出TOP</a>
        </li>
        <li>
          <a href="/users/logout.php" class="text-white leading-9">ログアウト</a>
        </li>
      </ul>
    </header>
    <main class="w-full">
      <h1 class="mb-5 text-xl text-center">支出</h1>
      <div class="mb-5 mx-auto w-5/6">
        <h2 class="text-lg mr-5">合計額: <?php echo $totalAmount; ?>円</h2>
        <a class="text-blue-600 mb-5" href="./create.php">支出を登録する</a>
        <form method="get" action="./index.php">
          <label for=""><b>絞り込み検索</b></label>
          <div class="form-group">
            カテゴリー:
            <select class="border" name="categoryId">
              <option value=""></option>
              <?php foreach ($spendingsCategories as $category): ?>
              <option value=<?php echo $category['id']; ?> <?php if (
     isset($categoryId) &&
     $category['id'] == $categoryId
 ) {
     echo 'selected';
 } ?>>
                <?php echo $category['name']; ?></option>
              <?php endforeach; ?>
            </select>
            日付:
            <input class="border" type="date" name="startDate" value=<?php if (
                isset($startDate)
            ) {
                echo $startDate;
            } ?>> 〜
            <input class="border" type="date" name="endDate" value=<?php if (
                isset($endDate)
            ) {
                echo $endDate;
            } ?>>
            <button type="submit" class="bg-blue-500 px-1 py-1 text-white rounded">検索</button>
          </div>
        </form>
      </div>
      <table class="w-full border mb-5">
        <tr class="bg-gray-50 border-b">
          <th class="border-r p-2">支出名</th>
          <th class="border-r p-2">カテゴリー</th>
          <th class="border-r p-2">金額</th>
          <th class="border-r p-2">日付</th>
          <th class="border-r p-2">編集</th>
          <th class="border-r p-2">削除</th>
        </tr>
        <?php foreach ($spendings as $spending): ?>
        <tr class="bg-gray-100 text-center border-b text-sm text-gray-600">
          <td class="p-2 border-r"><?php echo $spending['name']; ?></td>
          <td class="p-2 border-r">
            <?php echo $spending['categories_name']; ?>
          </td>
          <td class="p-2 border-r">
            <?php echo $spending['amount']; ?>
          </td>
          <td class="p-2 border-r">
            <?php echo $spending['accrual_date']; ?>
          </td>
          <td class="p-2 border-r">
            <a class="text-blue-600" href="./edit.php?id=<?php echo $spending[
                'id'
            ]; ?>">編集</a>
          </td>
          <td class="p-2 border-r">
            <form method="post" action="./delete.php">
              <input type="hidden" name="id" value="<?php echo $spending[
                  'id'
              ]; ?>">
              <button type="submit" class="text-red-500">
                削除
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
    </main>
  </div>
</body>

</html>