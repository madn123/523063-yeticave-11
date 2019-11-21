<?php

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

function link_error($link) {
    $error = mysqli_connect_error($link);
    $content = include_template('error.php', ['error' => $error]);
    print($content);
}

function debug_error($link) {
    $error = mysqli_error($link);
    $content = include_template('error.php', ['error' => $error]);
    print($content);

}

function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

function getPostVal($name) {
    return filter_input(INPUT_POST, $name);
}

function validateCategory($id, $allowed_list) {
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }

    return null;
}

function validateLength($value, $min, $max) {
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно быть от $min до $max символов";
        }
    }

    return null;
}

function validateNumber($value) {
    if (!is_numeric($value)){
        return 'Введите число';
    }

    if ((int) $value != $value){
        return 'Число должно быть целым';
    }

    return null;
}

function validateDate(string $date){
    if (!is_date_valid($date)){
        return 'Неверный формат даты';
    }
    if (strtotime($date) < time()){
        return "Выберите будущую дату";
    }
    
    return null;
}

function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

function user_exist_by_email($email, $link){
    $email = mysqli_real_escape_string($link, $email);
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);
    return mysqli_num_rows($res) > 0;
}

function validateEmail($email, $link){
    if(empty($email)){
        return 'Пустой email';
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Введите корректный email';
    }

    if(user_exist_by_email($email, $link)){
        return 'Такой email уже есть';
    }
    return null;
}