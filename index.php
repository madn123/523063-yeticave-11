<?php
$is_auth = rand(0, 1);
$user_name = 'Кирилл';
$categories = ['Доски и лыжи' , 'Крепления' , 'Ботинки' , 'Одежда' , 'Инструменты' , 'Разное'];
$products = [
	[
		'name' => '2014 Rossignol District Snowboard',
		'cats' => 'Доски и лыжи',
		'price' => '10999',
		'img' => 'img/lot-1.jpg',
		'date' => '2019-11-01'
	],
	[
		'name' => 'DC Ply Mens 2016/2017 Snowboard',
		'cats' => 'Доски и лыжи',
		'price' => '159999',
		'img' => 'img/lot-2.jpg',
		'date' => '2019-11-03'
	],
	[
		'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
		'cats' => 'Крепления',
		'price' => '8000',
		'img' => 'img/lot-3.jpg',
		'date' => '2019-11-05'
	],
	[
		'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
		'cats' => 'Ботинки',
		'price' => '10999',
		'img' => 'img/lot-4.jpg',
		'date' => '2019-11-02'
	],
	[
		'name' => 'Куртка для сноуборда DC Mutiny Charocal',
		'cats' => 'Одежда',
		'price' => '7500',
		'img' => 'img/lot-5.jpg',
		'date' => '2019-11-04'
	],
	[
		'name' => 'Маска Oakley Canopy',
		'cats' => 'Разное',
		'price' => '5400',
		'img' => 'img/lot-6.jpg',
		'date' => '2019-11-03'
	],
];
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

$page_content = include_template('main.php', [
	'categories' => $categories,
	'products' => $products
]);
$layout_content = include_template('layout.php', [
	'content' => $page_content,
	'categories' => $categories,
	'title' => 'YetiCave - Главная страница',
	'user_name' => $user_name,
	'is_auth' => $is_auth
]);

print($layout_content);

