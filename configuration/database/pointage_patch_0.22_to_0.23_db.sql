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


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `test`
--


--
-- Structure de la table `requetes`
--

CREATE TABLE IF NOT EXISTS `cegid_requetes` (
  `ID` varchar(100) NOT NULL COMMENT 'identifiant',
  `NAME` varchar(100) NOT NULL,
  `DESCRIPTION` text,
  `SQL_REQUEST` text NOT NULL COMMENT 'la requête stockée'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table contenant des requetes';



--
-- Index pour les tables exportées
--

--
-- Index pour la table `requetes`
--
ALTER TABLE `cegid_requetes`
 ADD PRIMARY KEY (`ID`);


TRUNCATE TABLE `cegid_requetes`;


--
-- Contenu de la table `requetes`
--

INSERT INTO `cegid_requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`) VALUES
('Actuel CA_projet', 'Actuel CA_projet', 'Actuel CA par projet', 'select CA_PROFIL.project_id, CA_PROFIL.NAME, status, \r\n	sum(uo_possible) as uo_possible, sum(CA_possible) as CA_possible, \r\n	sum(uo_consomme) as uo_consomme, sum(consomme_total) as consomme_total, \r\n	least(  sum(CA_PROFIL.CA_possible) , sum(CA_PROFIL.consomme_total) ) as CA_realise from\r\n(\r\n	select p.project_id, cp.NAME,  p.profil, c.cout, c.uo as uo_possible, (c.uo * c.cout ) as CA_possible, sum(p.uo) uo_consomme,  (sum(p.uo)*cout) as consomme_total, least( (sum(p.uo)*cout) , c.uo * cout ) as CA_realise\r\n	from ( \r\n			(\r\n				select PROJECT_ID, DATE, PROFIL_ID as PROFIL, (select 0) as USER_ID,  (select 0) as UO\r\n				from  cegid_project_cout c\r\n				where not exists (select * from cegid_pointage where c.PROFIL_ID = cegid_pointage.PROFIL and c.project_id = cegid_pointage.project_id)\r\n			)\r\n			union\r\n			(\r\n				select PROJECT_ID, DATE, PROFIL, USER_ID,  UO from  cegid_pointage  \r\n			)\r\n	) p, cegid_project_cout c, cegid_project cp\r\n	where \r\n	 cp.CEGID =p.PROJECT_ID\r\n	 and year(p.date)=${year} and year(c.date)=${year} \r\n	 and p.PROJECT_ID = c.PROJECT_ID\r\n	 and p.PROFIL = c.PROFIL_ID\r\n	 group by p.project_id, p.profil\r\n	 order by p.project_id\r\n) CA_PROFIL , cegid_project\r\nwhere \r\n   CA_PROFIL.project_id = cegid_project.cegid \r\ngroup by CA_PROFIL.project_id\r\n\r\n\r\n'),
('Actuel CA_projet_clos', 'Actuel CA_projet_clos', 'Actuel CA par projet . prise en compte projet clos', 'select CA_PROFIL.project_id, CA_PROFIL.NAME, status, \r\n	sum(uo_possible) as uo_possible, sum(CA_possible) as CA_possible, \r\n	sum(uo_consomme) as uo_consomme, sum(consomme_total) as consomme_total, \r\n	if (status=\"Clos\",sum(CA_PROFIL.CA_possible), least(  sum(CA_PROFIL.CA_possible) , sum(CA_PROFIL.consomme_total) )) as CA_realise from\r\n(\r\n	select p.project_id, cp.NAME,  p.profil, c.cout, c.uo as uo_possible, (c.uo * c.cout ) as CA_possible, sum(p.uo) uo_consomme,  (sum(p.uo)*cout) as consomme_total, least( (sum(p.uo)*cout) , c.uo * cout ) as CA_realise\r\n	from ( \r\n			(\r\n				select PROJECT_ID, DATE, PROFIL_ID as PROFIL, (select 0) as USER_ID,  (select 0) as UO\r\n				from  cegid_project_cout c\r\n				where not exists (select * from cegid_pointage where c.PROFIL_ID = cegid_pointage.PROFIL and c.project_id = cegid_pointage.project_id)\r\n			)\r\n			union\r\n			(\r\n				select PROJECT_ID, DATE, PROFIL, USER_ID,  UO from  cegid_pointage  \r\n			)\r\n	) p, cegid_project_cout c, cegid_project cp\r\n	where \r\n	 cp.CEGID =p.PROJECT_ID\r\n	 and year(p.date)=${year} and year(c.date)=${year} \r\n	 and p.PROJECT_ID = c.PROJECT_ID\r\n	 and p.PROFIL = c.PROFIL_ID\r\n	 group by p.project_id, p.profil\r\n	 order by p.project_id\r\n) CA_PROFIL , cegid_project\r\nwhere \r\n   CA_PROFIL.project_id = cegid_project.cegid \r\ngroup by CA_PROFIL.project_id\r\n\r\n\r\n'),
('CA Responsable Affaires', 'CA Responsable Affaires', 'Represente le CA de la fiche de responsable d\'affaires (Left join)', '(\r\n  SELECT *\r\n  FROM (${CA Responsable Affaires Left}) ral\r\n)\r\nUNION\r\n(\r\n  SELECT *\r\n  FROM (${CA Responsable Affaires Right}) rar\r\n)'),
('CA Responsable Affaires Left', 'CA Responsable Affaires Left', 'CA de la fiche de responsable d\'affaires (Left join)', 'SELECT  \r\n   caa.project_id, caa.NAME, caa.status, caa.uo_possible,\r\n   caa.uo_consomme as uo_consomme_reel, caa.consomme_total as consomme_total_reel, caa.CA_realise as CA_Realise_reel,\r\n   cap.uo_consomme as uo_consomme_prev, cap.consomme_total as consomme_total_prev, cap.CA_realise as CA_Realise_prev\r\n\r\nFROM (${Actuel CA_projet}) caa\r\nLEFT JOIN (${Previsionnel CA_projet}) cap\r\nON caa.project_id = cap.project_id'),
('CA Responsable Affaires Right', 'CA Responsable Affaires Right', 'CA de la fiche de responsable d\'affaires (Right join)', 'SELECT  \r\n   cap.project_id, cap.NAME, cap.status, cap.uo_possible,\r\n   caa.uo_consomme as uo_consomme_reel, caa.consomme_total as consomme_total_reel, caa.CA_realise as CA_Realise_reel,\r\n   cap.uo_consomme as uo_consomme_prev, cap.consomme_total as consomme_total_prev, cap.CA_realise as CA_Realise_prev\r\n\r\nFROM   (${Previsionnel CA_projet}) cap\r\nLEFT JOIN (${Actuel CA_projet}) caa\r\nON caa.project_id = cap.project_id'),
('diff CA', 'Difference de CA 2017', 'Difference sur le CA reel et prevision', 'SELECT    \r\n    year(diff_pointage.DATE) as YEAR , diff_pointage.PROJECT_ID, cegid_project.NAME as PROJECT, diff_pointage.USER_ID, cegid_user.NOM, \r\n    diff_pointage.PROFIL, diff_pointage.DATE, diff_pointage.UO_Reel, diff_pointage.UO_Prevision, cegid_project.STATUS\r\n#,cegid_project.DEBUT, cegid_project.FIN, cegid_project.FIN_GARANTIE,  cegid_project.COMMENTAIRE\r\nFROM (\r\n    (\r\n        select * from (\r\n        SELECT cp.PROJECT_ID, cp.DATE, cp.USER_ID, cp.PROFIL, cp.UO as UO_Reel, cpp.UO as UO_Prevision\r\n        FROM `cegid_pointage` cp  \r\n        LEFT JOIN cegid_pointage_previsionnel cpp \r\n        ON cp.PROJECT_ID = cpp.PROJECT_ID \r\n            and cp.DATE = cpp.DATE \r\n            and cp.USER_ID = cpp.USER_ID\r\n            and cp.PROFIL = cpp.PROFIL\r\n        ) pointage1\r\n        where UO_Prevision <> UO_Reel or UO_Prevision is NULL or UO_Reel is NULL\r\n    )\r\n    UNION\r\n    (\r\n        select * from (\r\n        SELECT cpp.PROJECT_ID, cpp.DATE, cpp.USER_ID, cpp.PROFIL, cp.UO as UO_Reel, cpp.UO as UO_Prevision\r\n        FROM `cegid_pointage_previsionnel` cpp\r\n        LEFT JOIN cegid_pointage cp \r\n        ON cp.PROJECT_ID = cpp.PROJECT_ID \r\n            and cp.DATE = cpp.DATE \r\n            and cp.USER_ID = cpp.USER_ID\r\n            and cp.PROFIL = cpp.PROFIL\r\n        ) pointage2\r\n        where UO_Prevision <> UO_Reel or UO_Prevision is NULL or UO_Reel is NULL\r\n    )\r\n) diff_pointage, cegid_project, cegid_user\r\nWHERE \r\n    cegid_project.CEGID = diff_pointage.PROJECT_ID and cegid_user.ID = diff_pointage.USER_ID\r\n    and year(DATE) = ${year}\r\nORDER BY YEAR, PROJECT, DATE\r\n'),
('Previsionnel CA_projet', 'Previsionnel CA_projet', 'Previsionnel CA par projet', 'select CA_PROFIL.project_id, CA_PROFIL.NAME, status, \r\n	sum(uo_possible) as uo_possible, sum(CA_possible) as CA_possible, \r\n	sum(uo_consomme) as uo_consomme, sum(consomme_total) as consomme_total, \r\n	least(  sum(CA_PROFIL.CA_possible) , sum(CA_PROFIL.consomme_total) ) as CA_realise from\r\n(\r\n	select p.project_id, cp.NAME,  p.profil, c.cout, c.uo as uo_possible, (c.uo * c.cout ) as CA_possible, sum(p.uo) uo_consomme,  (sum(p.uo)*cout) as consomme_total, least( (sum(p.uo)*cout) , c.uo * cout ) as CA_realise\r\n	from ( \r\n			(\r\n				select PROJECT_ID, DATE, PROFIL_ID as PROFIL, (select 0) as USER_ID,  (select 0) as UO\r\n				from  cegid_project_cout c\r\n				where not exists (select * from cegid_pointage_previsionnel where c.PROFIL_ID = cegid_pointage_previsionnel.PROFIL and c.project_id = cegid_pointage_previsionnel.project_id)\r\n			)\r\n			union\r\n			(\r\n				select PROJECT_ID, DATE, PROFIL, USER_ID,  UO from  cegid_pointage_previsionnel  \r\n			)\r\n	) p, cegid_project_cout c, cegid_project cp\r\n	where \r\n	 cp.CEGID =p.PROJECT_ID\r\n	 and year(p.date)=${year} and year(c.date)=${year} \r\n	 and p.PROJECT_ID = c.PROJECT_ID\r\n	 and p.PROFIL = c.PROFIL_ID\r\n	 group by p.project_id, p.profil\r\n	 order by p.project_id\r\n) CA_PROFIL , cegid_project\r\nwhere \r\n   CA_PROFIL.project_id = cegid_project.cegid \r\ngroup by CA_PROFIL.project_id\r\n\r\n\r\n'),
('Previsionnel CA_projet_clos', 'Previsionnel CA_projet_clos', 'Previsionnel CA par projet . prise en compte projet clos', 'select CA_PROFIL.project_id, CA_PROFIL.NAME, status, \r\n	sum(uo_possible) as uo_possible, sum(CA_possible) as CA_possible, \r\n	sum(uo_consomme) as uo_consomme, sum(consomme_total) as consomme_total, \r\n	if (status=\"Clos\",sum(CA_PROFIL.CA_possible), least(  sum(CA_PROFIL.CA_possible) , sum(CA_PROFIL.consomme_total) )) as CA_realise from\r\n(\r\n	select p.project_id, cp.NAME,  p.profil, c.cout, c.uo as uo_possible, (c.uo * c.cout ) as CA_possible, sum(p.uo) uo_consomme,  (sum(p.uo)*cout) as consomme_total, least( (sum(p.uo)*cout) , c.uo * cout ) as CA_realise\r\n	from ( \r\n			(\r\n				select PROJECT_ID, DATE, PROFIL_ID as PROFIL, (select 0) as USER_ID,  (select 0) as UO\r\n				from  cegid_project_cout c\r\n				where not exists (select * from cegid_pointage_previsionnel where c.PROFIL_ID = cegid_pointage_previsionnel.PROFIL and c.project_id = cegid_pointage_previsionnel.project_id)\r\n			)\r\n			union\r\n			(\r\n				select PROJECT_ID, DATE, PROFIL, USER_ID,  UO from  cegid_pointage_previsionnel  \r\n			)\r\n	) p, cegid_project_cout c, cegid_project cp\r\n	where \r\n	 cp.CEGID =p.PROJECT_ID\r\n	 and year(p.date)=${year} and year(c.date)=${year} \r\n	 and p.PROJECT_ID = c.PROJECT_ID\r\n	 and p.PROFIL = c.PROFIL_ID\r\n	 group by p.project_id, p.profil\r\n	 order by p.project_id\r\n) CA_PROFIL , cegid_project\r\nwhere \r\n   CA_PROFIL.project_id = cegid_project.cegid \r\ngroup by CA_PROFIL.project_id\r\n\r\n\r\n'),
('Responsable Affaires', 'Responsable Affaires', 'Represente la fiche de responsable d\'affaires (Left join)', 'SELECT \r\n\r\n   cara.project_id, cara.NAME, cara.status, cara.uo_possible,\r\n   cara.uo_consomme_reel, cara.consomme_total_reel, cara.CA_Realise_reel,\r\n   cara.uo_consomme_prev, cara.consomme_total_prev, cara.CA_Realise_prev,\r\n   cp.PRIX_VENTE, cp.DEBUT, cp.FIN, cp.FIN_GARANTIE\r\n\r\n\r\n\r\nFROM (${CA Responsable Affaires}) cara, cegid_project cp\r\nWHERE\r\n  cara.project_id = cp.CEGID\r\n');




 
DELETE FROM `requetes` WHERE `requetes`.`ID` = 'Actuel CA_projet';
DELETE FROM `requetes` WHERE `requetes`.`ID` = 'Actuel CA_projet_clos';
DELETE FROM `requetes` WHERE `requetes`.`ID` = 'Previsionnel CA_projet';
DELETE FROM `requetes` WHERE `requetes`.`ID` = 'Previsionnel CA_projet_clos';
DELETE FROM `requetes` WHERE `requetes`.`ID` = 'diff CA';
DELETE FROM `requetes` WHERE `requetes`.`ID` = 'diff CA 2017';
DELETE FROM `requetes` WHERE `requetes`.`ID` = 'Cout TMA R vs P';

INSERT INTO `requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`) VALUES
('Cout TMA R vs P', 'Cout TMA 2017 Reel vs Prev', 'Cout TMA 2017', 'SELECT * FROM (\r\n  (${Cout projets TMA}) tmaR\r\njoin\r\n  ( ${Previsionnel Cout projets TMA}) tmaP\r\non tmaR.project_id = tmaP.project_id )'),
('diff CA', 'Difference de CA', 'Difference sur le CA reel et prevision', 'SELECT    \r\n    diff_pointage.PROJECT_ID, cegid_project.NAME as PROJECT, diff_pointage.USER_ID, cegid_user.NOM, \r\n    diff_pointage.PROFIL, diff_pointage.DATE, diff_pointage.UO_Reel, diff_pointage.UO_Prevision, cegid_project.STATUS\r\n#,cegid_project.DEBUT, cegid_project.FIN, cegid_project.FIN_GARANTIE,  cegid_project.COMMENTAIRE\r\nFROM (\r\n    (\r\n        select * from (\r\n        SELECT cp.PROJECT_ID, cp.DATE, cp.USER_ID, cp.PROFIL, cp.UO as UO_Reel, cpp.UO as UO_Prevision\r\n        FROM `cegid_pointage` cp  \r\n        LEFT JOIN cegid_pointage_previsionnel cpp \r\n        ON cp.PROJECT_ID = cpp.PROJECT_ID \r\n            and cp.DATE = cpp.DATE \r\n            and cp.USER_ID = cpp.USER_ID\r\n            and cp.PROFIL = cpp.PROFIL\r\n        ) pointage1\r\n        where UO_Prevision <> UO_Reel or UO_Prevision is NULL or UO_Reel is NULL\r\n    )\r\n    UNION\r\n    (\r\n        select * from (\r\n        SELECT cpp.PROJECT_ID, cpp.DATE, cpp.USER_ID, cpp.PROFIL, cp.UO as UO_Reel, cpp.UO as UO_Prevision\r\n        FROM `cegid_pointage_previsionnel` cpp\r\n        LEFT JOIN cegid_pointage cp \r\n        ON cp.PROJECT_ID = cpp.PROJECT_ID \r\n            and cp.DATE = cpp.DATE \r\n            and cp.USER_ID = cpp.USER_ID\r\n            and cp.PROFIL = cpp.PROFIL\r\n        ) pointage2\r\n        where UO_Prevision <> UO_Reel or UO_Prevision is NULL or UO_Reel is NULL\r\n    )\r\n) diff_pointage, cegid_project, cegid_user\r\nWHERE \r\n    cegid_project.CEGID = diff_pointage.PROJECT_ID and cegid_user.ID = diff_pointage.USER_ID\r\n    and year(DATE) = 2017\r\nORDER BY PROJECT, DATE\r\n'),
('diff CA 2017', 'Difference de CA 2017', 'Difference sur le CA reel et prevision', 'SELECT    \r\n    year(diff_pointage.DATE) as YEAR , diff_pointage.PROJECT_ID, cegid_project.NAME as PROJECT, diff_pointage.USER_ID, cegid_user.NOM, \r\n    diff_pointage.PROFIL, diff_pointage.DATE, diff_pointage.UO_Reel, diff_pointage.UO_Prevision, cegid_project.STATUS\r\n#,cegid_project.DEBUT, cegid_project.FIN, cegid_project.FIN_GARANTIE,  cegid_project.COMMENTAIRE\r\nFROM (\r\n    (\r\n        select * from (\r\n        SELECT cp.PROJECT_ID, cp.DATE, cp.USER_ID, cp.PROFIL, cp.UO as UO_Reel, cpp.UO as UO_Prevision\r\n        FROM `cegid_pointage` cp  \r\n        LEFT JOIN cegid_pointage_previsionnel cpp \r\n        ON cp.PROJECT_ID = cpp.PROJECT_ID \r\n            and cp.DATE = cpp.DATE \r\n            and cp.USER_ID = cpp.USER_ID\r\n            and cp.PROFIL = cpp.PROFIL\r\n        ) pointage1\r\n        where UO_Prevision <> UO_Reel or UO_Prevision is NULL or UO_Reel is NULL\r\n    )\r\n    UNION\r\n    (\r\n        select * from (\r\n        SELECT cpp.PROJECT_ID, cpp.DATE, cpp.USER_ID, cpp.PROFIL, cp.UO as UO_Reel, cpp.UO as UO_Prevision\r\n        FROM `cegid_pointage_previsionnel` cpp\r\n        LEFT JOIN cegid_pointage cp \r\n        ON cp.PROJECT_ID = cpp.PROJECT_ID \r\n            and cp.DATE = cpp.DATE \r\n            and cp.USER_ID = cpp.USER_ID\r\n            and cp.PROFIL = cpp.PROFIL\r\n        ) pointage2\r\n        where UO_Prevision <> UO_Reel or UO_Prevision is NULL or UO_Reel is NULL\r\n    )\r\n) diff_pointage, cegid_project, cegid_user\r\nWHERE \r\n    cegid_project.CEGID = diff_pointage.PROJECT_ID and cegid_user.ID = diff_pointage.USER_ID\r\n    and year(DATE) = 2017\r\nORDER BY YEAR, PROJECT, DATE\r\n');



  
UPDATE `version` SET `DATE` = '2017-12-26 00:00:00', `value` = '0.23.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.22_vers_0.23', '101', '2017-12-16 00:00:00', 'udate user table', '0.23.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.25.00_vers_0.1.26.0', '200', '2017-12-16 00:00:00', 'modification user', '0.1.26.0');
UPDATE `version` SET `DATE` = '2017-12-26 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.26.0' WHERE `version`.`id` = 'php';

COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
