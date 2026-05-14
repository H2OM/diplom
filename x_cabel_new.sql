-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 15 2026 г., 01:58
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `x_cabel_new`
--

-- --------------------------------------------------------

--
-- Структура таблицы `brands`
--

CREATE TABLE `brands` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `brands`
--

INSERT INTO `brands` (`id`, `name`, `code`) VALUES
(1, 'Cabeus', 'cabeus'),
(2, 'DataCable', 'datacable'),
(3, 'Rexant', 'rexant');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `title`, `code`) VALUES
(1, 'кабельная продукция', 'kabelnaya-produktsiya'),
(2, 'Освещение и светотехника', 'lighting'),
(3, 'Электроустановочные изделия', 'wiring-accessories'),
(4, 'Инструменты', 'tools'),
(5, 'Сетевое оборудование', 'network');

-- --------------------------------------------------------

--
-- Структура таблицы `categories_filters`
--

CREATE TABLE `categories_filters` (
  `category_id` int UNSIGNED NOT NULL,
  `filter_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Привязывание фильтров к категории';

--
-- Дамп данных таблицы `categories_filters`
--

INSERT INTO `categories_filters` (`category_id`, `filter_id`) VALUES
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11);

-- --------------------------------------------------------

--
-- Структура таблицы `categories_types`
--

CREATE TABLE `categories_types` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `category_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `categories_types`
--

INSERT INTO `categories_types` (`id`, `name`, `category_id`) VALUES
(8, 'Акустический кабель', 1),
(2, 'Витая пара', 1),
(7, 'Кабель для видеонаблюдения', 1),
(4, 'Коаксиальный кабель', 1),
(5, 'Оптоволоконный кабель', 1),
(6, 'Ретро провод', 1),
(1, 'Силовой кабель', 1),
(3, 'Телефонный кабель', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `favorites`
--

CREATE TABLE `favorites` (
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `mark` enum('1','2','3','4','5') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `filters`
--

CREATE TABLE `filters` (
  `id` int UNSIGNED NOT NULL,
  `filter` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('switch','multi','range','') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `position` tinyint UNSIGNED NOT NULL DEFAULT '127'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `filters`
--

INSERT INTO `filters` (`id`, `filter`, `code`, `type`, `position`) VALUES
(1, 'Сортировка', 'sort', 'switch', 1),
(2, 'Скидка', 'sale', 'switch', 254),
(3, 'Тип', 'type', 'multi', 2),
(4, 'Бренд', 'brand', 'multi', 3),
(5, 'Цена', 'price', 'range', 255),
(7, 'Цвет', 'color', 'multi', 127),
(8, 'Длинна', 'length', 'multi', 127),
(9, 'Кол-во пар', 'pairs_count', 'multi', 127),
(10, 'Исполнение', 'perfomance', 'multi', 127),
(11, 'Кол-во проводников', 'cores_count', 'multi', 127);

-- --------------------------------------------------------

--
-- Структура таблицы `filters_values`
--

CREATE TABLE `filters_values` (
  `id` int UNSIGNED NOT NULL,
  `value` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(28) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `filter_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `filters_values`
--

INSERT INTO `filters_values` (`id`, `value`, `code`, `filter_id`) VALUES
(73, '305 м', '305m', 8),
(74, '4', '4', 9),
(76, '4', '4', 11),
(57, 'Бежевый', 'beige', 7),
(55, 'Белый', 'white', 7),
(5, 'Больше 10%', 'more-10', 2),
(6, 'Больше 30%', 'more-30', 2),
(7, 'Больше 50%', 'more-50', 2),
(58, 'Бордовый', 'vinous', 7),
(59, 'Голубой', 'blue', 7),
(4, 'Да', 'yes', 2),
(63, 'Зеленый', 'green', 7),
(60, 'Мультиколор', 'multicolor', 7),
(75, 'нг(А)-HF', 'ng-a-hf', 10),
(61, 'Оранжевый', 'orange', 7),
(1, 'По возрастанию цены', 'low-to-high', 1),
(3, 'По популярности', 'by-popular', 1),
(2, 'По убыванию цены', 'high-to-low', 1),
(62, 'Розовый', 'pink', 7),
(72, 'Серый', 'gray', 7),
(56, 'Черный', 'black', 7);

-- --------------------------------------------------------

--
-- Структура таблицы `filters_values_products`
--

CREATE TABLE `filters_values_products` (
  `filter_value_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `filters_values_products`
--

INSERT INTO `filters_values_products` (`filter_value_id`, `product_id`) VALUES
(72, 20),
(73, 20),
(74, 20),
(75, 20),
(76, 20);

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int UNSIGNED NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `text`, `image`) VALUES
(4, 'Новая модель Reebok - Zig Kinetica! Zig Kinetica – модель с беговой историей, ставшая иконой уличного стиля. Сегодня технологии Reebok на страже повседневного комфорта. Дополнительные свойства: Зигзагообразный ТПУ каркас ZIG ENERGY SHELL обеспечивающий стабилизацию, направляя и возвращая кинетическую энергию. Комбинация пеноматериалов FLOATRIDE ENERGY и FLOATRIDE FUEL в промежуточной подошве обеспечивает легкую и отзывчивую амортизацию, а также гасит ударные нагрузки. Дышащий комбинированный верх выполнен с использованием двухслойной сетки и обеспечивает превосходную циркуляцию воздуха. Инновационные резиновые полоски ZIG ENERGY BANDS на подметке сжимаются и разжимаются, усиливая возврат энергии с каждым шагом.', 'img/info1.jpg'),
(5, 'Кроссовки PUMA RS-Z LTH Trainers. Футуристический внешний вид модели PUMA RS-Z LTH Trainers сочетается с продуманными технологическим наполнением. Верх изготовлен из кожи и отвечает за внешний вид и долговечность пары. Подошва с технологией Running System — мягкий вспененный материал IMEVA и формованная стелька снижают ударные нагрузки и обеспечивают ощущение легкости даже на длинных дистанциях. Знаменитые полосы PUMA Formstrip выделяются по структуре, дополняя многослойную конструкцию верха.', 'png/info3.png'),
(6, 'Зарегистрируйтесь на нашем сайте и получите бонус в виде промокода на первый заказ.', 'png/info2.png'),
(7, 'Новый интернет-магазин обуви - Shoes!', 'png/info4.png');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int UNSIGNED NOT NULL,
  `number` char(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('0','1','2','3') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `user_id` int UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `change_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `delivery_date` timestamp NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `orders_products`
--

CREATE TABLE `orders_products` (
  `order_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `size` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `brand_id` int UNSIGNED NOT NULL,
  `category_type_id` int UNSIGNED DEFAULT NULL,
  `article` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price_old` decimal(10,2) NOT NULL DEFAULT '0.00',
  `image` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Untitled.jpg',
  `slider_images` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'example.jpg,example2.jpg,example3.jpg...',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `hit` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `title`, `brand_id`, `category_type_id`, `article`, `price`, `price_old`, `image`, `slider_images`, `description`, `hit`) VALUES
(20, 'Кабель витая пара UTP (U/UTP), категория 5e, 4 пары (24 AWG), одножильный, серый, LSZH, нг(А)-HF, (305 м)', 1, 2, 'UTP-4P-Cat.5e-SOLID-LSZH-GY', '17123.18', '0.00', '', '', 'Четырехпарный кабель категории 5e на основе витой пары предназначен для использования в системах передачи данных со скоростью до 2.5 Гбит/c. Кабель выполнен в неэкранированном исполнении U/UTP и предназначен для прокладки внутри зданий. Диаметр проводников составляет 0,50 мм (24 AWG). Внешняя оболочка выполнена из не распространяющего горение LSZH-компаунда, малодымного и не выделяющего ядовитых соединений в процессе горения, исполнение нг(А)-HF. На внешней оболочке кабеля нанесены метровые метки длины кабеля.\r\nКабель обладает отличными характеристиками при разумной цене, что делает его оптимальным вариантом для построения сетей в проектах, в том числе где требуется гарантийная системная поддержка. Кабель поставляется в картонной коробке «easy-pull box».', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `products_related`
--

CREATE TABLE `products_related` (
  `product_id` int UNSIGNED NOT NULL,
  `related_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `products_variations`
--

CREATE TABLE `products_variations` (
  `product_id` int UNSIGNED NOT NULL,
  `variation_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `first_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `second_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `age` int UNSIGNED NOT NULL,
  `gender` enum('male','female') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` char(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `first_name`, `second_name`, `age`, `gender`, `email`, `phone`, `password`) VALUES
(5, 'Дмитрий', 'Заболотнов', 19, 'male', 'dima.zabolotnov.02@mail.ru', '+7 (918) 219-55-84', '$2y$10$j3VsuFxHcIeTuCy/9yPfS.2BVzi/4AXy1KB4/nr14q/JUt6NlWiti'),
(11, 'Дмитрий', 'Заболотнов', 12, 'male', 'dima.za2232@mail.ru', '+7 (918) 294-25-22', '$2y$10$t56uGoskE/jLBmrpzMlm3.ZkZ1yukDyHwk8Rar3YaTT7jrMx6GmS2'),
(12, 'Дмитрий', 'Заболотнов', 43, 'male', 'dima.za423@mail.ru', '+7 (918) 111-11-12', '$2y$10$TwK3UYpWGj2UvUwjS0OMMuWchiJIFh.AOH.Zxr52S9deifhtbTj16'),
(13, 'Дмитрий', 'Заболотнов', 43, 'male', 'dima.za425@mail.ru', '+7 (918) 222-22-22', '$2y$10$4xPK6YnY.oRFQZU6Kbe/6.ljQXGH17unMsp1vjACibDCwhRsoTQwK'),
(14, 'Дмитрий', 'Коваленко', 22, 'female', 'danvbcsf@mail.ru', '+7 (124) 151-51-54', '$2y$10$czay3zomMM5UuR2aNjcQye2Uol3ai3IzPv6k4bMxbazPIbm5b9jtm'),
(15, 'Дмитрий', 'Коваленко', 22, 'male', 'dima.za2512@mail.ru', '+7 (151) 515-15-11', '$2y$10$zx/B7DdeJlYiRLl4JNRUNuNggsYtInO0y91qSnt3d3Jky6QGjrlw6'),
(16, 'Тест', 'Тест', 44, 'male', 'jopaj@mail.su', '77678768686', '$2y$10$9uQhtPQG.1TcN8CGeDn1/ez3603bAEqtLX4mj58zEhSvgiHtgD2VW'),
(17, 'Тест', 'Тест', 99, 'male', 'test@mail.su', '73323321312', '$2y$10$ZwO9y.CHqjD0c1HVdr2Wv.gBMzs/1Wh6empYeXOwC0rwKFP0qCSvq');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_name` (`name`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `categories_filters`
--
ALTER TABLE `categories_filters`
  ADD UNIQUE KEY `category_id` (`category_id`,`filter_id`),
  ADD KEY `filter_id` (`filter_id`);

--
-- Индексы таблицы `categories_types`
--
ALTER TABLE `categories_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_2` (`name`,`category_id`),
  ADD KEY `name` (`name`),
  ADD KEY `type_category_id` (`category_id`);

--
-- Индексы таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`user_id`,`product_id`) USING BTREE,
  ADD KEY `FavGoodsCall` (`product_id`);

--
-- Индексы таблицы `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `User_id` (`user_id`,`product_id`),
  ADD KEY `FitToGoodsCall` (`product_id`) USING BTREE;

--
-- Индексы таблицы `filters`
--
ALTER TABLE `filters`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `filters_values`
--
ALTER TABLE `filters_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `value` (`value`,`code`,`filter_id`),
  ADD KEY `filterCall` (`filter_id`);

--
-- Индексы таблицы `filters_values_products`
--
ALTER TABLE `filters_values_products`
  ADD PRIMARY KEY (`filter_value_id`,`product_id`),
  ADD KEY `FilterToGoodsCall` (`product_id`),
  ADD KEY `filter_value_id` (`filter_value_id`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `news` ADD FULLTEXT KEY `Text` (`text`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Number` (`number`),
  ADD KEY `User_id` (`user_id`);

--
-- Индексы таблицы `orders_products`
--
ALTER TABLE `orders_products`
  ADD PRIMARY KEY (`product_id`,`order_id`),
  ADD KEY `OrderGToOrders` (`order_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`,`article`),
  ADD UNIQUE KEY `Article` (`article`),
  ADD KEY `Title` (`title`),
  ADD KEY `product_brand_id` (`brand_id`),
  ADD KEY `product_category_type_id` (`category_type_id`);

--
-- Индексы таблицы `products_related`
--
ALTER TABLE `products_related`
  ADD PRIMARY KEY (`product_id`,`related_id`) USING BTREE,
  ADD KEY `Related_id` (`related_id`) USING BTREE,
  ADD KEY `Goods_id` (`product_id`) USING BTREE;

--
-- Индексы таблицы `products_variations`
--
ALTER TABLE `products_variations`
  ADD PRIMARY KEY (`product_id`,`variation_id`) USING BTREE,
  ADD KEY `Variation_id` (`variation_id`) USING BTREE,
  ADD KEY `Base_id` (`product_id`) USING BTREE;

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Email` (`email`),
  ADD UNIQUE KEY `Phone` (`phone`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `categories_types`
--
ALTER TABLE `categories_types`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `filters`
--
ALTER TABLE `filters`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `filters_values`
--
ALTER TABLE `filters_values`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `categories_filters`
--
ALTER TABLE `categories_filters`
  ADD CONSTRAINT `category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `filter_id` FOREIGN KEY (`filter_id`) REFERENCES `filters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `categories_types`
--
ALTER TABLE `categories_types`
  ADD CONSTRAINT `type_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `FavGoodsCall` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FavUserCall` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `FitToGoodsCall` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FitToUserCall` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `filters_values`
--
ALTER TABLE `filters_values`
  ADD CONSTRAINT `filterCall` FOREIGN KEY (`filter_id`) REFERENCES `filters` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `filters_values_products`
--
ALTER TABLE `filters_values_products`
  ADD CONSTRAINT `to_filters_values_id` FOREIGN KEY (`filter_value_id`) REFERENCES `filters_values` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `to_products_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `OrderToUser` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `orders_products`
--
ALTER TABLE `orders_products`
  ADD CONSTRAINT `OrderGToGoods` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `OrderGToOrders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `product_brand_id` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_category_type_id` FOREIGN KEY (`category_type_id`) REFERENCES `categories_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `products_related`
--
ALTER TABLE `products_related`
  ADD CONSTRAINT `products_related_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_related_ibfk_2` FOREIGN KEY (`related_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `products_variations`
--
ALTER TABLE `products_variations`
  ADD CONSTRAINT `products_variations_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_variations_ibfk_2` FOREIGN KEY (`variation_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
