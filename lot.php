<?php
require_once 'include.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cost = filter_input(INPUT_POST, 'cost', FILTER_DEFAULT);
    $id = filter_input(INPUT_POST, 'id', FILTER_DEFAULT);

    $error = validate_number($cost);

    if (empty($error)) {
        $res = do_query($link, "SELECT items.start_price, items.step_bet FROM items
        WHERE items.id = $id");

        $new_price = mysqli_fetch_array($res, MYSQLI_ASSOC);
        $new_price = $new_price['start_price'] + $new_price['step_bet'];

        if ($new_price <= $cost) {
            do_query($link, "INSERT INTO bets (date_creation, price, user_id, item_id)
            VALUES (NOW(), ?, ?, ?)", [$cost, $_SESSION['user']['id'], $id]);
            do_query($link, "UPDATE items SET start_price = ?
            WHERE id = ?", [$cost, $id]);

            header("Location: lot.php?id=" . $id);
            die();
        }
        $error = 'Ваша ставка должна быть выше минимальной';
    }
}

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

$sql = <<<SQL
    SELECT items.*, categories.category_name FROM items
    LEFT JOIN categories ON items.category_id = categories.id
    WHERE items.id = $id
SQL;

$res = do_query($link, $sql);

$lot = mysqli_fetch_assoc($res);

if (empty($lot)) {
    $content = include_template('404.php',[]);
    print($content);
    die();
}

$new_price = $lot['start_price'] + $lot['step_bet'];

$sql = <<<SQL
    SELECT bets.*, users.name FROM bets
    JOIN users ON bets.user_id = users.id
    WHERE bets.item_id = $id
    ORDER BY date_creation DESC
SQL;

$res = do_query($link, $sql);

$bets = mysqli_fetch_all($res, MYSQLI_ASSOC);

if(!isset($_SESSION['user'])){
    $display_lot = 'style="display:none"';
}
elseif (($_SESSION['user']['id']) == ($lot['creator_user_id'])){
    $display_lot = 'style="display:none"';
}
elseif (($_SESSION['user']['id']) == ($bets['0']['user_id'])){
    $display_lot = 'style="display:none"';
}
elseif (convert_time($lot['completion_date']) < 0){
    $display_lot = 'style="display:none"';
}

print render('lot', $lot['name'], [
    'lot' => $lot,
    'bets' => $bets,
    'new_price' => $new_price,
    'display_lot' => $display_lot,
    'error' => $error
]);
