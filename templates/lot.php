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
                            Мин. ставка <span><?=edit($new_price);?></span>
                        </div>
                    </div>

                    <form class="lot-item__form" action="add-bet.php" method="post" autocomplete="off">
                        <?php $classname = isset($error) ? "form__item--invalid " : ""; ?>
                        <p class="lot-item__form-item form__item <?= $classname; ?>">
                            <label for="cost">Ваша ставка</label>
                            <input type="hidden" name="item_id" value="<?= ($lots['id']); ?>">
                            <input id="cost" type="text" name="cost" placeholder="<?=edit($new_price);?>">
                            <span class="form__error"><?= $error; ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>

                <div class="history">
                    <h3>История ставок (<span><?=count($bets);?></span>)</h3>
                    <table class="history__list">
                        <?php foreach ($bets as $bet): ?>
                            <tr class="history__item">
                                <td class="history__name"><?=$bet['name']; ?></td>
                                <td class="history__price"><?=edit($bet['price']); ?></td>
                                <td class="history__time"><?=format_date($bet['date_creation']); ?></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
