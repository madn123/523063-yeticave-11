<?php
require_once 'functions.php';
require_once 'config.php';

if (!$link) {
    $error = mysqli_error($link);
}

$sql = 'SELECT id, category_name, category_code FROM categories ORDER BY category_name ASC';

$result = mysqli_query($link, $sql);

$cats_ids = [];

if (!$result) {
    $error = debug_error($link);
}

$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

$cats_ids = array_column($categories, 'id');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $required = ['category_id', 'name', 'description', 'completion_date', 'start_price', 'step_bet'];
    $errors = [];

    $rules = [
    'category_id' => function($value) use ($cats_ids) {
        return validateCategory($value, $cats_ids);
    },
    'name' => function($value) {
        return validateLength($value, 10, 128);
    },
    'description' => function($value) {
        return validateLength($value, 10, 1000);
    },
    'completion_date' => function($value) {
        return is_date_valid($value);
    },            
    'start_price' => function($value) {
        return validateLength($value, 1, 11);
    },
    'step_bet' => function($value) {
        return validateLength($value, 1, 11);
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

    if (!empty($_FILES['image']['name'])) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $path = $_FILES['image']['name'];
        $filename = uniqid() . '.jpg';

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== "image/jpg") {
            $errors['file'] = 'Загрузите картинку в формате JPG';
        }
        else {
            move_uploaded_file($tmp_name, __DIR__ . '/uploads/' . $filename);
            $lots['path'] = $filename;
        }
    }
    else {
        $errors['file'] = 'Вы не загрузили файл';
    }

    if (count($errors)) {
        $page_content = include_template('add-lot.php', [
            'lots' => $lots, 
            'errors' => $errors, 
            'categories' => $categories
        ]);
    }

    else {
        $sql = 'INSERT INTO items (date_creation, category_id, creator_user_id, name, description, image, completion_date, start_price, step_bet) VALUES (NOW(), ?, 1, ?, ?, ?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, [
            $category_id,
            $name, 
            $description, 
            'uploads/' . $filename,
            $completion_date,
            $start_price, 
            $step_bet
        ]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($link);

            header("Location: add.php?id=" . $lot_id);
        }
    }
}
else {
    $page_content = include_template('add-lot.php', ['categories' => $categories]);
}

$layout_content = include_template('layout.php', [
    'content'    => $page_content,
    'categories' => []
]);

print($layout_content);

