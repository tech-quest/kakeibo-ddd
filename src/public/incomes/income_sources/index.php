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
$sql = 'SELECT * FROM income_sources WHERE user_id=:userId';
$statement = $pdo->prepare($sql);
$statement->bindvalue(':userId', $userId, PDO::PARAM_INT);
$statement->execute();
$income_sources = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>収入源一覧</title>
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
      <h1 class="mb-5 text-xl text-center">収入源一覧</h1>
      <div class="mb-5 mx-auto w-5/6">
        <a class="text-blue-600 mb-5" href="./create.php">収入源を追加する</a>
      </div>
      <table class="w-full border mb-5">
        <tr class="bg-gray-50 border-b">
          <th class="border-r p-2">収入源</th>
          <th class="border-r p-2">編集</th>
          <th class="border-r p-2">削除</th>
        </tr>
        <?php foreach ($income_sources as $income_source): ?>
        <tr class="bg-gray-100 text-center border-b text-sm text-gray-600">
          <td class="p-2 border-r"><?php echo $income_source['name']; ?></td>
          <td class="p-2 border-r"><a class="text-blue-600" href="./edit.php?id=<?php echo $income_source[
              'id'
          ]; ?>">編集</a></td>
          <td class="p-2 border-r">
            <form method="post" action="./delete.php">
              <input type="hidden" name="id" value="<?php echo $income_source[
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
      <div class="mb-5 mx-auto w-5/6">
        <a href="../create.php">戻る</a>
      </div>
    </main>
  </div>
</body>

</html>