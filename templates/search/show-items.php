<ul class="lots__list">
    <?php foreach ($items as $item): ?>
        <li class="lots__item lot">
            <div class="lot__image">
                <img src="<?= $item['image']; ?>" width="350" height="260" alt="">
            </div>
            <div class="lot__info">
                <span class="lot__category"><?= $item['category_name']; ?></span>
                <h3 class="lot__title">
                    <a class="text-link" href="../lot.php?id=<?= $item['id']; ?>"><?= html_encode($item['name']); ?></a>
                </h3>
                <div class="lot__state">
                    <div class="lot__rate">
                        <span class="lot__amount">Стартовая цена</span>
                        <span class="lot__cost">
                    <?= edit_price($item['start_price']); ?>
                  </span>
                    </div>
                    <div class="lot__timer timer <?= $item['timer_classname']; ?>">
                        <?= $item['timer']; ?>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach ?>
</ul>
