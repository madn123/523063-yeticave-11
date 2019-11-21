<?php
require_once 'functions.php';
require_once 'config.php';

if (!$link) {
    $error = mysqli_error($link);
    die();
}

$sql = 'SELECT id, category_name, category_code FROM categories ORDER BY category_name ASC';

$result = mysqli_query($link, $sql);

if (!$result) {
    $error = debug_error($link);
    die();
}

$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);