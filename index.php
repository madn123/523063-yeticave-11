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

array_walk($items, function (&$item) {
    if (convert_time($item['completion_date']) < 0) {
        $item['timer_classname'] = 'timer--end';
        $item['timer'] = 'Торги окончены';
        return $item;
    }
    if ((strtotime($item['completion_date']) - time()) < 3600) {
        $item['timer_classname'] = 'timer--finishing';
        $item['timer'] = convert_time($item['completion_date']);
        return $item;
    }
    $item['timer_classname'] = '';
    $item['timer'] = convert_time($item['completion_date']);
});

print render('main', 'YetiCave - Главная страница', ['items' => $items]);
die();
