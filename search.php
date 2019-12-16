<?php
require_once 'include.php';

$search = filter_input(INPUT_GET, 'search', FILTER_DEFAULT);
$cur_page = intval($_GET['page'] ?? 1);

if (empty($search)) {
    print render('search/search', 'Ошибка!', ['errors' => $errors]);
    die();
}

$page_items = 9;

$sql = <<<SQL
    SELECT COUNT(*) as cnt FROM items i
	WHERE MATCH(name, description) AGAINST (?)
SQL;

$res = do_query($link, $sql, [$search]);
$stmt = db_get_prepare_stmt($link, $sql, [$search]);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);


$items_count = mysqli_fetch_assoc($res)['cnt'];
$pages_count = ceil($items_count / $page_items);
$offset = ($cur_page - 1) * $page_items;
$pages = range(1, $pages_count);

$sql = <<<SQL
    SELECT i.*, c.category_name FROM items i
    JOIN categories c ON i.category_id = c.id
    WHERE MATCH (name, description) AGAINST (?)
    ORDER BY date_creation DESC LIMIT $page_items OFFSET $offset
SQL;

$res = do_query($link, $sql, [$search]);
$stmt = db_get_prepare_stmt($link, $sql, [$search]);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!$res) {
    $error = debug_error($link);
    die();
}

$items = mysqli_fetch_all($res, MYSQLI_ASSOC);

assign_class($items);

print render('search/search', 'Поиск лота', [
    'items' => $items,
    'search' => $search,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page,
    'cur_cat' => $cur_cat,
    'categories' => $categories
]);
die();
