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


--
-- Base de données :  `test`
--

-- --------------------------------------------------------


ALTER TABLE `cegid_status_evolution` 
CHANGE `STATUS` `STATUS` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;


UPDATE `cegid_requetes` SET `SQL_REQUEST` = 'SELECT cpc.ID, cp.DATE, cp.PROJECT_ID, p.NAME, p.STATUS, cpc.PROFIL_ID, cpc.commentaire, cpc.COUT, cpc.UO as UO_possible, \r\nsum(cp.UO) as UO_consomme, (cpc.UO - sum(cp.UO)) as UO_restant \r\nFROM (${ALL_CEGID_POINTAGE}) cp, cegid_project_cout cpc, cegid_project p \r\nWHERE \r\n  year(cp.DATE)=${year} \r\n  AND cp.PROJECT_ID = cpc.PROJECT_ID \r\n  AND cp.PROFIL = cpc.PROFIL_ID \r\n  AND year(cp.DATE) = year(cpc.DATE) \r\n  AND p.CEGID = cp.PROJECT_ID\r\nGROUP BY PROJECT_ID, PROFIL_ID' WHERE `cegid_requetes`.`ID` = 'UO_RESTANT';


--
-- Declaration Modification
--
  
UPDATE `version` SET `DATE` = now(), `value` = '0.49.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.48_vers_0.49', '101', now(), 'bug cloture', '0.49.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.42.0 vers_0.1.42.01', '200', now(), 'bug cloture', '0.1.42.1');
UPDATE `version` SET `DATE` = now(), `description` = 'version fichier php minimal', `value` = '0.1.42.01' WHERE `version`.`id` = 'php';



UPDATE `cegid_requetes` SET `SQL_REQUEST` = 'UPDATE `cegid_devis_project` \r\nSET `VISIBLE`=\'Archive\' \r\nWHERE \r\n STATUS_DEVIS LIKE \'Annule\' \r\n OR STATUS_DEVIS LIKE \'Clos\' \r\n OR STATUS_DEVIS LIKE \'Non repondu\' \r\n OR STATUS_DEVIS LIKE \'Refuse\' \r\n OR STATUS_COMMANDE LIKE \'Annule\'\r\n OR CEGID IN (SELECT CEGID FROM cegid_project WHERE VISIBLE like \'Archive\')' WHERE `cegid_requetes`.`ID` = 'ARCHIVE_DEVIS';


UPDATE `version` SET `DATE` = now(), `value` = '0.49.1' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.49.0_vers_0.49.1', '101', now(), 'update request cegid archive devis', '0.49.1');




--
-- Structure de la table `cegid_check_prix_vente_cout`
--

DROP TABLE IF EXISTS `cegid_check_prix_vente_cout`;

CREATE TABLE `cegid_check_prix_vente_cout` (
  `CEGID` varchar(10) CHARACTER SET utf8 NOT NULL,
  `COMMENTAIRE` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Explication différence Cout_Vente et Prix Devis';


--
-- Index pour la table `cegid_check_prix_vente_cout`
--
ALTER TABLE `cegid_check_prix_vente_cout`
  ADD PRIMARY KEY (`CEGID`),
  ADD CONSTRAINT `cegid_check_prix_vente_cout_ibfk_1` FOREIGN KEY (`CEGID`) REFERENCES `cegid_project` (`CEGID`);


 UPDATE `cegid_requetes` SET `SQL_REQUEST` = 'SELECT PROJECT_ID, NAME, STATUS, PRIX_VENTE, CA, (PRIX_VENTE - CA) as DIFF, cpvc.COMMENTAIRE\r\nFROM \r\n (${PRIX_VENTE}) pv\r\nLEFT JOIN \r\n cegid_check_prix_vente_cout cpvc ON pv.PROJECT_ID = cpvc.CEGID\r\nWHERE \r\n (PRIX_VENTE - CA)>6 \r\n OR (PRIX_VENTE - CA)<-6 \r\n' WHERE `cegid_requetes`.`ID` = 'CHECK_PRIX_VENTE';
  
  
INSERT INTO `cegid_check_prix_vente_cout`(`CEGID`, `COMMENTAIRE`) VALUES ("P17002","Normal : Facturation supplémentaire car quelques travaux en plus sans modification de Devis");

INSERT INTO `cegid_check_prix_vente_cout`(`CEGID`, `COMMENTAIRE`) VALUES ("P18902","Normal : CA différent car inclut des report CA demandé par V.B (120 000€ pour toute la TMA 2018)");

INSERT INTO `cegid_check_prix_vente_cout`(`CEGID`, `COMMENTAIRE`) VALUES ("P19901","Normal : PRIX Vente après report CA vers P19013 versus Siso 1.9");
INSERT INTO `cegid_check_prix_vente_cout`(`CEGID`, `COMMENTAIRE`) VALUES ("P19903","Normal : PRIX Vente après report CA vers P19013 versus Siso 1.9");
INSERT INTO `cegid_check_prix_vente_cout`(`CEGID`, `COMMENTAIRE`) VALUES ("P19904","Normal : PRIX Vente après report CA vers P19013 versus Siso 1.9");
INSERT INTO `cegid_check_prix_vente_cout`(`CEGID`, `COMMENTAIRE`) VALUES ("P19905","Normal : PRIX Vente après report CA vers P19013 versus Siso 1.9");
INSERT INTO `cegid_check_prix_vente_cout`(`CEGID`, `COMMENTAIRE`) VALUES ("P19906","Normal : PRIX Vente après report CA vers P19013 versus Siso 1.9");
INSERT INTO `cegid_check_prix_vente_cout`(`CEGID`, `COMMENTAIRE`) VALUES ("P19907","Normal : PRIX Vente après report CA vers P19013 versus Siso 1.9");
INSERT INTO `cegid_check_prix_vente_cout`(`CEGID`, `COMMENTAIRE`) VALUES ("P19908","Normal : PRIX Vente après report CA vers P19013 versus Siso 1.9");
INSERT INTO `cegid_check_prix_vente_cout`(`CEGID`, `COMMENTAIRE`) VALUES ("P19909","Normal : PRIX Vente après report CA vers P19013 versus Siso 1.9");

INSERT INTO `cegid_check_prix_vente_cout`(`CEGID`, `COMMENTAIRE`) VALUES ("P20905","Normal : CA groupement vs CA possible (gain perte maroc en différence)");
INSERT INTO `cegid_check_prix_vente_cout`(`CEGID`, `COMMENTAIRE`) VALUES ("P20907","Normal : CA groupement vs CA possible (gain perte maroc en différence)");


  
UPDATE `version` SET `DATE` = now(), `value` = '0.49.2' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.49.1_vers_0.49.2', '101', now(), 'add table cegid_check_prix_vente_cout', '0.49.2');


UPDATE `cegid_requetes` SET `SQL_REQUEST` = 'SELECT PROJECT_ID, NAME, STATUS, PRIX_VENTE, CA, (PRIX_VENTE - CA) as DIFF, cpvc.COMMENTAIRE\r\nFROM \r\n (${PRIX_VENTE}) pv\r\nLEFT JOIN \r\n cegid_check_prix_vente_cout cpvc ON pv.PROJECT_ID = cpvc.CEGID\r\nWHERE \r\n ( (PRIX_VENTE - CA)>6 \r\n OR (PRIX_VENTE - CA)<-6 )\r\n AND\r\n (SELECT VISIBLE From cegid_project cp WHERE cp.CEGID = pv.PROJECT_ID) like \"Visible\" \r\n' WHERE `cegid_requetes`.`ID` = 'CHECK_PRIX_VENTE';

UPDATE `version` SET `DATE` = now(), `value` = '0.49.3' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.49.2_vers_0.49.3', '101', now(), 'update check prix vente versus cout', '0.49.3');



COMMIT;


