<main>
    <?php require_once 'nav.php'; ?>

    <section class="lot-item container">
        <h2><?= html_encode($lot['name']); ?></h2>

        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="../<?= $lot['image']; ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= $lot['category_name']; ?></span></p>
                <p class="lot-item__description">
                    <?= html_encode($lot['description']); ?>
                </p>
            </div>

            <div class="lot-item__right">
                <div class="lot-item__state" <?= $display_lot; ?>>
                    <div
                        class="lot-item__timer timer <?php if (convert_time($lot['completion_date']) < 1): ?> timer--finishing <?php endif ?>">
                        <?= convert_time($lot['completion_date']); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= edit_price($lot['start_price']); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= edit_price($new_price); ?></span>
                        </div>
                    </div>

                    <form class="lot-item__form" action="lot.php" method="post" autocomplete="off">
                        <?php $classname = isset($error) ? "form__item--invalid" : ""; ?>
                        <p class="lot-item__form-item form__item <?= $classname; ?>">
                            <label for="cost">Ваша ставка</label>
                            <input type="hidden" name="id" value="<?= ($lot['id']); ?>">
                            <input id="cost" type="text" name="cost" placeholder="<?= edit_price($new_price); ?>">
                            <?php if (isset($error)): ?>
                                <span class="form__error"><?= $error; ?></span>
                            <?php endif; ?>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>

                <div class="history">
                    <h3>История ставок (<span><?= count($bets); ?></span>)</h3>
                    <table class="history__list">
                        <?php foreach ($bets as $bet): ?>
                            <tr class="history__item">
                                <td class="history__name"><?= html_encode($bet['name']); ?></td>
                                <td class="history__price"><?= edit_price($bet['price']); ?></td>
                                <td class="history__time"><?= format_date($bet['date_creation']); ?></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
