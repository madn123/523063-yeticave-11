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


$page_content = include_template('add-lot.php', [
    'categories' => $categories,
    'error' => $error
]);

$layout_content = include_template('layout.php', [
	'content' => $page_content,
	'categories' => $categories,
	'title' => 'Добавить лот',
	'user_name' => $user_name,
	'is_auth' => $is_auth,
	'error' => $error
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $lot = $_POST;

        $filename = uniqid() . '.jpg';
        $lot['image'] = $filename;
        move_uploaded_file($_FILES['lot-img']['tmp_name'], 'uploads/' . $filename);

        $sql = 'INSERT INTO items (date_creation, category_id, creator_user_id, name, description, image, completion_date, step_bet) VALUES (NOW(), ?, 1, ?, ?, ?, ?, ?)';

        $stmt = db_get_prepare_stmt($link, $sql, $lot);
        $res = mysqli_stmt_execute($stmt);

        if (!$res) {
            $error = debug_error($link);
        }

        $lot_id = mysqli_insert_id($link);

        header("Location: lot.php?id=" . $lot_id);
}

print($layout_content);


