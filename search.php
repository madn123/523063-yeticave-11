<?php
require_once 'include.php';

$search = filter_input(INPUT_GET, 'search', FILTER_DEFAULT);

if (empty($search)) {
    print render('search/search', 'Ошибка!', ['errors' => $errors]);
    die();
}

$sql = 'SELECT i.id, name, start_price, image, completion_date, c.category_name FROM items i '
    . 'JOIN categories c ON i.category_id = c.id '
    . 'WHERE MATCH(name, description) AGAINST(?)';

$stmt = db_get_prepare_stmt($link, $sql, [$search]);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!$res) {
    $error = debug_error($link);
    die();
}

$items = mysqli_fetch_all($res, MYSQLI_ASSOC);

print render('search/search', 'Поиск лота', [
    'items' => $items,
    'search' => $search
]);
die();
