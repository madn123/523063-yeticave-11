<?php
require_once 'functions.php';
require_once 'config.php';

if (!$link) {
    $error = mysqli_error($link);
    die();
}

$sql = 'SELECT id, category_name, category_code FROM categories ORDER BY category_name ASC';

$result = mysqli_query($link, $sql);

if (!$result) {
    $error = debug_error($link);
    die();
}

$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

if($_SERVER['REQUEST_METHOD'] != 'POST') {
    $page_content = include_template('sign-up.php', ['categories' => $categories]);
    $layout_content = include_template('layout.php', [
        'content'    => $page_content,
        'categories' => $categories,
        'title'      => 'Регистрация'
    ]);
    print($layout_content);
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

if (empty($errors)) {
    $email = mysqli_real_escape_string($link, $form['email']);
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);

    if (mysqli_num_rows($res) > 0) {
        $errors[$email] = 'Пользователь с этим email уже зарегистрирован';
    }
    else {
        $pass = password_hash($form['pass'], PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users (dt_add, email, name, pass, contacts) VALUES (NOW(), ?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, [
            $form['email'], 
            $form['name'], 
            $pass, 
            $form['contacts']]);
        $res = mysqli_stmt_execute($stmt);
    }

    if ($res && empty($errors)) {
        header("Location: /login.php");
        exit();
    }
}

$page_content = include_template('sign-up.php', [
    'errors' => $errors,
    'form' => $form, 
    'categories' => $categories
]);

$layout_content = include_template('layout.php', [
    'content'    => $page_content,
    'categories' => $categories,
    'title'      => 'Регистрация'
]);

print($layout_content);