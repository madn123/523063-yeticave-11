<?php
$is_auth = rand(0, 1);
$user_name = 'Кирилл';

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

function conver_time($с_time) {
    $diff = strtotime($с_time) - time();
    $hours = floor($diff / 60 / 60);
    $seconds = $diff - ($hours * 60 * 60);
    $hours = str_pad ($hours, 2, "0", STR_PAD_LEFT);
    $seconds = floor($seconds / 60);
    $seconds = str_pad ($seconds, 2, "0", STR_PAD_LEFT);
    $с_time = $hours . ':' . $seconds;
    return $с_time;
}

function edit($price) {
    $price = ceil($price);
	$price = number_format($price, 0, '', ' ');
    $price .= " " . "₽";
	return $price;
}

function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

$link = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($link, "utf8");
if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
}
else {
    $sql = 'SELECT id, category_name, category_code FROM categories';
    $result = mysqli_query($link, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($link);
        $content = include_template('error.php', ['error' => $error]);
    }
    $sql = 'SELECT name, start_price, image, completion_date, category_name FROM items i'
         . 'JOIN categories c ON i.category_id = c.id'
         . 'ORDER BY date_creation ASC DESC LIMIT 9';

    if ($res = mysqli_query($link, $sql)) {
        $items = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    else {
        $content = include_template('error.php', ['error' => mysqli_error($link)]);
    }
}

$page_content = include_template('main.php', [
	'categories' => $categories,
	'items' => $items
]);
$layout_content = include_template('layout.php', [
	'content' => $page_content,
	'categories' => $categories,
	'title' => 'YetiCave - Главная страница',
	'user_name' => $user_name,
	'is_auth' => $is_auth,
	'error' => $error
]);
print($layout_content);

