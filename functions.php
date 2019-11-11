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

function debug_error($link) {
    $error = mysqli_error($link);
    $content = include_template('error.php', ['error' => $error]);
    print($content);

}