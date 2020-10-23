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

ALTER TABLE `requetes` 
   ADD `VISIBLE` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Visible' AFTER `REQUEST_PARAM`;

ALTER TABLE `cegid_requetes` 
   ADD `VISIBLE` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Visible' AFTER `REQUEST_PARAM`;


  
UPDATE `version` SET `DATE` = now(), `value` = '0.50' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.49._vers_0.50', '101', now(), 'update structure table requestes', '0.50.0');


INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.43.01 vers_0.1.43.02', '200', now(), 'update structure table requestes', '0.1.43.02');
UPDATE `version` SET `DATE` = now(), `description` = 'version fichier php minimal', `value` = '0.1.43.02' WHERE `version`.`id` = 'php';






ALTER TABLE `cegid_frais_mission` CHANGE `STATUS_ID` `STATUS_ID` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'UNDEF';

UPDATE `version` SET `DATE` = now(), `value` = '0.51' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.50._vers_0.51', '101', now(), 'update cegid frais', '0.51.0');

ALTER TABLE `files` ADD `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP AFTER `version`;

UPDATE `version` SET `DATE` = now(), `value` = '0.52' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.51._vers_0.52', '101', now(), 'update table files', '0.52.0');







INSERT INTO `cegid_requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`, `REQUEST_PARAM`, `VISIBLE`) 
VALUES (
	'HISTORIQUE_PREVISIONNEL', 
	'LISTE HISTORIQUE_PREVISIONNEL', 
	'liste les differentes dates de backup des couts.\r\nPermet si besoin une retauration apres l action cloture.', 
	'SELECT distinct HISTORY, HISTORY_ACTION, count(HISTORY)\r\nFROM cegid_pointage_previsionnel_history\r\nWHERE \r\n 1\r\nGROUP BY HISTORY desc', '', 'Visible');


UPDATE `version` SET `DATE` = now(), `value` = '0.53' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.52._vers_0.53', '101', now(), 'create request history pointage previsionnel', '0.53.0');






REPLACE INTO `requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`, `REQUEST_PARAM`, `VISIBLE`) VALUES('BUDGET_PAR_ANNEE', 'Budget par année', 'recupère le budget par année pour les projets non archivés', 'select CEGID, NAME, GROUPE, TYPE, STATUS, PRIX_VENTE, DATE, ROUND(sum(UO),1) as UO_annee,  ROUND(sum(Budget)) as Budget_annee ,  COMMENTAIRE\r\nFrom (\r\n    select p.CEGID, p.NAME, p.GROUPE, p.TYPE, p.STATUS, p.PRIX_VENTE, pc.DATE, pc.PROFIL_ID, pc.UO, pc.COUT, pc.UO * pc.COUT as Budget ,pc.COMMENTAIRE\r\n	from cegid_project p, cegid_project_cout pc \r\n	where	\r\n		p.CEGID = pc.PROJECT_ID\r\n    	AND p.VISIBLE = \"visible\"\r\n	) affaire\r\nWHERE 1\r\nGROUP BY CEGID, DATE', '', 'Visible');
REPLACE INTO `requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`, `REQUEST_PARAM`, `VISIBLE`) VALUES('BUDGET_PAR_PROJET', 'Budget par projet', 'recupère le budget par projet non archivés', 'select CEGID, NAME, GROUPE, TYPE, STATUS, PRIX_VENTE, DATE, sum(UO_annee) as UO_Prevu,  sum(Budget_annee) as Budget\r\nFROM (\r\n    select CEGID, NAME, GROUPE, TYPE, STATUS, PRIX_VENTE, DATE, ROUND(sum(UO),1) as UO_annee,  ROUND(sum(Budget)) as Budget_annee ,  COMMENTAIRE\r\n    From (\r\n        select p.CEGID, p.NAME, p.GROUPE, p.TYPE, p.STATUS, p.PRIX_VENTE, pc.DATE, pc.PROFIL_ID, pc.UO, pc.COUT, pc.UO * pc.COUT as Budget ,pc.COMMENTAIRE\r\n        from cegid_project p, cegid_project_cout pc \r\n        where	\r\n            p.CEGID = pc.PROJECT_ID\r\n            AND p.VISIBLE = \"visible\"\r\n        ) affaire_annee\r\n    WHERE 1\r\n    GROUP BY CEGID, DATE\r\n    ) affaire\r\nWHERE 1\r\nGROUP BY CEGID', '', 'Visible');
REPLACE INTO `requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`, `REQUEST_PARAM`, `VISIBLE`) VALUES('check_document_cegid', 'check_document_cegid', 'recherche le nombre de document par rapport Ã  un projet CEGID', 'select d.id as devis, p.cegid, p.name , p.status as project_status, 0 as nbDocument\r\nfrom cegid_project p, cegid_devis_project d\r\nWHERE \r\n        d.id not in (select reference from cegid_file) \r\n    and p.cegid = d.cegid\r\n    and p.status != \"Annule\"\r\n\r\n    \r\n', '', 'Visible');
REPLACE INTO `requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`, `REQUEST_PARAM`, `VISIBLE`) VALUES('CONSOMME_PAR_ANNEE', 'Consomme par année', 'Récupère le budget & consomme par année pour les projets non archivés', 'select CEGID, NAME, GROUPE, TYPE, STATUS, PRIX_VENTE, Annee, round(sum(Budget_Profil)) as Budget, round(sum(Consomme_Profil)) as Consomme, round(sum(UO_Prevu_Profil),1) as UO_Prevu, round(sum(UO_Consomme_Profil),1) as UO_Consomme\r\nFROM (\r\n    select CEGID, NAME, GROUPE, TYPE, STATUS, PRIX_VENTE, Annee, PROFIL_ID, UO_Prevu_Profil, COUT, Budget_Profil, sum(UO_Pointage) as UO_Consomme_Profil, sum(Consomme_Profil_user) as Consomme_Profil\r\n    FROM (\r\n        select pj.CEGID, pj.NAME, pj.GROUPE, pj.TYPE, pj.STATUS, pj.PRIX_VENTE, year(pc.DATE) as Annee, pc.PROFIL_ID, pc.UO as UO_Prevu_Profil, pc.COUT, pc.UO * pc.COUT as Budget_Profil ,month(pt.DATE) Mois, pt.USER_ID, pt.UO as UO_Pointage, pt.UO * pc.COUT as Consomme_Profil_user\r\n        from cegid_project pj, cegid_project_cout pc, cegid_pointage pt \r\n        where	\r\n            pj.CEGID = pc.PROJECT_ID\r\n            AND pj.CEGID = pt.PROJECT_ID\r\n            AND pt.PROFIL = pc.PROFIL_ID\r\n            AND year(pt.DATE) = year(pc.DATE)\r\n            AND pj.VISIBLE = \"visible\"\r\n        ) affaire_profil_annee\r\n    WHERE 1\r\n    GROUP BY CEGID, Annee, PROFIL_ID\r\n    ) affaire_annee\r\nWHERE 1\r\nGROUP BY CEGID, Annee\r\n', '', 'Visible');
REPLACE INTO `requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`, `REQUEST_PARAM`, `VISIBLE`) VALUES('CONSOMME_TOTAL_PAR_PROJET', 'Consomme total par projet', 'Récupère le budget & consomme par par les projets non archivés.\r\nle consomme comprend toutes les année', 'select CEGID, NAME, GROUPE, TYPE, STATUS, PRIX_VENTE, min(Annee), sum(Budget), sum(Consomme), sum(UO_Prevu), sum(UO_Consomme)\r\nFROM (\r\n    select CEGID, NAME, GROUPE, TYPE, STATUS, PRIX_VENTE, Annee, round(sum(Budget_Profil)) as Budget, round(sum(Consomme_Profil)) as Consomme, round(sum(UO_Prevu_Profil),1) as UO_Prevu, round(sum(UO_Consomme_Profil),1) as UO_Consomme\r\n    FROM (\r\n        select CEGID, NAME, GROUPE, TYPE, STATUS, PRIX_VENTE, Annee, PROFIL_ID, UO_Prevu_Profil, COUT, Budget_Profil, sum(UO_Pointage) as UO_Consomme_Profil, sum(Consomme_Profil_user) as Consomme_Profil\r\n        FROM (\r\n            select pj.CEGID, pj.NAME, pj.GROUPE, pj.TYPE, pj.STATUS, pj.PRIX_VENTE, year(pc.DATE) as Annee, pc.PROFIL_ID, pc.UO as UO_Prevu_Profil, pc.COUT, pc.UO * pc.COUT as Budget_Profil ,month(pt.DATE) Mois, pt.USER_ID, pt.UO as UO_Pointage, pt.UO * pc.COUT as Consomme_Profil_user\r\n            from cegid_project pj, cegid_project_cout pc, cegid_pointage pt \r\n            where	\r\n                pj.CEGID = pc.PROJECT_ID\r\n                AND pj.CEGID = pt.PROJECT_ID\r\n                AND pt.PROFIL = pc.PROFIL_ID\r\n                AND year(pt.DATE) = year(pc.DATE)\r\n                AND pj.VISIBLE = \"visible\"\r\n            ) affaire_profil_annee\r\n        WHERE 1\r\n        GROUP BY CEGID, Annee, PROFIL_ID\r\n        ) affaire_annee\r\n    WHERE 1\r\n    GROUP BY CEGID, Annee\r\n    ) affaire\r\nWHERE 1\r\nGROUP BY CEGID\r\n', '', 'Visible');

UPDATE `version` SET `DATE` = now(), `value` = '0.54' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.53._vers_0.54', '101', now(), 'create request budget et consomme', '0.54.0');






ALTER TABLE `cegid_profil` 
   ADD `VISIBLE` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Visible' AFTER `NAME`;


UPDATE `version` SET `DATE` = now(), `value` = '0.55' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.54._vers_0.55', '101', now(), 'table profil : add visible field', '0.55.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.43.01 vers_0.1.46.00', '200', now(), 'table profil : add visible field', '0.1.46.00');
UPDATE `version` SET `DATE` = now(), `description` = 'version fichier php minimal', `value` = '0.1.46.00' WHERE `version`.`id` = 'php';




ALTER TABLE `cegid_type_project` CHANGE `ID` `ID` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
INSERT INTO `cegid_requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`, `REQUEST_PARAM`, `VISIBLE`) VALUES ('ID_REQUETE_SQL_PROFIL_ARCHIVABLE', 'Detection PROFIL_ARCHIVABLE', 'Permet de detecter les profils archivable (non utilisé dans les projets ouvert)', ' SELECT DISTINCT * FROM (\r\n\r\n SELECT DISTINCT * FROM cegid_profil cpf \r\n WHERE\r\n cpf.ID not in (\r\n SELECT cpc.PROFIL_ID from cegid_project pj, cegid_project_cout cpc\r\n WHERE\r\n pj.CEGID = cpc.PROJECT_ID\r\n AND pj.VISIBLE = \"Visible\"\r\n )\r\n\r\n UNION DISTINCT\r\n\r\n SELECT DISTINCT * FROM cegid_profil cpf \r\n WHERE\r\n cpf.ID not in (\r\n SELECT cpc2.PROFIL_ID from cegid_project_cout cpc2\r\n WHERE 1\r\n )\r\n )profil\r\nORDER BY profil.ID', NULL, 'Visible');

UPDATE `version` SET `DATE` = now(), `value` = '0.56' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.55._vers_0.56', '101', now(), 'table request cegid : add request profils', '0.56.0');

-- 
-- gestion status commande prestataires
-- 

REPLACE INTO `cegid_requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`, `REQUEST_PARAM`, `VISIBLE`) VALUES('CMD_PRESTA_A_FAIRE_TO_DDE',  'CMD_PRESTA_A_FAIRE_TO_DDE',  'passe les commande prestataire de [a faire] � [demand�]', 'UPDATE cegid_commande_prestataire  set STATUS = \"Demande\" WHERE STATUS = \"A faire\" And year(now()) >= year(Debut) AND month(now()) >= month(DEBUT);\r\n', '', 'Visible');
REPLACE INTO `cegid_requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`, `REQUEST_PARAM`, `VISIBLE`) VALUES('CMD_PRESTA_DDE_TO_CREE',     'CMD_PRESTA_DDE_TO_CREE',     'passe les commande prestataire de [demand�] � [cr��]',    'UPDATE cegid_commande_prestataire  set STATUS = \"Cree\"    WHERE STATUS = \"Demande\" And year(now()) >= year(FIN) AND month(now()) > month(DEBUT);\r\n',    '', 'Visible');
REPLACE INTO `cegid_requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`, `REQUEST_PARAM`, `VISIBLE`) VALUES('CMD_PRESTA_CREE_TO_CLOS',    'CMD_PRESTA_CREEE_TO_CLOS',   'passe les commande prestataire de [cr��] � [clos]',       'UPDATE cegid_commande_prestataire  set STATUS = \"Clos\"    WHERE STATUS = \"Cree\"    And now() > FIN;\r\n',                                                 '', 'Visible');
REPLACE INTO `cegid_requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`, `REQUEST_PARAM`, `VISIBLE`) VALUES('ARCHIVE_CMD_PRESTATAIRES',   'ARCHIVE_CMD_PRESTATAIRES',   'Archivage des commandes prestataires clos',               'UPDATE `cegid_commande_prestataire` \r\nSET `VISIBLE`=\'Archive\'  WHERE STATUS LIKE \'Clos\' OR STATUS LIKE \'Annule\'',                                     '', 'Visible');
REPLACE INTO `cegid_requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`, `REQUEST_PARAM`, `VISIBLE`) VALUES('UNARCHIVE_CMD_PRESTATAIRES', 'UNARCHIVE_CMD_PRESTATAIRES', 'UnArchivage des commandes prestataires archiv�',         'UPDATE `cegid_commande_prestataire` \r\nSET `VISIBLE`=\'Visible\'  WHERE STATUS LIKE \'Clos\' OR STATUS LIKE \'Annule\'',                                      '', 'Visible');



UPDATE `version` SET `DATE` = now(), `value` = '0.57' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.56._vers_0.57', '101', now(), 'table request cegid : add request profils', '0.57.0');


COMMIT;


