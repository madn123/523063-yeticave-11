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

$res = mysqli_query($link, $sql);

if (!$res) {
    $error = debug_error($link);
    die();
}

$items = mysqli_fetch_all($res, MYSQLI_ASSOC);

print render('bets', 'Мои ставки', ['items' => $items]);
die();
