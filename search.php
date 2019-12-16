<?php
require_once 'include.php';

$search = filter_input(INPUT_GET, 'search', FILTER_DEFAULT);

if (empty($search)) {
    print render('search/search', 'Ошибка!', ['errors' => $errors]);
    die();
}

$sql = <<<SQL
    SELECT i.*, c.category_name FROM items i
    JOIN categories c ON i.category_id = c.id
    WHERE MATCH(name, description) AGAINST(?)
    ORDER BY date_creation DESC LIMIT 9
SQL;

$res = do_query($link, $sql, [$search]);
$stmt = db_get_prepare_stmt($link, $sql, [$search]);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!$res) {
    $error = debug_error($link);
    die();
}

$items = mysqli_fetch_all($res, MYSQLI_ASSOC);

array_walk($items, function (&$item) {
    if ($item['winner_user_id'] == $_SESSION['user']['id']) {
        $item['timer_classname'] = 'timer--win';
        $item['timer'] = 'Ставка выиграла';
        return $item;
    }
    if (convert_time($item['completion_date']) < 0) {
        $item['timer_classname'] = 'timer--end';
        $item['timer'] = 'Торги окончены';
        return $item;
    }
    if ((strtotime($item['completion_date']) - time()) < 3600) {
        $item['timer_classname'] = 'timer--finishing';
        $item['timer'] = convert_time($item['completion_date']);
        return $item;
    }
    $item['timer_classname'] = '';
    $item['timer'] = convert_time($item['completion_date']);
});

print render('search/search', 'Поиск лота', [
    'items' => $items,
    'search' => $search
]);
die();
