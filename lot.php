<?php
require_once 'include.php';

$id = intval($_GET['id']);

$sql = <<<SQL
    SELECT items.* FROM items
    LEFT JOIN categories ON items.category_id = categories.id
    WHERE items.id = $id
SQL;

$res = mysqli_query($link, $sql);

if (!$res) {
    $error = debug_error($link);
    die();
}

$lots = mysqli_fetch_assoc($res);

if (empty($lots)) {
    $content = include_template('404.php',[]);
    print($content);
    die();
}

$new_price = $lots['start_price'] + $lots['step_bet'];

$sql = <<<SQL
    SELECT bets.price, bets.date_creation, users.name FROM bets
    JOIN users ON bets.user_id = users.id
    WHERE bets.item_id = $id
    ORDER BY date_creation DESC
SQL;

$res = mysqli_query($link, $sql);

if (!$res) {
    $error = debug_error($link);
    die();
}

$bets = mysqli_fetch_all($res, MYSQLI_ASSOC);

print render('lot', 'Название лота', [
    'lots' => $lots,
    'bets' => $bets,
    'new_price' => $new_price
]);
die();
