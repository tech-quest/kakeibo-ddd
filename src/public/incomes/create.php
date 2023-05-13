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
$sql = 'SELECT * FROM income_sources WHERE user_id = :userId';
$statement = $pdo->prepare($sql);
$statement->bindvalue(':userId', $userId, PDO::PARAM_INT);
$statement->execute();
$incomeSources = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>収入登録</title>
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
    <h1 class="text-center text-xl my-5">収入登録</h1>
    <div class="mb-5 mx-auto w-5/6">
      <form class="mb-5" method="post" action="./store.php">
        <div class="form-group mb-5">
          <label class="font-bold" for="income_source">収入源:
            <select class="border" name="incomeSourceId">
              <?php foreach ($incomeSources as $incomeSource): ?>
              <option value=<?php echo $incomeSource['id']; ?>>
                <?php echo $incomeSource['name']; ?>
              </option>
              <?php endforeach; ?>
            </select>
          </label>
          <a href="./income_sources/index.php" class="text-blue-600 mb-5">収入源一覧へ</a>
        </div>
        <div class="form-group mb-5">
          <label class="font-bold" for="amount">金額
            <input class="border" type="number" name="amount" place holder="金額"> 円
          </label>
        </div>
        <div class="form-group mb-5">
          <label class="font-bold" for="accrualDate">日付
            <input class="border" type="date" name="accrualDate">
          </label>
        </div>
        <button type="submit" class="bg-blue-500 px-1 py-1 text-white rounded">登録</button>
      </form>
      <a href="./index.php">戻る</a>
    </div>
  </div>
</body>

</html>