<?php
require_once 'include.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$cur_user = $_SESSION['user']['id'];

$sql = <<<SQL
    SELECT b.id, b.date_creation, b.price, i.id, i.name, i.image, i.completion_date, i.winner_user_id, c.category_name FROM bets b
    JOIN items i ON b.item_id = i.id
    JOIN categories c ON i.category_id = c.id
    JOIN users u ON b.user_id = u.id
    WHERE b.user_id = $cur_user
    ORDER BY date_creation DESC
SQL;

$res = do_query($link, $sql);

$items = mysqli_fetch_all($res, MYSQLI_ASSOC);

foreach ($items as $item) {
    $sql = <<<SQL
    SELECT users.contacts FROM users
    JOIN items ON users.id = items.creator_user_id
	WHERE items.id = $item
SQL;
    $res = do_query($link, $sql);
    $items = mysqli_fetch_assoc($res);

}


assign_class($items);

print render('bets', 'Мои ставки', ['items' => $items]);
die();
