<?php
require_once 'include.php';

$cost = filter_input_array(INPUT_POST, ['cost' => FILTER_DEFAULT], true);
$errors = [];

$rule = ['cost' => function($value) {
    return validate_number($value);
    }
];

if (!empty ($rule)) {
    $errors['cost'] = $rule;
    print render('lot', 'Ошибка!', ['errors' => $errors]);
    die();
}

$sql = <<<SQL
    INSERT INTO items (start_price)
    VALUES (?)
    INSERT INTO bets (date_creation, price, user_id, item_id)
    VALUES (NOW(), ?, 1, 1)
SQL;

$stmt = db_get_prepare_stmt($link, $sql, $cost);

$res = mysqli_stmt_execute($stmt);

if(!$res){
    debug_error($link);
    die();
}

$lot_id = mysqli_insert_id($link);
header("Location: lot.php?id=" . $lot_id);
die();
