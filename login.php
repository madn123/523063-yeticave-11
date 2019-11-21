<?php
require_once 'include.php';

$page_content = include_template('login.php', ['categories' => $categories]);

$layout_content = include_template('layout.php', [
    'content'    => $page_content,
    'categories' => $categories
]);

print($layout_content);