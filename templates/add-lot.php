  <main>
    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
              <li class="nav__item">
                  <a href="pages/all-lots.html"><?=$category['category_name']; ?></a>
              </li>
        <?php endforeach ?>
      </ul>
    </nav>

    <form class="form form--add-lot container form--invalid" action="add.php" method="post" enctype="multipart/form-data">
      <h2>Добавление лота</h2>
      <div class="form__container-two">
      	<?php $classname = isset($errors['name']) ? "form__item--invalid " : ""; ?>
        <div class="form__item <?= $classname; ?>">
          <label for="lot-name">Наименование <sup>*</sup></label>
          <input type="text" name="name" placeholder="Введите наименование лота" value="<?= getPostVal('name'); ?>">
          <span class="form__error">Введите наименование лота</span>
        </div>

        <?php $classname = isset($errors['category_id']) ? "form__item--invalid " : ""; ?>
        <div class="form__item <?= $classname; ?>">
          <label for="category">Категория <sup>*</sup></label>
          <select id="category" name="category_id">
            <option>Выберите категорию</option>
          	<?php foreach ($categories as $category): ?>
              <option value="<?=$category['id']; ?>" <?= getPostVal('category_id') == $category['id'] ? 'selected="true"' : '';?>><?=$category['category_name']; ?></option>
        	<?php endforeach ?>
          </select>
          <span class="form__error">Выберите категорию</span>
        </div>
      </div>

      <?php $classname = isset($errors['description']) ? "form__item--invalid " : ""; ?>
      <div class="form__item form__item--wide <?= $classname; ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="description" placeholder="Напишите описание лота"><?= getPostVal('description'); ?></textarea>
        <span class="form__error">Напишите описание лота</span>
      </div>

      <div class="form__item form__item--file">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" id="lot-img" name="image" value=>
          <label for="lot-img">
            Добавить
          </label>
        </div>

      </div>
      <div class="form__container-three">
      	<?php $classname = isset($errors['start_price']) ? "form__item--invalid " : ""; ?>
        <div class="form__item form__item--small <?= $classname; ?>">
          <label for="lot-rate">Начальная цена <sup>*</sup></label>
          <input id="lot-rate" type="text" name="start_price" placeholder="0" value="<?= getPostVal('start_price'); ?>">
          <span class="form__error">Введите начальную цену</span>
        </div>

        <?php $classname = isset($errors['step_bet']) ? "form__item--invalid " : ""; ?>
        <div class="form__item form__item--small <?= $classname; ?>">
          <label for="lot-step">Шаг ставки <sup>*</sup></label>
          <input id="lot-step" type="text" name="step_bet" placeholder="0" value="<?= getPostVal('step_bet'); ?>">
          <span class="form__error">Введите шаг ставки</span>
        </div>

        <?php $classname = isset($errors['completion_date']) ? "form__item--invalid " : ""; ?>
        <div class="form__item <?= $classname; ?>">
          <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
          <input class="form__input-date" id="lot-date" type="text" name="completion_date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= getPostVal('completion_date'); ?>"> 
          <span class="form__error">Введите дату завершения торгов</span>
        </div>
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
      <button type="submit" class="button">Добавить лот</button>
    </form>
  </main>