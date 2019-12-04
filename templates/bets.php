<main>
    <?php require_once 'nav.php'; ?>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach ($items as $item): ?>
                <tr class="rates__item">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="<?=$item['image']; ?>" width="54" height="40" alt="Сноуборд">
                        </div>
                        <h3 class="rates__title"><a href="lot.php?id=<?=$item['id']; ?>"><?=$item['name']; ?></a></h3>
                    </td>
                    <td class="rates__category">
                        <?=$item['category_name']; ?>
                    </td>
                    <td class="rates__timer">
                        <div class="timer <?php if (convert_time($item['completion_date']) < 1): ?> timer--finishing <?php endif ?>"><?=convert_time($item['completion_date']);?></div>
                    </td>
                    <td class="rates__price">
                        <?=edit($item['price']);?>
                    </td>
                    <td class="rates__time">
                        5 минут назад
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </section>
</main>
