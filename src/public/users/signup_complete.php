<?php
$email = filter_input(INPUT_POST, 'email');
$password = filter_input(INPUT_POST, 'password');
$confirmPassword = filter_input(INPUT_POST, 'confirmPassword');

session_start();
if (empty($email) || empty($password) || empty($confirmPassword)) {
    $_SESSION['error'] = 'パスワードとメールアドレスを入力してください';
    $_SESSION['formInputs']['email'] = $email;
    header("Location: ./signup.php");
    exit;
}

if ($password !== $confirmPassword) {
    $_SESSION['error'] = 'パスワードが一致しません';
    $_SESSION['formInputs']['email'] = $email;
    header("Location: ./signup.php");
    exit;
}

$pdo = new PDO("mysql:host=mysql; dbname=kakeibo; charset=utf8", "root", "password");
$sql = 'SELECT * FROM users WHERE email = :email';
$statement = $pdo->prepare($sql);
$statement->bindValue(':email', $email, PDO::PARAM_STR);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);
if ($user) {
    $_SESSION['error'] = 'すでに登録済みのメールアドレスです';
    $_SESSION['formInputs']['email'] = $email;
    header("Location: ./signup.php");
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users(email, password) VALUES (:email, :password)";
$statement = $pdo->prepare($sql);
$statement->bindValue(':email', $email, PDO::PARAM_STR);
$statement->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
$statement->execute();
$_SESSION['completedMessage'] = '登録が完了しました';
header("Location: ./signin.php");
exit;
?>