<main>
    <?php require_once 'nav.php'; ?>

    <form class="form container form--invalid" action="login.php" method="post">
        <h2>Вход</h2>

        <?php $classname = isset($errors) ? "form__item--invalid " : ""; ?>
        <div class="form__item <?= $classname; ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail"
                   value="<?= html_encode(get_post_val('email')); ?>">
            <span class="form__error"><?= $errors['email']; ?></span>
        </div>

        <?php $classname = isset($errors) ? "form__item--invalid " : ""; ?>
        <div class="form__item form__item--last <?= $classname; ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="pass" placeholder="Введите пароль"
                   value="<?= html_encode(get_post_val('pass')); ?>">
            <span class="form__error"><?= $errors['pass']; ?></span>
        </div>

        <?php if (isset($errors)): ?>
            <div class="form__errors">
                <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
                <ul>
                    <?php foreach ($errors as $val): ?>
                        <li><strong><?= $val; ?></strong></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <button type="submit" class="button">Войти</button>
    </form>
</main>
