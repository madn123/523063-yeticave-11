<?php
require_once 'include.php';

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
