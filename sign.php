<?php
require_once 'functions.php';
require_once 'config.php';
require_once 'include.php';

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

if (!empty($form['email'])) {
    if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Email должен быть корректным';
    } 
    $email = mysqli_real_escape_string($link, $form['email']);
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);

    if (mysqli_num_rows($res) > 0) {
        $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
    }
}

if (!empty($errors)) {
    $page_content = include_template('sign-up.php', [
        'categories' => $categories,
        'errors' => $errors
    ]);
    $layout_content = include_template('layout.php', [
        'content'    => $page_content,
        'categories' => $categories,
        'title'      => 'Регистрация'
    ]);
    print($layout_content);
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

if ($res && empty($errors)) {
    header("Location: /login.php");
    exit();
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