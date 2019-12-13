<main>
    <?php require_once 'nav.php'; ?>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach ($items as $item): ?>
                <?php if ($item['winner_user_id'] == $_SESSION['user']['id']): ?>
                    <? $classname = "rates__item--win"; ?>
                <?php elseif (convert_time($item['completion_date']) < 0): ?>
                    <? $classname = "rates__item--end"; ?>
                <?php else:?>
                    <? $classname = ""; ?>
                <?php endif ?>
                <tr class="rates__item <?= $classname; ?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="<?=$item['image']; ?>" width="54" height="40" alt="Изображение лота">
                        </div>
                        <div>
                            <h3 class="rates__title"><a href="lot.php?id=<?=$item['id']; ?>"><?=$item['name']; ?></a></h3>
                            <?php if ($item['winner_user_id'] == $_SESSION['user']['id']): ?>
                                <p><?=$item['contacts']; ?></p>
                            <?php endif ?>
                        </div>
                    </td>
                    <td class="rates__category">
                        <?=$item['category_name']; ?>
                    </td>
                    <?php if ($item['winner_user_id'] == $_SESSION['user']['id']): ?>
                        <? $classname = "timer--win"; ?>
                        <? $timer = "Ставка выиграла"; ?>
                    <?php elseif (convert_time($item['completion_date']) < 0): ?>
                        <? $classname = "timer--end"; ?>
                        <? $timer = "Торги окончены"; ?>
                    <?php elseif ((strtotime($item['completion_date']) - time()) < 3600): ?>
                        <? $classname = "timer--finishing"; ?>
                        <? $timer = convert_time($item['completion_date']);?>
                    <?php else:?>
                        <? $classname = ""; ?>
                        <? $timer = convert_time($item['completion_date']);?>
                    <?php endif ?>
                    <td class="rates__timer">
                        <div class="timer <?= $classname; ?>"><?=$timer; ?></div>
                    </td>
                    <td class="rates__price">
                        <?=edit($item['price']);?>
                    </td>
                    <td class="rates__time">
                        <?=format_date($item['date_creation']); ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </section>
</main>
