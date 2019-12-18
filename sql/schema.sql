CREATE DATABASE yeticave
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE `categories` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`category_name` char(32) NOT NULL,
	`category_code` char(32) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `category_code` (`category_code`),
	UNIQUE KEY `idx_category` (`category_name`)
);

CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_creation` datetime NOT NULL,
  `name` varchar(128) NOT NULL DEFAULT '',
  `description` varchar(600) NOT NULL,
  `image` varchar(128) NOT NULL,
  `start_price` int(11) NOT NULL,
  `completion_date` datetime NOT NULL,
  `step_bet` int(11) NOT NULL,
  `winner_user_id` int(11) DEFAULT NULL,
  `creator_user_id` int(11),
  `category_id` int(11),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `category_id` (`category_id`),
  KEY `creator_user_id` (`creator_user_id`),
  KEY `items_ibfk_3` (`winner_user_id`),
  FULLTEXT KEY `items_ft_search` (`name`,`description`)
);

CREATE TABLE `bets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_creation` datetime NOT NULL,
  `price` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bets_ibfk_1` (`item_id`),
  KEY `bets_ibfk_2` (`user_id`)
);

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt_add` datetime NOT NULL,
  `email` char(32) NOT NULL,
  `name` char(32) NOT NULL,
  `pass` char(255) NOT NULL,
  `contacts` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
);

INSERT INTO categories
SET category_name = 'Доски и лыжи', category_code = 'boards';
INSERT INTO categories
SET category_name = 'Крепления', category_code = 'attachment';
INSERT INTO categories
SET category_name = 'Ботинки', category_code = 'boots';
INSERT INTO categories
SET category_name = 'Одежда', category_code = 'clothing';
INSERT INTO categories
SET category_name = 'Инструменты', category_code = 'tools';
INSERT INTO categories
SET category_name = 'Разное', category_code = 'other';

INSERT INTO items
SET date_creation = '2019-11-05 22:44:00', name = '2014 Rossignol District Snowboard', description = 'Sample text', image = 'imag/lot-1.jpg', start_price = '10999', completion_date = '2019-12-24 23:59:00', step_bet = '250', category_id = '1';
INSERT INTO items
SET date_creation = '2019-11-05 22:44:00', name = 'DC Ply Mens 2016/2017 Snowboard', description = 'Sample text', image = 'imag/lot-2.jpg', start_price = '159999', completion_date = '2019-12-24 23:59:00', step_bet = '250', category_id = '1';
INSERT INTO items
SET date_creation = '2019-11-05 22:44:00', name = 'Крепления Union Contact Pro 2015 года размер L/XL', description = 'Sample text', image = 'imag/lot-3.jpg', start_price = '8000', completion_date = '2019-12-24 23:59:00', step_bet = '250', category_id = '3';
INSERT INTO items
SET date_creation = '2019-11-05 22:44:00', name = 'Ботинки для сноуборда DC Mutiny Charocal', description = 'Sample text', image = 'imag/lot-4.jpg', start_price = '10999', completion_date = '2019-12-24 23:59:00', step_bet = '250', category_id = '4';
INSERT INTO items
SET date_creation = '2019-11-05 22:44:00', name = 'Куртка для сноуборда DC Mutiny Charocal', description = 'Sample text', image = 'imag/lot-5.jpg', start_price = '7500', completion_date = '2019-12-24 23:59:00', step_bet = '250', category_id = '5';
INSERT INTO items
SET date_creation = '2019-11-05 22:44:00', name = 'Маска Oakley Canopy', description = 'Sample text', image = 'imag/lot-6.jpg', start_price = '5400', completion_date = '2019-12-24 23:59:00', step_bet = '250', category_id = '6';
