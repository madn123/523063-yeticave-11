<?php
require_once 'include.php';
// 1. Найти все лоты без победителей, дата истечения которых меньше или равна текущей дате.
// 2. Для каждого такого лота найти последнюю ставку.
// 3. Записать в лот победителем автора последней ставки.

$date = date_create('now');
$date = date_format($date, 'Y-m-d H:i:s');

//Находим лоты без победителя
$sql = <<<SQL
    SELECT items.id FROM items
    WHERE winner_user_id IS NULL AND completion_date <= '$date'
SQL;

$res = mysqli_query($link, $sql);

if (!$res) {
    $error = debug_error($link);
    die();
}

$lots_id = mysqli_fetch_all($res, MYSQLI_ASSOC);

//Делаем запрос к бд. Для каждого из определенных лотов находим последнюю ставку. Определяем автора ставки.
$sql = <<<SQL
    SELECT bets.user_id FROM bets
    WHERE bets.item_id = ?
    ORDER BY bets.date_creation DESC LIMIT 1
SQL;

$res = mysqli_query($link, $sql);

if (!$res) {
    $error = debug_error($link);
    die();
}

$users_id = mysqli_fetch_all($res, MYSQLI_ASSOC);

//Записываем авторов ставки в лот как победителя.
do_query($link, "UPDATE items SET winner_user_id = ? WHERE id = ?");

print_r($lots);
die();

