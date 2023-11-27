SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `prwb_2324_a03`
--
DROP DATABASE IF EXISTS `prwb_2324_a03`;
CREATE DATABASE IF NOT EXISTS `prwb_2324_a03` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `prwb_2324_a03`;


DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `hashed_password` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `full_name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `owner` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `edited_at` datetime NULL,
  `pinned` boolean NOT NULL DEFAULT FALSE,
  `archived` boolean NOT NULL DEFAULT FALSE,
  `weight` double NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `notes` ADD CONSTRAINT `fk_notes_users` FOREIGN KEY (`owner`) REFERENCES `users`(`id`);

DROP TABLE IF EXISTS `note_shares`;
CREATE TABLE IF NOT EXISTS `note_shares` (
  `note` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `editor` boolean NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`note`, `user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `note_shares` ADD CONSTRAINT `fk_note_shares_notes` FOREIGN KEY (`note`) REFERENCES `notes`(`id`);
ALTER TABLE `note_shares` ADD CONSTRAINT `fk_note_shares_users` FOREIGN KEY (`user`) REFERENCES `users`(`id`);

DROP TABLE IF EXISTS `text_notes`;
CREATE TABLE IF NOT EXISTS `text_notes` (
  `id` int(11) NOT NULL,
  `content` text COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `text_notes` ADD CONSTRAINT `fk_text_notes_notes` FOREIGN KEY (`id`) REFERENCES `notes`(`id`);

DROP TABLE IF EXISTS `checklist_notes`;
CREATE TABLE IF NOT EXISTS `checklist_notes` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `checklist_notes` ADD CONSTRAINT `fk_checklist_notes_notes` FOREIGN KEY (`id`) REFERENCES `notes`(`id`);


DROP TABLE IF EXISTS `checklist_note_items`;
CREATE TABLE IF NOT EXISTS `checklist_note_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checklist_note` int(11) NOT NULL,
  `content` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `checked` boolean NOT NULL DEFAULT FALSE, 
  PRIMARY KEY (`id`),
  UNIQUE(`checklist_note`, `content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `checklist_note_items` ADD CONSTRAINT `fkchecklist_note_items_checklist_notes` FOREIGN KEY (`checklist_note`) REFERENCES `checklist_notes`(`id`);



