<?php
require_once 'include.php';

$cur_page = $_GET['page'] ?? 1;
$category = $_GET['category'];
$page_items = 3;

$res = mysqli_query($link, "SELECT COUNT(*) as cnt FROM items");

if (!$res) {
    $error = debug_error($link);
    die();
}

$items_count = mysqli_fetch_assoc($res)['cnt'];
$pages_count = ceil($items_count / $page_items);
$offset = ($cur_page - 1) * $page_items;

$pages = range(1, $pages_count);

$sql = 'SELECT i.id, name, start_price, image, completion_date, c.category_name FROM items i '
	. 'JOIN categories c ON i.category_id = c.id '
	. 'WHERE c.id =' . $category . ' '
	. 'ORDER BY date_creation DESC LIMIT ' . $page_items . ' OFFSET ' . $offset;

$res = mysqli_query($link, $sql);

if (!$res) {
    $error = debug_error($link);
    die();
}

$items = mysqli_query($link, $sql);

$page_content = include_template('all-lots.php', [
	'items' => $items,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page,
    'categories' => $categories
]);

$layout_content = include_template('layout.php', [
	'content' => $page_content,
	'categories' => $categories,
	'title' => 'YetiCave - Главная страница',
]);

print($layout_content);