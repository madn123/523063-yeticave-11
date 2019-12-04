<main>
    <?php require_once 'nav.php'; ?>

    <section class="lot-item container">
        <h2><?= ($lots['name']); ?></h2>

        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="../<?= $lots['image']; ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= ($lots['category_name']); ?></span></p>
                <p class="lot-item__description">
                    <?= ($lots['description']); ?>
                </p>
            </div>

            <div class="lot-item__right">
                <div class="lot-item__state" <?php if (!isset($_SESSION['user'])): ?> style="display:none" <?php endif ?>>
                    <div class="lot-item__timer timer <?php if (convert_time($lots['completion_date']) < 1): ?> timer--finishing <?php endif ?>">
                        <?= convert_time($lots['completion_date']); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= edit($lots['start_price']); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span>12 000 р</span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="add-bet.php" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item "><!-- form__item--invalid -->
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="12 000">
                            <span class="form__error">Введите наименование лота</span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>

                <div class="history">
                    <h3>История ставок (<span>10</span>)</h3>
                    <table class="history__list">
                        <tr class="history__item">
                            <td class="history__name">Иван</td>
                            <td class="history__price">10 999 р</td>
                            <td class="history__time">5 минут назад</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
