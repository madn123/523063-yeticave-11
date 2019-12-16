<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="all-lots.php?category=<?= $category['id']; ?>"><?= $category['category_name']; ?></a>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
