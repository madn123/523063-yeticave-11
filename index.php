<?php
$is_auth = rand(0, 1);
$user_name = 'Кирилл';
$categories = ['Доски и лыжи' , 'Крепления' , 'Ботинки' , 'Одежда' , 'Инструменты' , 'Разное'];
$products = [
	[
		'name' => '2014 Rossignol District Snowboard',
		'cats' => 'Доски и лыжи',
		'price' => '10999',
		'img' => 'img/lot-1.jpg'

	],
	[
		'name' => 'DC Ply Mens 2016/2017 Snowboard',
		'cats' => 'Доски и лыжи',
		'price' => '159999',
		'img' => 'img/lot-2.jpg'

	],
	[
		'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
		'cats' => 'Крепления',
		'price' => '8000',
		'img' => 'img/lot-3.jpg'

	],
	[
		'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
		'cats' => 'Ботинки',
		'price' => '10999',
		'img' => 'img/lot-4.jpg'

	],
	[
		'name' => 'Куртка для сноуборда DC Mutiny Charocal',
		'cats' => 'Одежда',
		'price' => '7500',
		'img' => 'img/lot-5.jpg'

	],
	[
		'name' => 'Маска Oakley Canopy',
		'cats' => 'Разное',
		'price' => '5400',
		'img' => 'img/lot-6.jpg'

	],
];

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

