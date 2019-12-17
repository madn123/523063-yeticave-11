<main>
    <?php require_once 'nav.php'; ?>

    <div class="container">
        <section class="lots">
            <h2>Все лоты в категории <span>«<?= $categories[$cur_cat]['category_name']; ?>»</span></h2>
            <ul class="lots__list">
                <?php foreach ($items as $item): ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?= $item['image']; ?>" width="350" height="260" alt="">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= $item['category_name']; ?></span>
                            <h3 class="lot__title">
                                <a class="text-link" href="lot.php?id=<?= $item['id']; ?>">
                                    <?= html_encode($item['name']); ?>
                                </a>
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
        </section>

        <?php if ($pages_count > 1): ?>
            <ul class="pagination-list">
                <?php if ($cur_page > 1): ?>
                    <li class="pagination-item pagination-item-prev"><a
                            href="/all-lots.php?page=<?= ($cur_page - 1); ?>&category=<?= $cur_cat; ?>">Назад</a></li>
                <?php endif ?>
                <?php foreach ($pages as $page): ?>
                    <li class="pagination-item <?php if ($page == $cur_page): ?>pagination-item-active<?php endif; ?>">
                        <a href="/all-lots.php?page=<?= $page; ?>&category=<?= $cur_cat; ?>"><?= $page; ?></a>
                    </li>
                <?php endforeach; ?>
                <?php if ($cur_page != $pages_count): ?>
                    <li class="pagination-item pagination-item-next"><a
                            href="/all-lots.php?page=<?= ($cur_page + 1); ?>&category=<?= $cur_cat; ?>">Вперед</a></li>
                <?php endif ?>
            </ul>
        <?php endif; ?>

    </div>
</main>

