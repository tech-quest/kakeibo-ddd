<?php
$email = filter_input(INPUT_POST, 'email');
$password = filter_input(INPUT_POST, 'password');

session_start();
if (empty($email) || empty($password)) {
    $_SESSION['error'] = 'パスワードとメールアドレスを入力してください';
    $_SESSION['formInputs']['email'] = $email;
    header("Location: ./signin.php");
    exit;
}

$pdo = new PDO("mysql:host=mysql; dbname=kakeibo; charset=utf8", "root", "password");
$sql = "select * from users where email = :email";
$statement = $pdo->prepare($sql);
$statement->bindValue(':email', $email, PDO::PARAM_STR);
$statement->execute();
$user = $statement->fetch();

if (is_null($user)) {
    $_SESSION['error'] = "メールアドレスまたは<br />パスワードが違います";
    $_SESSION['formInputs']['email'] = $email;
    header("Location: ./signin.php");
    exit;
}
$_SESSION['user']['id'] = $user['id'];
header("Location: ../index.php");
exit;