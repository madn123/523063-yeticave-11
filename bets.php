<?php
require_once 'include.php';

if (!is_auth()) {
    header("Location: /");
    exit();
}

$cur_user = get_user_id();

$sql = <<<SQL
    SELECT
           b.id,
           b.date_creation,
           b.price,
           i.id,
           i.name,
           i.image,
           i.completion_date,
           i.winner_user_id,
           uu.contacts,
           c.category_name
    FROM bets b
    JOIN items i ON b.item_id = i.id
    JOIN categories c ON i.category_id = c.id
    JOIN users u ON b.user_id = u.id
    JOIN users uu ON uu.id = i.creator_user_id
    WHERE b.user_id = $cur_user
    ORDER BY date_creation DESC
SQL;

$res = do_query($link, $sql);

$items = mysqli_fetch_all($res, MYSQLI_ASSOC);

assign_class($items);

print render('bets', 'Мои ставки', ['items' => $items]);
