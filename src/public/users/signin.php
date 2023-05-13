<?php
session_start();
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
$email = $_SESSION['formInputs']['email'] ?? '';
$completedMessage = $_SESSION['completedMessage'] ?? '';
unset($_SESSION['completedMessage']);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-200 w-full h-screen flex justify-center items-center">
    <div class="w-96  bg-white pt-10 pb-10 rounded-xl">
        <div class="w-60 m-auto text-center">
            <h2 class="text-2xl pb-5">ログイン</h2>
            <p class="text-blue-600"><?php echo $completedMessage ?></p>
            <p class="text-red-600"><?php echo $error ?></p>
            <form class="px-4" action="./signin_complete.php" method="POST">
                <p><input class="border-2 border-gray-300 mb-5 w-full" type=“text” name="email" type="email" required placeholder="Email" value="<?php if (isset($_SESSION['email'])) echo $_SESSION['email']; ?>"></p>
                <p><input class="border-2 border-gray-300 mb-5 w-full" type="password" placeholder="Password" name="password"></p>
                <button class="bg-blue-500 text-white leading-loose rounded hover:bg-blue-700 border-2 border-gray-300 mb-5 w-full" type="submit">ログイン</button>
            </form>
            <a class="text-blue-600 " href="./signup.php">アカウントを作る</a>
        </div>
    </div>
</body>

</html>