<?php
require_once 'include.php';

if (isset($_SESSION['user'])) {
        header("Location: /");
        exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $required = ['email', 'pass'];
    $errors = [];

    foreach ($required as $field) {
        if (empty($form[$field])) {
            $errors[$field] = "Не заполнено поле " . $field;
        }
    }
}

if(!empty($errors) or $_SERVER['REQUEST_METHOD'] != 'POST') {
    print render('login.php', 'Авторизация', ['errors' => $errors]);
    die();
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
	$errors['email'] = 'Не верный логин или пароль';
}

if (empty($errors)) {
	if (password_verify($form['pass'], $user['pass'])) {
		$_SESSION['user'] = $user;
	}
	else {
		$errors['pass'] = 'Не верный логин или пароль';
	}
}

if (!empty($errors)) {
    print render('login', 'Ошибка авторизации', ['errors' => $errors]);
    die();
}

header("Location: /");
exit();
