<?php
require_once 'include.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cost = filter_input(INPUT_POST, 'cost', FILTER_DEFAULT);
    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_DEFAULT);

    $error = validate_number($cost);

    if (!empty($error)) {
        $error = 'Заполните поле';
        print render('lot', 'Ошибка!', ['error' => $error]);
        die();
    }

    $sql = <<<SQL
    SELECT items.start_price, items.step_bet FROM items
    WHERE items.id = $item_id
SQL;

    $res = mysqli_query($link, $sql);

    if (!$res) {
        $error = debug_error($link);
        die();
    }

    $new_price = mysqli_fetch_array($res, MYSQLI_ASSOC);
    $new_price = $new_price['start_price'] + $new_price['step_bet'];

    if ($new_price > $cost){
        $error = 'Ваша ставка должна быть больше минимальной';
        print render('lot', 'Ошибка!', ['error' => $error]);
        die();
    }

    $sql = <<<SQL
    INSERT INTO bets (date_creation, price, user_id, item_id)
    VALUES (NOW(), ?, ?, ?)
SQL;

    $stmt = db_get_prepare_stmt($link, $sql, [$cost, $_SESSION['user']['id'], $item_id]);
    $res = mysqli_stmt_execute($stmt);

    if(!$res){
        debug_error($link);
        die();
    }

    $sql = <<<SQL
    UPDATE items SET start_price = ?
    WHERE id = ?
SQL;

    $stmt = db_get_prepare_stmt($link, $sql, [$cost, $item_id]);
    $res = mysqli_stmt_execute($stmt);

    if(!$res){
        debug_error($link);
        die();
    }

    header("Location: lot.php?id=" . $item_id);
    die();
}

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

print render('lot', $lots['name'], [
    'lots' => $lots,
    'bets' => $bets,
    'new_price' => $new_price
]);
