<?php
require_once 'include.php';

$search = $_GET['search'];

if (empty($search)) {
    $layout_content = include_template('layout.php', [
        'categories' => $categories,
        'title'      => 'Ошибка!'
    ]);
    print($layout_content);
    die();    
}

$sql = 'SELECT i.id, name, start_price, image, completion_date, c.category_name FROM items i '
    . 'JOIN categories c ON i.category_id = c.id '
    . "WHERE MATCH(name, description) AGAINST(?)";

$stmt = db_get_prepare_stmt($link, $sql, [$search]);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!$res) {
    $error = debug_error($link);
    die();
}

$items = mysqli_fetch_all($res, MYSQLI_ASSOC);

$page_content = include_template('search.php', [
    'categories' => $categories,
    'items' => $items
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Поиск',
]);

print($layout_content);