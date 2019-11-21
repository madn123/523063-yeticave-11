<?php
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

$error = validateEmail($form['email'], $link);
if ($error != null) {
    $errors['email'] = $error;
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