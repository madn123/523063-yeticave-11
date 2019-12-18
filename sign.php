<?php
require_once 'include/include.php';

if (is_auth()) {
    header("Location: /");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    print render('sign-up', 'Регистрация');
    die();
}

$form = $_POST;
$required = [
    'email' => '"E-mail"',
    'name' => '"Имя"',
    'pass' => '"Пароль"',
    'contacts' => '"Контактные данные"'];
$errors = [];

foreach ($required as $field => $field_name) {
    if (empty($form[$field])) {
        $errors[$field] = "Не заполнено поле " . $field_name;
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

do_query($link, "INSERT INTO users (dt_add, email, name, pass, contacts) VALUES (NOW(), ?, ?, ?, ?)", [
    trim($form['email']),
    $form['name'],
    $pass,
    $form['contacts']
]);

header("Location: /login.php");
