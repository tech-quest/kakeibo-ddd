<?php
session_start();
if (!isset($_SESSION['user']['id'])) {
    header('Location: ./signin.php');
    exit();
}

$userId = $_SESSION['user']['id'];
$thisYear = date('Y');
$lastTenYears = range($thisYear - 9, $thisYear);
$selectYear = filter_input(INPUT_GET, 'selectYear');
$pdo = new PDO(
    'mysql:host=mysql; dbname=kakeibo; charset=utf8',
    'root',
    'password'
);

$spendingSubQuery = <<<EOF
SELECT 
  DATE_FORMAT(accrual_date, '%Y-%m') as accrual_year_month
  ,SUM(amount) as total_amount 
FROM 
  spendings 
WHERE 
  user_id = :userId 
  AND DATE_FORMAT(accrual_date, '%Y') = :selectYear 
GROUP BY DATE_FORMAT(accrual_date, '%Y-%m')
EOF;

$incomeSubQuery = <<<EOF
SELECT 
  DATE_FORMAT(accrual_date, '%Y-%m') as accrual_year_month
  ,SUM(amount) as total_amount 
FROM 
  incomes 
WHERE 
  user_id = :userId 
  AND DATE_FORMAT(accrual_date, '%Y') = :selectYear 
GROUP BY DATE_FORMAT(accrual_date, '%Y-%m')
EOF;

$sql = <<<EOF
SELECT 
  month_spends.accrual_year_month as spends_date
  ,month_incomes.accrual_year_month as incomes_date
  ,month_incomes.total_amount as incomes_total_amount
  ,month_spends.total_amount as spends_total_amount 
FROM 
  ({$spendingSubQuery}) month_spends 
RIGHT JOIN 
  ({$incomeSubQuery}) month_incomes 
ON 
  month_spends.accrual_year_month = month_incomes.accrual_year_month;
EOF;

$statement = $pdo->prepare($sql);
$statement->bindValue(':userId', $userId, PDO::PARAM_INT);
$statement->bindValue(':selectYear', $selectYear, PDO::PARAM_INT);
$statement->execute();
$balances = $statement->fetchAll(PDO::FETCH_ASSOC);

$balancesGroupByMonth = [];
foreach ($balances as $balance) {
    $yearMonth = $balance['spends_date'] ?? $balance['incomes_date'];
    [, $month] = explode('-', $yearMonth);
    $monthKey = sprintf('%d', $month);

    $incomesTotalAmount = $balance['incomes_total_amount'] ?? 0;
    $spendsTotalAmount = $balance['spends_total_amount'] ?? 0;
    $balancesGroupByMonth[$monthKey] = [
        'accrual_year_month' => $monthKey,
        'incomes_total_amount' => $incomesTotalAmount,
        'spends_total_amount' => $spendsTotalAmount,
        'balance_of_payments' => $incomesTotalAmount - $spendsTotalAmount,
    ];
}

for ($i = 1; $i <= 12; $i++) {
    if (isset($balancesGroupByMonth[$i])) {
        continue;
    }

    $balancesGroupByMonth[$i] = [
        'accrual_year_month' => $i,
        'incomes_total_amount' => 0,
        'spends_total_amount' => 0,
        'balance_of_payments' => 0,
    ];
}
ksort($balancesGroupByMonth);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TOPページ</title>
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
      <h1 class="mb-5 text-xl text-center">家計簿アプリ</h1>
      <form method="get" action="./index.php" class="flex mb-5 mx-auto w-5/6">
        <h2 class="text-lg mr-5">
          <select class="border" name="selectYear">
            <?php foreach ($lastTenYears as $oneYear): ?>
            <option value=<?php echo $oneYear; ?> <?php if (
     $oneYear == $selectYear
 ) {
     echo 'selected';
 } ?>><?php echo $oneYear; ?></option>
            <?php endforeach; ?>
          </select>
          年
          の収支一覧
        </h2>
        <button type="submit" class="bg-blue-500 px-1 py-1 text-white rounded">検索</button>
      </form>
      <table class="w-full border mb-5">
        <tr class="bg-gray-50 border-b">
          <th class="border-r p-2">月</th>
          <th class="border-r p-2">収入</th>
          <th class="border-r p-2">支出</th>
          <th class="border-r p-2">収支</th>
        </tr>
        <?php foreach ($balancesGroupByMonth as $month => $balance): ?>
        <tr class="bg-gray-100 text-center border-b text-sm text-gray-600">
          <td class="p-2 border-r"><?php echo $month; ?></td>
          <td class="p-2 border-r"><?php echo $balance[
              'incomes_total_amount'
          ]; ?></td>
          <td class="p-2 border-r"><?php echo $balance[
              'spends_total_amount'
          ]; ?></td>
          <td class="p-2 border-r"><?php echo $balance[
              'balance_of_payments'
          ]; ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </main>
  </div>
</body>

</html>