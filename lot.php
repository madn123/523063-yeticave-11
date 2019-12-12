<?php
require_once 'include.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cost = filter_input(INPUT_POST, 'cost', FILTER_DEFAULT);
    $id = filter_input(INPUT_POST, 'id', FILTER_DEFAULT);

    $error = validate_number($cost);

    if (empty($error)) {

        $res = mysqli_query($link, "SELECT items.start_price, items.step_bet FROM items
        WHERE items.id = $id");
        if (!$res) {
            $error = debug_error($link);
            die();
        }

        $new_price = mysqli_fetch_array($res, MYSQLI_ASSOC);
        $new_price = $new_price['start_price'] + $new_price['step_bet'];

        if ($new_price < $cost) {
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

print render('lot', $lots['name'], [
    'lots' => $lots,
    'bets' => $bets,
    'new_price' => $new_price,
    'error' => $error
]);
