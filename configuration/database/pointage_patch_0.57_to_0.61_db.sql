-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Sam 28 novembre 2020 a� 11:08
-- Version du serveur :  10.1.23-MariaDB-9+deb9u1
-- Version de PHP :  5.6.30-0+deb8u1

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


ALTER TABLE `cegid_commande_prestataire` CHANGE `GROUPE` `TEAM`   VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `cegid_commande_prestataire` ADD            `PROFIL` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `TEAM`;
ALTER TABLE `cegid_commande_prestataire` ADD KEY        `cmd_presta_profil_profil_id` (`PROFIL`);
ALTER TABLE `cegid_commande_prestataire` ADD CONSTRAINT `cmd_presta_ibfk_1` FOREIGN KEY (`PROFIL`) REFERENCES `cegid_profil` (`ID`);


UPDATE `version` SET `DATE` = now(), `value` = '0.58.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.57._vers_0.58.0', '101', now(), 'update table commande prestataire', '0.58.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.48.08 vers_0.1.49.02', '200', now(), 'update table commande prestataire', '0.1.49.02');
UPDATE `version` SET `DATE` = now(), `description` = 'version fichier php minimal', `value` = '0.1.49.02' WHERE `version`.`id` = 'php';


UPDATE `version` SET `DATE` = now(), `value` = '0.59.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.58._vers_0.59.0', '101', now(), 'update table societe fournisseur', '0.59.0');


ALTER TABLE `cegid_societe_fournisseur` ADD  `ADRESSE` text   CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `NAME`;
ALTER TABLE `cegid_societe_client` ADD  `ADRESSE` text   CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `NAME`;

UPDATE `version` SET `DATE` = now(), `value` = '0.60.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.59._vers_0.60.0', '101', now(), 'update table societe fournisseur', '0.60.0');
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.49.02 vers_0.1.49.04', '200', now(), 'update adresse societe', '0.1.49.02');
UPDATE `version` SET `DATE` = now(), `description` = 'version fichier php minimal', `value` = '0.1.49.04' WHERE `version`.`id` = 'php';


ALTER TABLE `cegid_user` CHANGE `NOM` `NOM` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

UPDATE `version` SET `DATE` = now(), `value` = '0.61.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.60._vers_0.61.0', '101', now(), 'update table user', '0.61.0');


ALTER TABLE `cegid_project_cout_history` ADD `HISTORY_COMMENT` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `COMMENTAIRE`;
UPDATE `cegid_requetes` SET `SQL_REQUEST` = 'SELECT distinct HISTORY, HISTORY_ACTION, count(HISTORY),\r\HISTORY_COMMENT\r\nFROM cegid_project_cout_history\r\nWHERE \r\n 1\r\nGROUP BY HISTORY' WHERE `cegid_requetes`.`ID` = 'HISTORIQUE_COUT';

UPDATE `version` SET `DATE` = now(), `value` = '0.62.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.61._vers_0.62.0', '101', now(), 'update table user', '0.62.0');


ALTER TABLE `cegid_frais_mission` ADD `NON_REMBOURSABLE` DOUBLE NULL AFTER `FRAIS_EN_LOCAL`;
UPDATE `version` SET `DATE` = now(), `value` = '0.63.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.62._vers_0.63.0', '101', now(), 'update table frais mission', '0.63.0');

UPDATE `version` SET `DATE` = now(), `value` = '0.62.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.61._vers_0.62.0', '101', now(), 'update table frais mission', '0.62.0');


--
-- version DB 0.64
--


REPLACE INTO `cegid_requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`, `REQUEST_PARAM`, `VISIBLE`) VALUES('CMD_RENEW_PRESTA', 'CMD_RENEW_PRESTA', 'Prolonge les prestations encore en cours', '  Insert into cegid_commande_prestataire \r\n           ( ID,                                      USER_ID,    SOCIETE, TEAM, PROFIL, STATUS,    COMMANDE,   DEBUT, FIN,  TARIF_ACHAT, TARIF_VENTE, UO, COUT, VISIBLE, COMMENTAIRE)\r\n  SELECT     concat(\"CP_\",cp1.USER_ID,\"_\",cp2count), cp1.USER_ID, SOCIETE, TEAM, PROFIL, \"A Faire\", COMMANDE, \r\n    addtime( FIN,\'1 0:0:0\'), date(\"${FIN_NEW}\"), \r\n    TARIF_ACHAT, TARIF_VENTE, ${UO_NEW}, ${UO_NEW}*TARIF_ACHAT, VISIBLE, COMMENTAIRE\r\n  FROM cegid_commande_prestataire cp1,\r\n       ( SELECT count(*) as cp2count, USER_ID \r\n	     FROM cegid_commande_prestataire cp2\r\n		 GROUP BY USER_ID\r\n		) cp2\r\n  WHERE \r\n            date(fin) <= date(\"${FIN_OLD}\")\r\n      AND   STATUS = \"Cree\"\r\n     AND   cp1.user_id NOT IN ( select USER_ID from cegid_commande_prestataire where date(fin) > date(\"${FIN_OLD}\") )\r\n     AND   cp1.user_id = cp2.user_id;\r\n', '<tr><td>FIN_OLD</td>    <td><input type=\"text\" size=\"50\" name=\"FIN_OLD\"    value=\"2021-03-31\"  ></td></tr>\r\n<tr><td>FIN_NEW</td>    <td><input type=\"text\" size=\"50\" name=\"FIN_NEW\"    value=\"2021-06-30\"  ></td></tr>\r\n<tr><td>UO_NEW</td>    <td><input type=\"text\" size=\"50\" name=\"UO_NEW\"    value=\"55\"  ></td></tr>\r\n', 'Visible');

UPDATE `version` SET `DATE` = now(), `value` = '0.64.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.63._vers_0.64.0', '101', now(), 'add renew request for presta', '0.64.0');
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.49.02 vers_0.1.49.16', '200', now(), 'update table commande prestataire', '0.1.49.16');
UPDATE `version` SET `DATE` = now(), `description` = 'version fichier php minimal', `value` = '0.1.49.16' WHERE `version`.`id` = 'php';

--
-- a creer: nouvelle entree
--

ALTER TABLE `cegid_devis_project` CHANGE `SOCIETE` `SOCIETE` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

COMMIT;


