<main>
    <?php require_once 'templates/nav.php'; ?>

    <div class="container">
        <section class="lots">
            <?php if (!empty($items)): ?>
                <h2>Результаты поиска по запросу «<span><?=$search;?></span>»</h2>
                <?=include_template('search/show-items.php', ['items' => $items]);?>
            <?php else:?>
                <?=include_template('search/empty-request.php', ['errors' => $errors]);?>
            <?php endif ?>
        </section>
    </div>
</main>
