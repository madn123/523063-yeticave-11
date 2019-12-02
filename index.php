<?php
require_once 'include.php';

$sql = <<<SQL
    SELECT i.id, name, start_price, image, completion_date, c.category_name FROM items i
    JOIN categories c ON i.category_id = c.id
    ORDER BY date_creation DESC LIMIT 6
SQL;

$res = mysqli_query($link, $sql);

if (!$res) {
    $error = debug_error($link);
    die();
}

$items = mysqli_fetch_all($res, MYSQLI_ASSOC);

print render('main', 'YetiCave - Главная страница', ['items' => $items]);
die();
