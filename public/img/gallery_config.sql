-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 27. Dez 2023 um 23:25
-- Server-Version: 10.4.24-MariaDB
-- PHP-Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `laravel`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gallery_config`
--

CREATE TABLE `gallery_config` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `option` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `gallery_config`
--

INSERT INTO `gallery_config` (`id`, `option`, `value`, `value2`, `created_at`, `updated_at`) VALUES
(1, 'pic_size_xl', '2000', NULL, '2023-12-26 14:51:29', '2023-12-26 14:51:31'),
(2, 'pic_size_l', '1000', NULL, '2023-12-26 14:51:56', '2023-12-26 14:51:54'),
(3, 'pic_size_m', '768', NULL, '2023-12-26 14:52:20', '2023-12-26 14:52:22'),
(4, 'pic_size_tn', '100', '100', '2023-12-26 14:52:20', '2023-12-26 14:52:20'),
(5, 'tmp_path', 'img/tmp', NULL, '2023-12-27 17:14:00', '2023-12-27 17:13:58');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `gallery_config`
--
ALTER TABLE `gallery_config`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `gallery_config`
--
ALTER TABLE `gallery_config`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
