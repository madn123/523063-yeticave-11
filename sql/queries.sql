INSERT INTO categories 
SET category_name = 'Доски и лыжи', category_code = 'boards';
SET category_name = 'Крепления', category_code = 'attachment';
SET category_name = 'Ботинки', category_code = 'boots';
SET category_name = 'Одежда', category_code = 'clothing';
SET category_name = 'Инструменты', category_code = 'tools';
SET category_name = 'Разное', category_code = 'other';

INSERT INTO users
SET date_registration = '2019-11-05 22:36:00', email = 'ivan@yandex.ru', name = 'Ivan', pass = '123', contacts = 'Sample text';
SET date_registration = '2019-11-06 09:16:00', email = 'vovan@yandex.ru', name = 'Vovan', pass = '12313', contacts = 'Sample text';

INSERT INTO items
SET date_creation = '2019-11-05 22:44:00', name = '2014 Rossignol District Snowboard', description = 'Sample text', image = 'Samle_image', start_price = '10999', completion_date = '2019-12-24 23:59:00', step_bet = '250';
SET date_creation = '2019-11-05 22:44:00', name = 'DC Ply Mens 2016/2017 Snowboard', description = 'Sample text', image = 'Samle_image', start_price = '159999', completion_date = '2019-12-24 23:59:00', step_bet = '250';
SET date_creation = '2019-11-05 22:44:00', name = 'Крепления Union Contact Pro 2015 года размер L/XL', description = 'Sample text', image = 'Samle_image', start_price = '8000', completion_date = '2019-12-24 23:59:00', step_bet = '250';
SET date_creation = '2019-11-05 22:44:00', name = 'Ботинки для сноуборда DC Mutiny Charocal', description = 'Sample text', image = 'Samle_image', start_price = '10999', completion_date = '2019-12-24 23:59:00', step_bet = '250';
SET date_creation = '2019-11-05 22:44:00', name = 'Куртка для сноуборда DC Mutiny Charocal', description = 'Sample text', image = 'Samle_image', start_price = '7500', completion_date = '2019-12-24 23:59:00', step_bet = '250';
SET date_creation = '2019-11-05 22:44:00', name = 'Маска Oakley Canopy', description = 'Sample text', image = 'Samle_image', start_price = '5400', completion_date = '2019-12-24 23:59:00', step_bet = '250';

INSERT INTO bets
SET date_creation = '2019-11-05 23:10:00', price = '11250';

SELECT * FROM categories; --получить все категории;

SELECT name, start_price, image, category_name FROM items i
JOIN categories c 
ON i.category_id = c.id
ORDER BY date_creation ASC; --получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории;

SELECT * FROM items i
JOIN categories c 
ON i.category_id = c.id
WHERE i.id = 1; -- показать лот по его id. Получите также название категории, к которой принадлежит лот;

UPDATE items SET name = '2014 Rossignol District Snowboard 2' 
WHERE id = 1; -- обновить название лота по его идентификатору;

SELECT price FROM bets b
JOIN items i 
ON item_id = i.id
WHERE i.id = 1
ORDER BY i.date_creation ASC; -- получить список ставок для лота по его идентификатору с сортировкой по дате.