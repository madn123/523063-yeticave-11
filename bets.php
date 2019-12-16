<?php
require_once 'include.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$cur_user = $_SESSION['user']['id'];

$sql = <<<SQL
    SELECT b.id, b.date_creation, b.price, i.id, i.name, i.image, i.completion_date, i.winner_user_id, c.category_name, u.contacts FROM bets b
    JOIN items i ON b.item_id = i.id
    JOIN categories c ON i.category_id = c.id
    JOIN users u ON b.user_id = u.id
    WHERE b.user_id = $cur_user
    ORDER BY date_creation DESC
SQL;

$res = do_query($link, $sql);

$items = mysqli_fetch_all($res, MYSQLI_ASSOC);

array_walk($items, function (&$item) {
    if ($item['winner_user_id'] == $_SESSION['user']['id']) {
        $item['bet_classname'] = 'rates__item--win';
        $item['timer_classname'] = 'timer--win';
        $item['timer'] = 'Ставка выиграла';
        return $item;
    }
    if (convert_time($item['completion_date']) < 0) {
        $item['bet_classname'] = 'rates__item--end';
        $item['timer_classname'] = 'timer--end';
        $item['timer'] = 'Торги окончены';
        return $item;
    }
    if ((strtotime($item['completion_date']) - time()) < 3600) {
        $item['timer_classname'] = 'timer--finishing';
        $item['timer'] = convert_time($item['completion_date']);
        return $item;
    }
    $item['bet_classname'] = '';
    $item['timer_classname'] = '';
    $item['timer'] = convert_time($item['completion_date']);
});

print render('bets', 'Мои ставки', ['items' => $items]);
die();
