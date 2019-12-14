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

$res = do_query($link, $sql);

$items_count = mysqli_fetch_assoc($res)['cnt'];
$pages_count = ceil($items_count / $page_items);
$offset = ($cur_page - 1) * $page_items;

$pages = range(1, $pages_count);

$sql = <<<SQL
    SELECT i.* FROM items i
	JOIN categories c ON i.category_id = c.id
	WHERE c.id = $cur_cat
	ORDER BY date_creation DESC LIMIT $page_items OFFSET $offset
SQL;

$res = do_query($link, $sql);

$items = mysqli_fetch_all($res, MYSQLI_ASSOC);

array_walk($items, function (&$item){
    if (convert_time($item['completion_date']) < 0){
        $item['timer_classname'] = 'timer--end';
        $item['timer'] = 'Торги окончены';
        return $item;
    }
    if ((strtotime($item['completion_date']) - time()) < 3600){
        $item['timer_classname'] = 'timer--finishing';
        $item['timer'] = convert_time($item['completion_date']);
        return $item;
    }
    $item['timer_classname'] = '';
    $item['timer'] = convert_time($item['completion_date']);
});

print render('all-lots', 'Лоты по категориям', [
    'items' => $items,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page,
    'cur_cat' => $cur_cat,
    'categories' => $categories
]);
die();
