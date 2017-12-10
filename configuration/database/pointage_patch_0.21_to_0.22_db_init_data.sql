-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mer. 18 nov. 2017 à 16:33
-- Version du serveur :  10.1.25-MariaDB
-- Version de PHP :  7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS=0;



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `test`
--

-- --------------------------------------------------------


-- TRUNCATE TABLE `cegid_status_cegid`;
--
-- Contenu de la table `cegid_status_cegid`
--

INSERT IGNORE INTO `cegid_status_cegid` (`ID`, `NAME`, `ORDRE`) VALUES
('A faire', 'A faire', 1),
('Annule', 'Annule', 5),
('Clos', 'Clos', 4),
('Cree', 'Cree', 3),
('Demande', 'Demande', 2),
('Neant', 'Neant', 0);


--
-- Vider la table avant d'insérer `requetes`
--

TRUNCATE TABLE `requetes`;
--
-- Contenu de la table `requetes`
--

INSERT INTO `requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`) VALUES
('Cout projets TMA', 'Cout projets TMA 2017', 'Cout projets TMA 2017', 'select project_id, NAME, sum(uo),  sum(total) Cout from \r\n(select p.project_id, cp.NAME,  p.profil, sum(p.uo) uo, c.cout, (sum(p.uo)*cout) as total\r\nfrom cegid_pointage p, cegid_project_cout c, cegid_project cp\r\nwhere \r\n cp.CEGID > "P17900"\r\n and cp.CEGID =p.PROJECT_ID\r\n and year(p.date)=2017 and year(c.date)=2017 \r\n and p.PROJECT_ID = c.PROJECT_ID\r\n and p.PROFIL = c.PROFIL_ID\r\n group by p.project_id, p.profil\r\n order by p.project_id\r\n ) as CA\r\n  group by project_id, NAME\r\n  order by project_id\r\n'),
('Cout TMA', 'Cout TMA 2017', 'Cout TMA', 'select sum(CA) Cout_TMA from \r\n(select project_id, NAME, sum(uo),  sum(total) CA from \r\n(select p.project_id, cp.NAME,  p.profil, sum(p.uo) uo, c.cout, (sum(p.uo)*cout) as total\r\nfrom cegid_pointage p, cegid_project_cout c, cegid_project cp\r\nwhere \r\n cp.CEGID > "P17900"\r\n and cp.CEGID =p.PROJECT_ID\r\n and year(p.date)=2017 and year(c.date)=2017 \r\n and p.PROJECT_ID = c.PROJECT_ID\r\n and p.PROFIL = c.PROFIL_ID\r\n group by p.project_id, p.profil\r\n order by p.project_id\r\n ) as CA\r\n  group by project_id, NAME\r\n  order by project_id\r\n) as CA'),
('Cout_all', 'Cout global 2017', 'Cout global', 'select sum(COUT) from \r\n(select project_id, NAME, sum(uo),  sum(total) COUT from \r\n(select p.project_id, cp.NAME,  p.profil, sum(p.uo) uo, c.cout, (sum(p.uo)*cout) as total\r\nfrom cegid_pointage p, cegid_project_cout c, cegid_project cp\r\nwhere \r\n cp.CEGID =p.PROJECT_ID\r\n and year(p.date)=2017 and year(c.date)=2017 \r\n and p.PROJECT_ID = c.PROJECT_ID\r\n and p.PROFIL = c.PROFIL_ID\r\n group by p.project_id, p.profil\r\n order by p.project_id\r\n ) as CA\r\n  group by project_id, NAME\r\n  order by project_id\r\n) as CA'),
('Cout_profil', 'Cout par profil', 'Cout par profil', 'select p.project_id, cp.NAME,  p.profil, sum(p.uo) , c.cout, (sum(p.uo)*cout) as total\r\nfrom cegid_pointage p, cegid_project_cout c, cegid_project cp\r\nwhere \r\n cp.CEGID =p.PROJECT_ID\r\n and year(p.date)=2017 and year(c.date)=2017 \r\n and p.PROJECT_ID = c.PROJECT_ID\r\n and p.PROFIL = c.PROFIL_ID\r\n group by p.project_id, p.profil\r\n order by p.project_id'),
('Cout_project', 'Cout par projet', 'Cout par projet', 'select project_id, NAME, sum(uo),  sum(total) from \r\n(select p.project_id, cp.NAME,  p.profil, sum(p.uo) uo, c.cout, (sum(p.uo)*cout) as total\r\nfrom cegid_pointage p, cegid_project_cout c, cegid_project cp\r\nwhere \r\n cp.CEGID =p.PROJECT_ID\r\n and year(p.date)=2017 and year(c.date)=2017 \r\n and p.PROJECT_ID = c.PROJECT_ID\r\n and p.PROFIL = c.PROFIL_ID\r\n group by p.project_id, p.profil\r\n order by p.project_id\r\n ) as CA\r\n  group by project_id, NAME\r\n  order by project_id'),
('liste_des _requetes', 'requetes', 'la listes des requetes', 'select * from requetes'),
('Previsionnel CA_projet', 'Previsionnel CA_projet', 'Previsionnel CA par projet', 'select CA_PROFIL.project_id, CA_PROFIL.NAME, status, \r\n	sum(uo_possible) as uo_possible, sum(CA_possible) as CA_possible, \r\n	sum(uo_consomme) as uo_consomme, sum(consomme_total) as consomme_total, \r\n	least(  sum(CA_PROFIL.CA_possible) , sum(CA_PROFIL.consomme_total) ) as CA_realise from\r\n(\r\n	select p.project_id, cp.NAME,  p.profil, c.cout, c.uo as uo_possible, (c.uo * c.cout ) as CA_possible, sum(p.uo) uo_consomme,  (sum(p.uo)*cout) as consomme_total, least( (sum(p.uo)*cout) , c.uo * cout ) as CA_realise\r\n	from ( \r\n			(\r\n				select PROJECT_ID, DATE, PROFIL_ID as PROFIL, (select 0) as USER_ID,  (select 0) as UO\r\n				from  cegid_project_cout c\r\n				where not exists (select * from cegid_pointage_previsionnel where c.PROFIL_ID = cegid_pointage_previsionnel.PROFIL and c.project_id = cegid_pointage_previsionnel.project_id)\r\n			)\r\n			union\r\n			(\r\n				select PROJECT_ID, DATE, PROFIL, USER_ID,  UO from  cegid_pointage_previsionnel  \r\n			)\r\n	) p, cegid_project_cout c, cegid_project cp\r\n	where \r\n	 cp.CEGID =p.PROJECT_ID\r\n	 and year(p.date)=2017 and year(c.date)=2017 \r\n	 and p.PROJECT_ID = c.PROJECT_ID\r\n	 and p.PROFIL = c.PROFIL_ID\r\n	 group by p.project_id, p.profil\r\n	 order by p.project_id\r\n) CA_PROFIL , cegid_project\r\nwhere \r\n   CA_PROFIL.project_id = cegid_project.cegid \r\ngroup by CA_PROFIL.project_id\r\n\r\n\r\n'),
('Previsionnel CA_projet_clos', 'Previsionnel CA_projet_clos', 'Previsionnel CA par projet . prise en compte projet clos', 'select CA_PROFIL.project_id, CA_PROFIL.NAME, status, \r\n	sum(uo_possible) as uo_possible, sum(CA_possible) as CA_possible, \r\n	sum(uo_consomme) as uo_consomme, sum(consomme_total) as consomme_total, \r\n	if (status="Clos",sum(CA_PROFIL.CA_possible), least(  sum(CA_PROFIL.CA_possible) , sum(CA_PROFIL.consomme_total) )) as CA_realise from\r\n(\r\n	select p.project_id, cp.NAME,  p.profil, c.cout, c.uo as uo_possible, (c.uo * c.cout ) as CA_possible, sum(p.uo) uo_consomme,  (sum(p.uo)*cout) as consomme_total, least( (sum(p.uo)*cout) , c.uo * cout ) as CA_realise\r\n	from ( \r\n			(\r\n				select PROJECT_ID, DATE, PROFIL_ID as PROFIL, (select 0) as USER_ID,  (select 0) as UO\r\n				from  cegid_project_cout c\r\n				where not exists (select * from cegid_pointage_previsionnel where c.PROFIL_ID = cegid_pointage_previsionnel.PROFIL and c.project_id = cegid_pointage_previsionnel.project_id)\r\n			)\r\n			union\r\n			(\r\n				select PROJECT_ID, DATE, PROFIL, USER_ID,  UO from  cegid_pointage_previsionnel  \r\n			)\r\n	) p, cegid_project_cout c, cegid_project cp\r\n	where \r\n	 cp.CEGID =p.PROJECT_ID\r\n	 and year(p.date)=2017 and year(c.date)=2017 \r\n	 and p.PROJECT_ID = c.PROJECT_ID\r\n	 and p.PROFIL = c.PROFIL_ID\r\n	 group by p.project_id, p.profil\r\n	 order by p.project_id\r\n) CA_PROFIL , cegid_project\r\nwhere \r\n   CA_PROFIL.project_id = cegid_project.cegid \r\ngroup by CA_PROFIL.project_id\r\n\r\n\r\n'),
('Previsionnel CA__profil', 'Previsionnel CA__profil', 'Previsionnel CA__profil', '	select p.project_id, cp.NAME,  p.profil, c.cout, c.uo as uo_possible, (c.uo * c.cout ) as CA_possible, sum(p.uo) uo_consomme,  (sum(p.uo)*cout) as consomme_total, least( (sum(p.uo)*cout) , c.uo * cout ) as CA_realise\r\n	from ( \r\n			(\r\n				select PROJECT_ID, DATE, PROFIL_ID as PROFIL, (select 0) as USER_ID,  (select 0) as UO\r\n				from  cegid_project_cout c\r\n				where not exists (select * from cegid_pointage_previsionnel where c.PROFIL_ID = cegid_pointage_previsionnel.PROFIL and c.project_id = cegid_pointage_previsionnel.project_id)\r\n			)\r\n			union\r\n			(\r\n				select PROJECT_ID, DATE, PROFIL, USER_ID,  UO from  cegid_pointage_previsionnel  \r\n			)\r\n	) p, cegid_project_cout c, cegid_project cp\r\n	where \r\n	 cp.CEGID =p.PROJECT_ID\r\n	 and year(p.date)=2017 and year(c.date)=2017 \r\n	 and p.PROJECT_ID = c.PROJECT_ID\r\n	 and p.PROFIL = c.PROFIL_ID\r\n	 group by p.project_id, p.profil\r\n	 order by p.project_id\r\n'),
('Previsionnel CA__project_test', 'Previsionnel CA__project_test', 'Previsionnel CA__project_test', 'select p.project_id, cp.NAME,  p.profil, c.cout, c.uo as uo_possible, (c.uo * c.cout ) as CA_possible, sum(p.uo) uo_consomme,  (sum(p.uo)*cout) as consomme_total, least( (sum(p.uo)*cout) , c.uo * cout ) as CA\r\nfrom cegid_pointage_previsionnel p, cegid_project_cout c, cegid_project cp\r\nwhere \r\n cp.CEGID =p.PROJECT_ID\r\n and year(p.date)=2017 and year(c.date)=2017 \r\n and p.PROJECT_ID = c.PROJECT_ID\r\n and p.PROFIL = c.PROFIL_ID\r\n group by p.project_id, p.profil\r\n order by p.project_id'),
('Previsionnel Cout projets TMA', 'Previsionnel Cout projets TMA', 'Previsionnel Cout projets TMA', 'select project_id, NAME, sum(uo),  sum(total) CA_PREVISION from \r\n(select p.project_id, cp.NAME,  p.profil, sum(p.uo) uo, c.cout, (sum(p.uo)*cout) as total\r\nfrom cegid_pointage_previsionnel p, cegid_project_cout c, cegid_project cp\r\nwhere \r\n cp.CEGID > "P17900"\r\n and cp.CEGID =p.PROJECT_ID\r\n and year(p.date)=2017 and year(c.date)=2017 \r\n and p.PROJECT_ID = c.PROJECT_ID\r\n and p.PROFIL = c.PROFIL_ID\r\n group by p.project_id, p.profil\r\n order by p.project_id\r\n ) as CA\r\n  group by project_id, NAME\r\n  order by project_id\r\n'),
('Previsionnel Cout TMA', 'Previsionnel Cout TMA', 'Previsionnel Cout TMA', 'select sum(CA) CA_TMA_PREVISION from \r\n(select project_id, NAME, sum(uo),  sum(total) CA from \r\n(select p.project_id, cp.NAME,  p.profil, sum(p.uo) uo, c.cout, (sum(p.uo)*cout) as total\r\nfrom cegid_pointage_previsionnel p, cegid_project_cout c, cegid_project cp\r\nwhere \r\n cp.CEGID > "P17900"\r\n and cp.CEGID =p.PROJECT_ID\r\n and year(p.date)=2017 and year(c.date)=2017 \r\n and p.PROJECT_ID = c.PROJECT_ID\r\n and p.PROFIL = c.PROFIL_ID\r\n group by p.project_id, p.profil\r\n order by p.project_id\r\n ) as CA\r\n  group by project_id, NAME\r\n  order by project_id\r\n) as CA'),
('Previsionnel Cout_project', 'Previsionnel Cout par projet', 'Previsionnel Cout par projet', 'select project_id, NAME, sum(uo),  sum(total) CA_prevision from \r\n(select p.project_id, cp.NAME,  p.profil, sum(p.uo) uo, c.cout, (sum(p.uo)*cout) as total\r\nfrom cegid_pointage_previsionnel p, cegid_project_cout c, cegid_project cp\r\nwhere \r\n cp.CEGID =p.PROJECT_ID\r\n and year(p.date)=2017 and year(c.date)=2017 \r\n and p.PROJECT_ID = c.PROJECT_ID\r\n and p.PROFIL = c.PROFIL_ID\r\n group by p.project_id, p.profil\r\n order by p.project_id\r\n ) as CA\r\n  group by project_id, NAME\r\n  order by project_id'),
('Previsionnel_Cout_all', 'Previsionnel Cout global', 'Previsionnel Cout global', 'select sum(CA) CA_PREVISIONNEL from \r\n(select project_id, NAME, sum(uo),  sum(total) CA from \r\n(select p.project_id, cp.NAME,  p.profil, sum(p.uo) uo, c.cout, (sum(p.uo)*cout) as total\r\nfrom cegid_pointage_previsionnel p, cegid_project_cout c, cegid_project cp\r\nwhere \r\n cp.CEGID =p.PROJECT_ID\r\n and year(p.date)=2017 and year(c.date)=2017 \r\n and p.PROJECT_ID = c.PROJECT_ID\r\n and p.PROFIL = c.PROFIL_ID\r\n group by p.project_id, p.profil\r\n order by p.project_id\r\n ) as CA\r\n  group by project_id, NAME\r\n  order by project_id\r\n) as CA'),
('select_all_access_counter', 'Liste table access counter', 'Liste de la table access_counter', 'select * from access_counter'),
('select_all_access_history', 'Liste table access history', 'Liste de la table access_history', 'select * from access_history'),
('stat_access', 'statistiques accÃ¨s counter', 'statistiques sur les accÃ¨s (counter)', 'select count(*) access_counter from `access_counter` '),
('stat_access bots', 'statistiques accÃ¨s bots', 'statistiques sur les accÃ¨s des bots', 'select count(*) access_counter from `access_counter` where `user_agent` like "%bot%"'),
('stat_access_history', 'statistiques accÃ¨s history', 'statistiques sur les accÃ¨s (history)', 'select count(*) access_history from `access_history` '),
('suppression_bot_ac', 'suppression des bots (access counter)', 'suppression des bots sur access_counter', 'delete from `access_counter` where `user_agent` like "%bot%"\r\n'),
('suppression_bot_ah', 'suppression des bots (access history)', 'suppression des bots (access history)', 'delete from `access_history` where `user_agent` like "%bot%"\r\n');




UPDATE `version` SET `DATE` = '2017-11-18 00:00:00', `value` = '0.22.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.21_vers_0.22', '101', '2017-11-18 00:00:00', 'add data request', '0.22.0');



insert INTO `cegid_status_commande` (`ID`, `NAME`, `ORDRE`) VALUES
('Recu Erreur', 'Reçu en Erreur', 4);

UPDATE `cegid_status_commande` SET `ORDRE` = 5 WHERE `cegid_status_commande`.`ID` = 'Annule';


UPDATE `version` SET `DATE` = '2017-12-09 00:00:00', `value` = '0.22.1' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.21_vers_0.22.1', '101', '2017-12-09 00:00:00', 'add data status request', '0.22.1');


SET FOREIGN_KEY_CHECKS=1;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
