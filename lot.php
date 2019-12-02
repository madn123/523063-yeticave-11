<?php
require_once 'include.php';

$id = intval($_GET['id']);

$sql = 'SELECT * FROM items i '
    . 'JOIN categories c ON i.category_id = c.id '
    . 'WHERE i.id =' . $id . '';

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

$page_content = include_template('lot.php', [
    'lots' => $lots,
    'categories' => $categories,
]);

$layout_content = include_template('layout.php', [
	'content' => $page_content,
	'categories' => $categories,
	'title' => 'Название лота'
]);

print($layout_content);


