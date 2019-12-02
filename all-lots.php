<?php
require_once 'include.php';

$cur_page = intval($_GET['page'] ?? 1);
$cur_cat = intval($_GET['category'] ?? null);

$page_items = 6;

$sql = <<<SQL
    SELECT COUNT(*) as cnt FROM items i
	JOIN categories c ON i.category_id = c.id
	WHERE c.id = $cur_cat
SQL;

$res = mysqli_query($link, $sql);

if (!$res) {
    $error = debug_error($link);
    die();
}

$items_count = mysqli_fetch_assoc($res)['cnt'];
$pages_count = ceil($items_count / $page_items);
$offset = ($cur_page - 1) * $page_items;

$pages = range(1, $pages_count);

$sql = <<<SQL
    SELECT i.id, name, start_price, image, completion_date, c.category_name FROM items i
	JOIN categories c ON i.category_id = c.id
	WHERE c.id = $cur_cat
	ORDER BY date_creation DESC LIMIT $page_items OFFSET $offset
SQL;

$res = mysqli_query($link, $sql);

if (!$res) {
    $error = debug_error($link);
    die();
}

$items = mysqli_query($link, $sql);

print render('all-lots', 'Лоты по категориям', [
    'items' => $items,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page,
    'cur_cat' => $cur_cat,
    'categories' => $categories
]);
die();
