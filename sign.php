<?php
require_once 'include.php';

if (isset($_SESSION['user'])) {
        header("Location: /");
        exit();
}

if($_SERVER['REQUEST_METHOD'] != 'POST') {
    print render('sign-up.php', 'Регистрация');
    die();
}

$form = $_POST;
$required = ['email', 'name', 'pass', 'contacts'];
$errors = [];

foreach ($required as $field) {
    if (empty($form[$field])) {
        $errors[$field] = "Не заполнено поле " . $field;
    }
}

$error = validate_email($form['email'], $link);
if ($error != null) {
    $errors['email'] = $error;
}

if (!empty($errors)) {
    print render('sign-up', 'Ошибка регистрации', ['errors' => $errors]);
    die();
}

$pass = password_hash($form['pass'], PASSWORD_DEFAULT);

$sql = 'INSERT INTO users (dt_add, email, name, pass, contacts) VALUES (NOW(), ?, ?, ?, ?)';
$stmt = db_get_prepare_stmt($link, $sql, [
    trim($form['email']),
    $form['name'],
    $pass,
    $form['contacts']]);
$res = mysqli_stmt_execute($stmt);

if(!$res){
    debug_error($link);
    die();
}

header("Location: /login.php");
