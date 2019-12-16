<?php
require_once 'include.php';

$transport = new Swift_SmtpTransport("ssl://smtp.yandex.ru", 465);
$transport->setusername("testest.mail@yandex.ru");
$transport->setPassword("qaz123");
$mailer = new Swift_Mailer($transport);

$date = date('Y-m-d H:i:s');

$sql = <<<SQL
    SELECT items.id, items.completion_date, items.name FROM items
    WHERE winner_user_id IS NULL AND completion_date <= '$date'
SQL;

$res = mysqli_query($link, $sql);
$lots = mysqli_fetch_all($res, MYSQLI_ASSOC);

if (empty($lots)) {
    return;
}

foreach ($lots as $lot) {
    $sql = <<<SQL
        SELECT bets.user_id, users.name, users.email FROM bets
        LEFT JOIN users ON bets.user_id = users.id
        WHERE bets.item_id = {$lot['id']}
        ORDER BY bets.date_creation DESC LIMIT 1
SQL;

    $res = mysqli_query($link, $sql);
    $user = mysqli_fetch_assoc($res);

    $sql = "UPDATE items SET winner_user_id = {$user['user_id']} WHERE id = {$lot['id']}";
    do_query($link, $sql);

    $message = new Swift_Message();
    $message->setSubject("Ваша ставка победила");
    $message->setFrom(['testest.mail@yandex.ru' => 'YetiCave']);
    $message->setto($user['email']);

    $msg_content = include_template('email.php', [
        'lot' => $lot,
        'user' => $user
    ]);
    $message->setBody($msg_content, 'text/html');

    $result = $mailer->send($message);
}




