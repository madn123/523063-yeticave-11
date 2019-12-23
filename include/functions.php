<?php

/**
 * Конвертирует дату в формат ЧЧ:ММ
 * @param string $c_time Начальное значение даты в виде строки
 * @return string Отформатированная дата
 */
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

/**
 * Форматирует цену: Добавляет разделитель для тысяч и знак рубля
 * @param integer $price Начальное значение цены В виде целого числа
 * @return string Отформатированная цена в виде строки
 */
function edit_price($price)
{
    $price = ceil($price);
    $price = number_format($price, 0, '', ' ');
    $price .= " " . "₽";
    return $price;
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
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

/**
 * Передает данные в шаблон для подключения.
 * @param string $template Название шаблона в види строки
 * @param string $title Название страницы в виде строки
 * @param array $data Массив с данными для вывода в шаблоне
 * @return string $layout_content Возвращает шаблоны с внесенными данными.
 */
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

/**
 * Проводит очистку пользовательских данных перед их выводом
 * @param string $html Пользовательские данные из шаблона
 * @return string Возвращает отформатированные пользовательские данные.
 */
function html_encode($html)
{
    $html = strip_tags($html);
    $html = htmlspecialchars($html);
    return $html;
}

/**
 * Возвращает данные с описанием последней ошибки при обращении к БД. Подключаем шаблон для вывода ошибки.
 * @param mysqli $link Идентификатор соединения
 */
function debug_error($link)
{
    $error = mysqli_error($link);
    $content = include_template('error.php', ['error' => $error]);
    print($content);
}

/**
 * Делаем запрос к базе данных, если параметр $params пустой. В случае неудачи - возвращаем ошибку.
 * В ином случае, выполняем подготовленный запрос.
 * @param mysqli $link Идентификатор соединения
 * @param string $sql Данные для запроса
 * @param array $params Массив с данными для подготовленного запроса
 * @return string $res Возвращает объект mysqli_result.
 */
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

/**
 * Возвращает описание последней ошибки подключения. Выводит ее в шаблоне.
 * @param mysqli $link Идентификатор соединения
 */
function link_error($link)
{
    $error = mysqli_connect_error($link);
    $content = include_template('error.php', ['error' => $error]);
    print($content);
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 * @param mysqli $link Идентификатор соединения
 * @param string $sql SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 * @return mysqli_stmt Подготовленное выражение
 */
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

/**
 * Получает имя юзера из глобального массива $_SESSION.
 * @return string Возвращает имя юзера в виде строки, либо null
 */
function get_user_name()
{
    if (isset($_SESSION['user']['name']) and !empty($_SESSION['user']['name'])) {
        return $_SESSION['user']['name'];
    }
    return null;
}

/**
 * Получает id юзера из глобального массива $_SESSION.
 * @return integer Возвращает числовое значение id, либо null
 */
function get_user_id()
{
    if (isset($_SESSION['user']['id']) and !empty($_SESSION['user']['id']) and (int)($_SESSION['user']['id']) > 0) {
        return (int)($_SESSION['user']['id']);
    }
    return null;
}

/**
 * Проверяет авторизован ли пользователь.
 * @return bool Возвращает булевое значение
 */
function is_auth()
{
    $user_id = get_user_id();
    return $user_id > 0;
}

/**
 * Фильтрует переменную
 * @param string $name Переменная в виде строки
 * @return string Отфильтрованнные данные
 */
function get_post_val($name)
{
    return filter_input(INPUT_POST, $name);
}

/**
 * Проверяет валидность категории.
 * @param integer $id id категории
 * @return string Возвращает ошибку валидности, либо пустоту, если категория корректна.
 */
function validate_category($id, $allowed_list)
{
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }

    return null;
}

/**
 * Проверяет длину значения.
 * @param string $value Значение для проверки
 * @param integer $min Значение минимального колиества символов
 * @param integer $max Значение Максимального колиества символов
 * @return string Возвращает ошибку длины значения, либо пустоту, если значение корректно.
 */
function validate_length($value, $min, $max)
{
    $len = strlen($value);
    if ($len < $min or $len > $max) {
        return "Значение должно быть от $min до $max символов";
    }

    return null;
}

/**
 * Проверяет является ли значение целым числом
 * @param integer $value Значение для проверки
 * @return string Возвращает ошибку если хначение не числовое, либо дробное. В ином случаем - возвращает пустоту
 */
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

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 * @param string $date Дата в виде строки
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Выводит ошибки, если переданная дата не соответствует формату 'ГГГГ-ММ-ДД', либо является прошедшей датой.
 * @param string $date Дата в виде строки
 * @return string Возвращает ошибку если валидация не прошла. В ином случаем - возвращает пустоту
 */
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

/**
 * Проверяет есть ли переданный email в БД
 * @param string $email email в виде строки
 * @param mysqli $link Идентификатор соединения
 * @return bool true в зависимости от наличия email в БД
 */
function user_exist_by_email($email, $link)
{
    $email = mysqli_real_escape_string($link, $email);
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);
    return mysqli_num_rows($res) > 0;
}

/**
 * Выводит ошибки по валидации email. Проверяет на пустоту, корректность, наличие в БД.
 * @param string $email email в виде строки
 * @param mysqli $link Идентификатор соединения
 * @return string Возвращает ошибку если валидация не прошла. В ином случаем - возвращает пустоту
 */
function validate_email($email, $link)
{
    if (empty($email)) {
        return 'Не заполнено поле "E-mail"';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Введите корректный E-mail';
    }

    if (user_exist_by_email($email, $link)) {
        return 'Такой E-mail уже есть';
    }
    return null;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 * @return string Рассчитанная форма множественнго числа
 */
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

/**
 * Форматирует переданную дату в определенный вид, зависящий от прошедшего времени.
 * @param string $date Дата в виде строки
 * @return string $date Возвращает дату в новом формате
 */
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
    }

    $date = date_create($date);
    $new_date = date_format($date, 'd.m.y') . ' в ' . date_format($date, 'H:i');
    return $new_date;
}

/**
 * Принимает массив с данными о лоте и назначает css свойства, в зависимости от оработанных параметров.
 * @param array $items Массив с данными.
 * @return array Возвращает массив с определенными классами.
 */
function assign_class(&$items)
{
    array_walk($items, function (&$item) {
        if ((!empty($_SESSION['user'])) and $item['winner_user_id'] === $_SESSION['user']['id']) {
            $item['bet_classname'] = 'rates__item--win';
            $item['timer_classname'] = 'timer--win';
            $item['timer'] = 'Ставка выиграла';
            return $item;
        }
        if (convert_time($item['completion_date']) < 0) {
            $item['bet_classname'] = 'rates__item--end';
            $item['timer_classname'] = 'timer--end';
            $item['timer'] = 'Торги окончены';
            return $item;
        }
        if ((strtotime($item['completion_date']) - time()) < 3600) {
            $item['timer_classname'] = 'timer--finishing';
            $item['timer'] = convert_time($item['completion_date']);
            return $item;
        }
        $item['bet_classname'] = '';
        $item['timer_classname'] = '';
        $item['timer'] = convert_time($item['completion_date']);
        return $item;
    });
    return $items;
}
