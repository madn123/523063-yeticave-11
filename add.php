<?php
require_once 'include.php';

if (!isset($_SESSION['user'])) {
    http_response_code (403);
    exit();
}

$cats_ids = [];
$cats_ids = array_column($categories, 'id');

if($_SERVER['REQUEST_METHOD'] != 'POST') {
    $page_content = include_template('add-lot.php', ['categories' => $categories]);
    $layout_content = include_template('layout.php', [
        'content'    => $page_content,
        'categories' => $categories
    ]);
    print($layout_content);
    die();    
}

$required = ['category_id', 'name', 'description', 'completion_date', 'start_price', 'step_bet'];
$errors = [];

$rules = [
    'category_id' => function($value) use ($cats_ids) {
        return validateCategory($value, $cats_ids);
    },
    'name' => function($value) {
        return validateLength($value, 5, 128);
    },
    'description' => function($value) {
        return validateLength($value, 10, 1000);
    },
    'completion_date' => function($value) {
        return validateDate($value);
    },            
    'start_price' => function($value) {
        return validateNumber($value);
    },
    'step_bet' => function($value) {
        return validateNumber($value);
    }
];

$lots = filter_input_array(INPUT_POST, [
    'category_id' => FILTER_DEFAULT,
    'name' => FILTER_DEFAULT,
    'description' => FILTER_DEFAULT,
    'completion_date' => FILTER_DEFAULT,
    'start_price' => FILTER_DEFAULT,
    'step_bet' => FILTER_DEFAULT
], true);

foreach ($lots as $key => $value) {
    if (isset($rules[$key])) {
        $rule = $rules[$key];
        $errors[$key] = $rule($value);
    }

    if (in_array($key, $required) && empty($value)) {
        $errors[$key] = "Поле $key надо заполнить";
    }
}

$errors = array_filter($errors);

$errors['image'] = 'Вы не загрузили файл';
if( isset($_FILES['image']['name']) and !empty($_FILES['image']['name']) ){
    unset($errors['image']);
    $tmp_name = $_FILES['image']['tmp_name'];
    $path = $_FILES['image']['name'];
    $filename = uniqid();
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_type = finfo_file($finfo, $tmp_name);

    if($file_type == 'image/jpeg' or $file_type == 'image/png'){
        switch ($file_type) {
            case 'image/jpeg':
                $filename .= '.jpeg';
                break;
            
            case 'image/png':
                $filename .= '.png';
                break;
        }
        move_uploaded_file($tmp_name, __DIR__ . '/uploads/' . $filename);
        $lots['path'] = $filename;
    } 
    else {
        $errors['image'] = 'Не верный тип изображения';
    }
}

if (!empty($errors)) {
    $page_content = include_template('add-lot.php', [
        'lots' => $lots, 
        'errors' => $errors, 
        'categories' => $categories
    ]);

    $layout_content = include_template('layout.php', [
        'content'    => $page_content,
        'categories' => $categories
    ]);
    print($layout_content);
    die();
}

$user_id = $_SESSION['user']['id'];

$sql = 'INSERT INTO items (date_creation, category_id, creator_user_id, name, description, image, completion_date, start_price, step_bet) VALUES (NOW(), ?, $user_id, ?, ?, ?, ?, ?, ?)';
$stmt = db_get_prepare_stmt($link, $sql, [
    $lots['category_id'],
    $lots['name'], 
    $lots['description'], 
    'uploads/' . $filename,
    $lots['completion_date'],
    $lots['start_price'], 
    $lots['step_bet']
]);

$res = mysqli_stmt_execute($stmt);

if(!$res){
    debug_error($link);
    die();
}

$lot_id = mysqli_insert_id($link);
header("Location: lot.php?id=" . $lot_id);
die();