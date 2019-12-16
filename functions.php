<?php

function convert_time($c_time)
{
    $diff = strtotime($c_time) - time();
    $hours = floor($diff / 60 / 60);
    $seconds = $diff - ($hours * 60 * 60);
    $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
    $seconds = floor($seconds / 60);
    $seconds = str_pad($seconds, 2, "0", STR_PAD_LEFT);
    $c_time = $hours . ':' . $seconds;
    return $c_time;
}

function edit($price)
{
    $price = ceil($price);
    $price = number_format($price, 0, '', ' ');
    $price .= " " . "₽";
    return $price;
}

function include_template($name, $data)
{
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

function do_query($link, $sql, $params = array())
{
    if (!empty($params)) {
        $stmt = db_get_prepare_stmt($link, $sql, $params);
        $res = mysqli_stmt_execute($stmt);
    } else {
        $res = mysqli_query($link, $sql);
    }

    if (!$res) {
        debug_error($link);
        die();
    }

    return $res;
}

function link_error($link)
{
    $error = mysqli_connect_error($link);
    $content = include_template('error.php', ['error' => $error]);
    print($content);
}

function debug_error($link)
{
    $error = mysqli_error($link);
    $content = include_template('error.php', ['error' => $error]);
    print($content);

}

function render($template, $title, $data = [])
{
    global $categories;
    $data['categories'] = $categories;

    $page_content = include_template($template . '.php', $data);
    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'categories' => $categories,
        'title' => $title
    ]);
    return $layout_content;
}

function db_get_prepare_stmt($link, $sql, $data = [])
{
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
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
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

function get_post_val($name)
{
    return filter_input(INPUT_POST, $name);
}

function validate_category($id, $allowed_list)
{
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }

    return null;
}

function validate_length($value, $min, $max)
{
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно быть от $min до $max символов";
        }
    }

    return null;
}

function validate_number($value)
{
    if (!is_numeric($value)) {
        return 'Введите число';
    }

    if ((int)$value != $value) {
        return 'Число должно быть целым';
    }

    return null;
}

function validate_date(string $date)
{
    if (!is_date_valid($date)) {
        return 'Неверный формат даты';
    }
    if (strtotime($date) < time()) {
        return "Выберите будущую дату";
    }

    return null;
}

function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

function user_exist_by_email($email, $link)
{
    $email = mysqli_real_escape_string($link, $email);
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);
    return mysqli_num_rows($res) > 0;
}

function validate_email($email, $link)
{
    if (empty($email)) {
        return 'Пустой email';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Введите корректный email';
    }

    if (user_exist_by_email($email, $link)) {
        return 'Такой email уже есть';
    }
    return null;
}

function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

function format_date($date)
{
    $new_date = strtotime($date);
    $date_diff = time() - $new_date;

    if ($date_diff < 60) {
        return 'Только что';
    } elseif ($date_diff < 3600) {
        $min = floor($date_diff / 60);
        $new_date = $min . ' ' . get_noun_plural_form($min,
                'минуту назад',
                'минуты назад',
                'минут назад'
            );
        return $new_date;
    } elseif ($date_diff < 86400) {
        $hours = floor($date_diff / 60 / 60);
        $new_date = $hours . ' ' . get_noun_plural_form($hours,
                'час назад',
                'часа назад',
                'часов назад'
            );
        return $new_date;
    } else {
        $date = date_create($date);
        $new_date = date_format($date, 'd.m.y') . ' в ' . date_format($date, 'H:i');
        return $new_date;
    }
}
