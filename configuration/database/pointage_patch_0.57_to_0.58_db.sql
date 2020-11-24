-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Sam 05 Août 2017 à 14:15
-- Version du serveur :  10.1.23-MariaDB-9+deb9u1
-- Version de PHP :  5.6.30-0+deb8u1

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


ALTER TABLE `cegid_commande_prestataire` CHANGE `GROUPE` `TEAM`   VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `TEAM`;

ALTER TABLE `cegid_commande_prestataire` ADD            `PROFIL` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `cegid_commande_prestataire` ADD KEY        `cmd_presta_profil_profil_id` (`PROFIL`);
ALTER TABLE `cegid_commande_prestataire` ADD CONSTRAINT `cmd_presta_ibfk_1` FOREIGN KEY (`PROFIL`) REFERENCES `cegid_profil` (`ID`);
-- ALTER TABLE `cegid_commande_prestataire` CHANGE `PROFIL` `PROFIL` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `TEAM`;

UPDATE `version` SET `DATE` = now(), `value` = '0.58.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.57._vers_0.58.0', '101', now(), 'update table commande prestataire', '0.58.0');
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.48.08 vers_0.1.49.02', '200', now(), 'update table commande prestataire', '0.1.49.02');
UPDATE `version` SET `DATE` = now(), `description` = 'version fichier php minimal', `value` = '0.1.49.02' WHERE `version`.`id` = 'php';


COMMIT;


