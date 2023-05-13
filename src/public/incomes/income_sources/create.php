<?php

session_start();
if (!isset($_SESSION['user']['id'])) {
    header('Location: ./signin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>収入源追加</title>
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
      <h1 class="my-5 text-xl text-center">収入源追加</h1>
      <div class="mb-5 mx-auto w-5/6">
        <form class="mb-5" method="post" action="./store.php">
          <div class="form-group mb-5">
            <label class="font-bold" for="name">収入源:
              <input class="border" type="text" name="name" placeholder="収入源を入力">
            </label>
          </div>
          <button type="submit" class="bg-blue-500 px-1 py-1 text-white rounded">登録</button>
        </form>
        <a href="./index.php">戻る</a>
      </div>
    </main>
  </div>
</body>

</html>