<main>
    <?php require_once 'templates/nav.php'; ?>

    <div class="container">
        <section class="lots">
            <?php if (!empty($items)): ?>
                <h2>Результаты поиска по запросу «<span><?= $search; ?></span>»</h2>
                <?= include_template('search/show-items.php', ['items' => $items]); ?>
            <?php else: ?>
                <?= include_template('search/empty-request.php', ['errors' => $errors]); ?>
            <?php endif ?>
        </section>
        <?php if ($pages_count > 1): ?>
            <ul class="pagination-list">
                <?php if ($cur_page > 1): ?>
                    <li class="pagination-item pagination-item-prev"><a
                            href="/search.php?page=<?= ($cur_page - 1); ?>&search=<?= $search; ?>">Назад</a></li>
                <?php endif ?>
                <?php foreach ($pages as $page): ?>
                    <li class="pagination-item <?php if ($page == $cur_page): ?>pagination-item-active<?php endif; ?>">
                        <a href="/search.php?page=<?= $page; ?>&&search=<?= $search; ?>"><?= $page; ?></a>
                    </li>
                <?php endforeach; ?>
                <?php if ($cur_page != $pages_count): ?>
                    <li class="pagination-item pagination-item-next"><a
                            href="/search.php?page=<?= ($cur_page + 1); ?>&search=<?= $search; ?>">Вперед</a></li>
                <?php endif ?>
            </ul>
        <?php endif; ?>
    </div>
</main>
