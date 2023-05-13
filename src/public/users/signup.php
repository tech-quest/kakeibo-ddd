<?php
session_start();
$error = $_SESSION['error'] ?? "";
unset($_SESSION['error']);
$email = $_SESSION['formInputs']['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-200 w-full h-screen flex justify-center items-center">
    <div class="w-96  bg-white pt-10 pb-10 rounded-xl">
        <div class="w-60 m-auto text-center">
            <h2 class="text-2xl pb-5">会員登録</h2>
            <p class="text-red-600"><?php echo $error; ?></p>
            <form action="./signup_complete.php" method="POST">
                <input class='border-2 border-gray-300 w-full mb-5' placeholder="Email" type=“text” name="email" type="email" required value=""><br />
                <input class='border-2 border-gray-300 w-full mb-5' placeholder="Password" type="password" name="password"><br />
                <input class='border-2 border-gray-300 w-full mb-5' placeholder="Password確認" type="password" name="confirmPassword"><br />
                <input class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mb-5 w-full' type="submit" name="send" value="アカウント作成">
            </form>
            <a class="text-blue-600" href="./signin.php">ログイン画面へ</a>
        </div>
    </div>
</body>

</html>