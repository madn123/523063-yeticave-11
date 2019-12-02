<?php
require_once 'functions.php';
require_once 'config.php';

if (!$link) {
    $error = mysqli_error($link);
    die();
}

$sql = <<<SQL
    SELECT id, category_name, category_code FROM categories ORDER BY category_name ASC
SQL;

$result = mysqli_query($link, $sql);

if (!$result) {
    $error = debug_error($link);
    die();
}

$categories = [];
while($row = mysqli_fetch_array($result)){
    $categories[ $row['id'] ] = $row;
}
