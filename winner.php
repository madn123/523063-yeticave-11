<?php
require_once 'include.php';
// 1. Найти все лоты без победителей, дата истечения которых меньше или равна текущей дате.
// 2. Для каждого такого лота найти последнюю ставку.
// 3. Записать в лот победителем автора последней ставки.

$date = date('Y-m-d H:i:s');

$sql = <<<SQL
    SELECT items.id, completion_date FROM items
    WHERE winner_user_id IS NULL AND completion_date <= '$date'
SQL;

$res = mysqli_query($link, $sql);
$lots = mysqli_fetch_all($res, MYSQLI_ASSOC);

foreach ($lots as $lot){
    $sql = <<<SQL
        SELECT bets.user_id FROM bets
        WHERE bets.item_id = {$lot['id']}
        ORDER BY bets.date_creation DESC LIMIT 1
SQL;

    $res = mysqli_query($link, $sql);
    $user = mysqli_fetch_assoc($res);

    $sql = "UPDATE items SET winner_user_id = {$user['user_id']} WHERE id = {$lot['id']}";
    do_query($link, $sql);
}


