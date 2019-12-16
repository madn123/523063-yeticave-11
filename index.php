<?php
require_once 'include.php';
require_once 'winner.php';

$sql = <<<SQL
    SELECT i.*, c.category_name FROM items i
    JOIN categories c ON i.category_id = c.id
    WHERE i.winner_user_id IS NULL
    ORDER BY date_creation DESC LIMIT 6
SQL;

$res = do_query($link, $sql);

$items = mysqli_fetch_all($res, MYSQLI_ASSOC);

assign_class($items);

print render('main', 'YetiCave - Главная страница', ['items' => $items]);
die();
