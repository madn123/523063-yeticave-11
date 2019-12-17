<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= html_encode($user['name']); ?></p>
<p>Ваша ставка для лота
    <a href="http://523063-yeticave-11/lot.php?id=<?= $lot['id']; ?>">
        <?= html_encode($lot['name']); ?>
    </a>
    победила.</p>
<p>Перейдите по ссылке <a href="http://523063-yeticave-11/bets.php">мои ставки</a>,
    чтобы связаться с автором объявления</p>
<small>Интернет Аукцион "YetiCave"</small>
