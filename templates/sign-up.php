<?php require_once 'nav.php'; ?>
    
  <form class="form container form--invalid" action="sign.php" method="post" autocomplete="off">
    <h2>Регистрация нового аккаунта</h2>

    <?php $classname = isset($errors['email']) ? "form__item--invalid " : ""; ?>
    <div class="form__item <?= $classname; ?>">
      <label for="email">E-mail <sup>*</sup></label>
      <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= getPostVal('email'); ?>">
      <span class="form__error">Введите e-mail</span>
    </div>

    <?php $classname = isset($errors['pass']) ? "form__item--invalid " : ""; ?>
    <div class="form__item <?= $classname; ?>">
      <label for="password">Пароль <sup>*</sup></label>
      <input id="password" type="password" name="pass" placeholder="Введите пароль" value="<?= getPostVal('pass'); ?>">
      <span class="form__error">Введите пароль</span>
    </div>

    <?php $classname = isset($errors['name']) ? "form__item--invalid " : ""; ?>
    <div class="form__item <?= $classname; ?>">
      <label for="name">Имя <sup>*</sup></label>
      <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= getPostVal('name'); ?>">
      <span class="form__error">Введите имя</span>
    </div>

    <?php $classname = isset($errors['contacts']) ? "form__item--invalid " : ""; ?>
    <div class="form__item <?= $classname; ?>">
      <label for="message">Контактные данные <sup>*</sup></label>
      <textarea id="message" name="contacts" placeholder="Напишите как с вами связаться"><?= getPostVal('contacts'); ?></textarea>
      <span class="form__error">Напишите как с вами связаться</span>
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
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
  </form>
</main>