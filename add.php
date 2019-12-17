<?php
require_once 'include/include.php';

if (!is_auth()) {
    http_response_code(403);
    exit();
}

$cats_ids = [];
$cats_ids = array_column($categories, 'id');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    print render('add-lot', 'Добавление лота');
    die();
}

$required = ['category_id', 'name', 'description', 'completion_date', 'start_price', 'step_bet'];
$errors = [];

$rules = [
    'category_id' => function ($value) use ($cats_ids) {
        return validate_category($value, $cats_ids);
    },
    'name' => function ($value) {
        return validate_length($value, 5, 128);
    },
    'description' => function ($value) {
        return validate_length($value, 10, 1000);
    },
    'completion_date' => function ($value) {
        return validate_date($value);
    },
    'start_price' => function ($value) {
        return validate_number($value);
    },
    'step_bet' => function ($value) {
        return validate_number($value);
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
if (isset($_FILES['image']['name']) and !empty($_FILES['image']['name'])) {
    unset($errors['image']);
    $tmp_name = $_FILES['image']['tmp_name'];
    $path = $_FILES['image']['name'];
    $filename = uniqid();
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_type = finfo_file($finfo, $tmp_name);

    if ($file_type == 'image/jpeg' or $file_type == 'image/png') {
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
    } else {
        $errors['image'] = 'Не верный тип изображения';
    }
}

if (!empty($errors)) {
    print render('add-lot', 'Ошибка добавления', ['errors' => $errors]);
    die();
}

$user_id = get_user_id();

$sql = <<<SQL
    INSERT INTO items (date_creation, category_id, creator_user_id, name, description, image, completion_date, start_price, step_bet)
    VALUES (NOW(), ?,$user_id, ?, ?, ?, ?, ?, ?)
SQL;

do_query($link, $sql, [
    $lots['category_id'],
    $lots['name'],
    $lots['description'],
    'uploads/' . $filename,
    $lots['completion_date'],
    $lots['start_price'],
    $lots['step_bet']
]);

$lot_id = mysqli_insert_id($link);
header("Location: lot.php?id=" . $lot_id);
