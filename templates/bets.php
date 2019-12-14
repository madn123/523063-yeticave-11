<main>
    <?php require_once 'nav.php'; ?>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach ($items as $item): ?>
                <tr class="rates__item <?=$item['bet_classname']; ?>">
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
                    <td class="rates__timer">
                        <div class="timer <?=$item['timer_classname']; ?>"><?=$item['timer']; ?></div>
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
