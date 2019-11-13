<?php
require_once 'functions.php';
require_once 'config.php';

if (!$link) {
    $error = mysqli_error($link);
}

$sql = 'SELECT id, category_name, category_code FROM categories ORDER BY category_name ASC';

$result = mysqli_query($link, $sql);

if (!$result) {
    $error = debug_error($link);
}

$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (!isset($_GET['id'])) {
    $content = include_template('404.php', []);
    print($content);
    die();
}

$id = intval($_GET['id']);

$sql = 'SELECT * FROM items i '
    . 'JOIN categories c ON i.category_id = c.id '
    . 'WHERE i.id =' . $id . '';

$res = mysqli_query($link, $sql);

if (!$res) {
    $error = debug_error($link);
}

$lots = mysqli_fetch_assoc($res);

if (empty($lots)) {
    $content = include_template('404.php',[]);
    print($content);
    die();
}

$page_content = include_template('lot.php', [
    'lots' => $lots,
    'categories' => $categories,
    'error' => $error
]);

$layout_content = include_template('layout.php', [
	'content' => $page_content,
	'categories' => $categories,
	'title' => 'YetiCave - Главная страница',
	'user_name' => $user_name,
	'is_auth' => $is_auth,
	'error' => $error
]);

print($layout_content);


