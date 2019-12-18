<?php
require_once 'include/include.php';

if (is_auth()) {
    header("Location: /");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $required = ['email' => '"E-mail"', 'pass' => '"Пароль"'];
    $errors = [];

    foreach ($required as $field => $field_name) {
        if (empty($form[$field])) {
            $errors[$field] = "Не заполнено поле " . $field_name;
        }
    }
}

if (!empty($errors) or $_SERVER['REQUEST_METHOD'] != 'POST') {
    print render('login', 'Авторизация', ['errors' => $errors]);
    die();
}

$email = mysqli_real_escape_string($link, $form['email']);

$sql = <<<SQL
    SELECT * FROM users WHERE email = '$email'
SQL;

$res = do_query($link, $sql);

$user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;
if (!$user) {
    $errors['email'] = 'Не верный логин или пароль';
}

if (empty($errors)) {
    if (password_verify($form['pass'], $user['pass'])) {
        $_SESSION['user'] = $user;
    } else {
        $errors['pass'] = 'Не верный логин или пароль';
    }
}

if (!empty($errors)) {
    print render('login', 'Ошибка авторизации', ['errors' => $errors]);
    die();
}

header("Location: /");
