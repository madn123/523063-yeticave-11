<?php
require_once 'include.php';

$id = intval($_GET['id']);

$sql = <<<SQL
    SELECT * FROM items i
    JOIN categories c ON i.category_id = c.id
    WHERE i.id = $id
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

print render('lot', 'Название лота', ['lots' => $lots]);
die();
