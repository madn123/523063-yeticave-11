<?php
require_once 'include.php';

if (isset($_SESSION['user'])) {
        header("Location: /index.php");
        exit();
}

if($_SERVER['REQUEST_METHOD'] != 'POST') {
    $page_content = include_template('login.php', ['categories' => $categories]);
    $layout_content = include_template('layout.php', [
        'content'    => $page_content,
        'categories' => $categories,
        'title'      => 'Авторизация'
    ]);
    print($layout_content);
    die();    
}

$form = $_POST;

$required = ['email', 'pass'];
$errors = [];

foreach ($required as $field) {
    if (empty($form[$field])) {
        $errors[$field] = "Не заполнено поле " . $field;
    }
}

$email = mysqli_real_escape_string($link, $form['email']);
$sql = "SELECT * FROM users WHERE email = '$email'";
$res = mysqli_query($link, $sql);

if(!$res){
    debug_error($link);
    die();
}

$user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;
if (!$user){
	$errors['email'] = 'Такой пользователь не найден';
}

if (empty($errors)) {
	if (password_verify($form['pass'], $user['pass'])) {
		$_SESSION['user'] = $user;
	}
	else {
		$errors['pass'] = 'Неверный пароль';
	}
}

if (!empty($errors)) {
    $page_content = include_template('login.php', [
        'categories' => $categories,
        'errors' => $errors
    ]);
    $layout_content = include_template('layout.php', [
        'content'    => $page_content,
        'categories' => $categories,
        'title'      => 'Ошибка авторизации'
    ]);
    print($layout_content);
    die();    
}

header("Location: /");
exit();