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
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$sql = 'SELECT * FROM categories where id = :id';
$statement = $pdo->prepare($sql);
$statement->bindvalue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$category = $statement->fetch();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>編集画面</title>
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
      <h1 class="mb-5 text-xl text-center">編集</h1>
      <div class="mb-5 mx-auto w-5/6">
        <form method="post" action="./update.php">
          <input type="hidden" name="id" value=<?php echo $category['id']; ?>>
          <div class="form-group mb-5">
            <label class="font-bold" for="name">カテゴリ名
              <input class="border" type="text" name="name" value=<?php echo $category[
                  'name'
              ]; ?>>
            </label>
          </div>
          <button type="submit" class="bg-blue-500 px-1 py-1 text-white rounded">更新</button>
        </form>
      </div>
    </main>
  </div>
</body>

</html>