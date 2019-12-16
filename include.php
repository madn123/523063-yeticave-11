<?php
require_once 'functions.php';
require_once 'config.php';
require_once "vendor/autoload.php";

if (!$link) {
    $error = mysqli_error($link);
    die();
}

$sql = <<<SQL
    SELECT id, category_name, category_code FROM categories ORDER BY category_name ASC
SQL;

$res = do_query($link, $sql);

$categories = [];
while ($row = mysqli_fetch_array($res)) {
    $categories[$row['id']] = $row;
}
