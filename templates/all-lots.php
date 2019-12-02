<?php require_once 'nav.php'; ?>

<div class="container">
      <section class="lots">
        <h2>Все лоты в категории <span>«<?=$categories[$_GET['category']]['category_name'];?>»</span></h2>
        <ul class="lots__list">
	        	<?php foreach ($items as $item): ?>
		            <li class="lots__item lot">
		                <div class="lot__image">
		                    <img src="<?=$item['image']; ?>" width="350" height="260" alt="">
		                </div>
		                <div class="lot__info">
		                    <span class="lot__category"><?=$item['category_name']; ?></span>
		                    <h3 class="lot__title">
		                    	<a class="text-link" href="lot.php?id=<?=$item['id']; ?>"><?=$item['name']; ?></a>
		                    </h3>
		                    <div class="lot__state">
		                        <div class="lot__rate">
		                            <span class="lot__amount">Стартовая цена</span>
		                            <span class="lot__cost">
		                            	<?=edit($item['start_price']);?>
		                            </span>
		                        </div>
		                        <div class="lot__timer timer <?php if (conver_time($item['completion_date']) < 1): ?> timer--finishing <?php endif ?>">
		                            <?=conver_time($item['completion_date']);?>
		                        </div>
		                    </div>
		                </div>
		            </li>
		        <?php endforeach ?>
	        </ul>
      </section>

<?php if ($pages_count > 1): ?>
	<ul class="pagination-list">
		<li class="pagination-item pagination-item-prev"><a href="/all-lots.php?page=<?=($page-1);?>">Назад</a></li>
		<?php foreach ($pages as $page): ?>
			<li class="pagination-item <?php if ($page == $cur_page): ?>pagination-item-active<?php endif; ?>">
				<a href="/all-lots.php?page=<?=$page;?>"><?=$page;?></a>
			</li>
		<?php endforeach; ?>
		<li class="pagination-item pagination-item-next"><a href="/all-lots.php?page=<?=($page+1);?>">Вперед</a></li>
	</ul>
<?php endif; ?>

  </div>
  </main>