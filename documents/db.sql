-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2025 at 10:12 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db`
--

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE `article` (
  `id` int(10) NOT NULL,
  `name` varchar(14) NOT NULL,
  `description` varchar(21) NOT NULL,
  `price` decimal(3,2) NOT NULL,
  `unit` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `article`
--

INSERT INTO `article` (`id`, `name`, `description`, `price`, `unit`) VALUES
(1, 'Komkommer', 'Verse komkommer', 0.99, 'per stuk'),
(2, 'Tomaat', 'Ripe tomato', 0.85, 'per stuk'),
(3, 'Feta cheese', 'Zachte feta kaas', 2.99, 'per 100g'),
(4, 'Cheddar cheese', 'Geraspte cheddar', 3.99, 'per 100g'),
(5, 'Sriracha sauce', 'Pittige saus', 1.99, 'per fles'),
(6, 'Ui (onion)', 'Rode ui', 0.50, 'per stuk'),
(7, 'Noedels', 'Gedroogde noedels', 2.50, 'per pak'),
(8, 'Sesamzaadjes', 'Sesamzaadjes', 1.25, 'per 100g'),
(9, 'Stroop', 'Traditionele stroop', 1.75, 'per fles'),
(10, 'Poedersuiker', 'Suiker om te strooien', 0.99, 'per pak'),
(11, 'munt', 'Verse muntblaadjes', 1.50, 'per bosje');

-- --------------------------------------------------------

--
-- Table structure for table `cuisinetype`
--

CREATE TABLE `cuisinetype` (
  `id` int(10) NOT NULL,
  `record_type` varchar(1) NOT NULL,
  `description` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `cuisinetype`
--

INSERT INTO `cuisinetype` (`id`, `record_type`, `description`) VALUES
(1, 'C', 'Middle Eastern'),
(2, 'C', 'Dutch'),
(3, 'C', 'Mexican'),
(4, 'C', 'Korean'),
(5, 'T', 'Vegan'),
(6, 'T', 'Vegetarian'),
(7, 'T', 'Gluten-Free'),
(8, 'T', 'Dairy-Free');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `id` int(10) NOT NULL,
  `recipe_id` int(1) NOT NULL,
  `article_id` int(2) NOT NULL,
  `amount` int(11) NOT NULL,
  `calories` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `recipe_id`, `article_id`, `amount`, `calories`) VALUES
(1, 1, 1, 1, 15),
(2, 1, 2, 2, 45),
(3, 1, 3, 100, 265),
(4, 2, 4, 150, 600),
(5, 2, 5, 2, 30),
(6, 2, 6, 1, 40),
(7, 3, 7, 150, 520),
(8, 3, 8, 1, 50),
(9, 4, 9, 1, 300),
(10, 4, 10, 2, 60),
(11, 1, 11, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `recipe`
--

CREATE TABLE `recipe` (
  `id` int(10) NOT NULL,
  `cuisine_id` int(1) NOT NULL,
  `type_id` int(1) NOT NULL,
  `user_id` int(1) NOT NULL,
  `added_date` varchar(10) NOT NULL,
  `title` varchar(20) NOT NULL,
  `short_description` varchar(43) NOT NULL,
  `long_description` varchar(40) NOT NULL,
  `picture` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `recipe`
--

INSERT INTO `recipe` (`id`, `cuisine_id`, `type_id`, `user_id`, `added_date`, `title`, `short_description`, `long_description`, `picture`) VALUES
(1, 1, 5, 1, '25/07/2025', 'Middle Eastern Salad', 'Frisse salade met komkommer, tomaat en feta', 'Een frisse salade met munt en komkommer.', 'salad.jpg'),
(2, 3, 6, 2, '26/07/2025', 'American Burger', 'Homemade burger met cheddar en srirachasaus', 'Een burger met pittige saus en ui.', 'burger.jpg'),
(3, 4, 7, 3, '27/07/2025', 'Korean Noodles', 'Pittige noedels met groenten en sojasaus', 'Heet, pittig gerecht met sesamzaadjes.', 'noodles.jpg'),
(4, 2, 8, 4, '28/07/2025', 'Dutch Pancake', 'Dunne pannenkoek met poedersuiker en stroop', 'Traditionele Nederlandse pannenkoek.', 'pancake.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `recipeinfo`
--

CREATE TABLE `recipeinfo` (
  `id` int(10) NOT NULL,
  `recipe_id` int(1) NOT NULL,
  `user_id` int(1) NOT NULL,
  `record_type` varchar(1) NOT NULL,
  `date` varchar(10) NOT NULL,
  `numeric_field` varchar(1) NOT NULL,
  `text_content` varchar(46) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `recipeinfo`
--

INSERT INTO `recipeinfo` (`id`, `recipe_id`, `user_id`, `record_type`, `date`, `numeric_field`, `text_content`) VALUES
(1, 1, 1, 'P', '25/07/2025', '1', 'Snijd alle groenten en munt fijn'),
(2, 1, 1, 'P', '25/07/2025', '2', 'Meng komkommer, tomaat, munt en feta'),
(3, 2, 2, 'P', '26/07/2025', '1', 'Bak het vlees medium'),
(4, 2, 2, 'P', '26/07/2025', '2', 'Voeg srirachasaus en ui toe'),
(5, 3, 3, 'P', '27/07/2025', '1', 'Kook de noedels volgens de verpakking'),
(6, 3, 3, 'P', '27/07/2025', '2', 'Voeg groenten en sojasaus toe'),
(7, 4, 4, 'P', '28/07/2025', '1', 'Maak het pannenkoekenbeslag'),
(8, 4, 4, 'P', '28/07/2025', '2', 'Bak de pannenkoek goudbruin'),
(9, 2, 3, 'C', '29/07/2025', '', 'Heerlijk, lekker pittig'),
(10, 2, 5, 'C', '30/07/2025', '', 'Oooo one of my favs, joa joa aanrader'),
(11, 1, 4, 'C', '31/07/2025', '', 'Frisse salade, perfect voor de zomer als sides'),
(12, 3, 1, 'C', '01/08/2025', '', 'Kan iets pittiger, maar erg lekker!'),
(13, 4, 2, 'C', '01/08/2025', '', 'Traditioneel en lekker'),
(14, 1, 5, 'R', '30/07/2025', '5', ''),
(15, 2, 4, 'R', '30/07/2025', '4', ''),
(16, 3, 5, 'R', '31/07/2025', '3', ''),
(17, 4, 3, 'R', '31/07/2025', '5', ''),
(18, 1, 3, 'F', '30/07/2025', '', ''),
(19, 2, 1, 'F', '31/07/2025', '', ''),
(20, 3, 2, 'F', '31/07/2025', '', ''),
(21, 4, 5, 'F', '01/08/2025', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) NOT NULL,
  `username` varchar(6) NOT NULL,
  `password` varchar(17) NOT NULL,
  `email` varchar(18) NOT NULL,
  `profilepic` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `profilepic`) VALUES
(1, 'Saar', '[hashed_password]', 'saar@example.com', 'profile1.jpg'),
(2, 'Rene', '[hashed_password]', 'rene@example.com', 'profile2.jpg'),
(3, 'Kevin', '[hashed_password]', 'kevin@example.com', 'profile3.jpg'),
(4, 'Joell', '[hashed_password]', 'joell@example.com', 'profile4.jpg'),
(5, 'Jeroen', '[hashed_password]', 'jeroen@example.com', 'profile5.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cuisinetype`
--
ALTER TABLE `cuisinetype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Indexes for table `recipe`
--
ALTER TABLE `recipe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cuisine_id` (`cuisine_id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `recipeinfo`
--
ALTER TABLE `recipeinfo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `test` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cuisinetype`
--
ALTER TABLE `cuisinetype`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `recipe`
--
ALTER TABLE `recipe`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `recipeinfo`
--
ALTER TABLE `recipeinfo`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD CONSTRAINT `ingredients_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`id`),
  ADD CONSTRAINT `ingredients_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`);

--
-- Constraints for table `recipe`
--
ALTER TABLE `recipe`
  ADD CONSTRAINT `recipe_ibfk_1` FOREIGN KEY (`cuisine_id`) REFERENCES `cuisinetype` (`id`),
  ADD CONSTRAINT `recipe_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `cuisinetype` (`id`),
  ADD CONSTRAINT `recipe_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `recipeinfo`
--
ALTER TABLE `recipeinfo`
  ADD CONSTRAINT `recipeinfo_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
