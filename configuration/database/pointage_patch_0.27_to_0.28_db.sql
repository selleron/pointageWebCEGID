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


REPLACE INTO `cegid_requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`) VALUES
('Actuel CA_projet', 'Actuel CA_projet', 'Actuel CA par projet', 'select CA_PROFIL.project_id, CA_PROFIL.NAME, status, \r\n	sum(uo_possible) as uo_possible, sum(CA_possible) as CA_possible, \r\n	sum(uo_consomme) as uo_consomme, sum(consomme_total) as consomme_total, \r\n	least(  sum(CA_PROFIL.CA_possible) , sum(CA_PROFIL.consomme_total) ) as CA_realise from\r\n(\r\n	select p.project_id, cp.NAME,  p.profil, c.cout, c.uo as uo_possible, (c.uo * c.cout ) as CA_possible, sum(p.uo) uo_consomme,  (sum(p.uo)*cout) as consomme_total, least( (sum(p.uo)*cout) , c.uo * cout ) as CA_realise\r\n	from ( \r\n			(\r\n				select PROJECT_ID, DATE, PROFIL_ID as PROFIL, (select 0) as USER_ID,  (select 0) as UO\r\n				from  cegid_project_cout c\r\n			)\r\n			union\r\n			(\r\n				select PROJECT_ID, DATE, PROFIL, USER_ID,  UO from  cegid_pointage  \r\n			)\r\n	) p, cegid_project_cout c, cegid_project cp\r\n	where \r\n	 cp.CEGID =p.PROJECT_ID\r\n	 and year(p.date)=${year} and year(c.date)=${year} \r\n	 and p.PROJECT_ID = c.PROJECT_ID\r\n	 and p.PROFIL = c.PROFIL_ID\r\n	 group by p.project_id, p.profil\r\n	 order by p.project_id\r\n) CA_PROFIL , cegid_project\r\nwhere \r\n   CA_PROFIL.project_id = cegid_project.cegid \r\ngroup by CA_PROFIL.project_id\r\n\r\n\r\n'),
('Actuel CA_projet_clos', 'Actuel CA_projet_clos', 'Actuel CA par projet . prise en compte projet clos', 'select CA_PROFIL.project_id, CA_PROFIL.NAME, status, \r\n	sum(uo_possible) as uo_possible, sum(CA_possible) as CA_possible, \r\n	sum(uo_consomme) as uo_consomme, sum(consomme_total) as consomme_total, \r\n	if (status="Clos",sum(CA_PROFIL.CA_possible), least(  sum(CA_PROFIL.CA_possible) , sum(CA_PROFIL.consomme_total) )) as CA_realise from\r\n(\r\n	select p.project_id, cp.NAME,  p.profil, c.cout, c.uo as uo_possible, (c.uo * c.cout ) as CA_possible, sum(p.uo) uo_consomme,  (sum(p.uo)*cout) as consomme_total, least( (sum(p.uo)*cout) , c.uo * cout ) as CA_realise\r\n	from ( \r\n			(\r\n				select PROJECT_ID, DATE, PROFIL_ID as PROFIL, (select 0) as USER_ID,  (select 0) as UO\r\n				from  cegid_project_cout c\r\n			)\r\n			union\r\n			(\r\n				select PROJECT_ID, DATE, PROFIL, USER_ID,  UO from  cegid_pointage  \r\n			)\r\n	) p, cegid_project_cout c, cegid_project cp\r\n	where \r\n	 cp.CEGID =p.PROJECT_ID\r\n	 and year(p.date)=${year} and year(c.date)=${year} \r\n	 and p.PROJECT_ID = c.PROJECT_ID\r\n	 and p.PROFIL = c.PROFIL_ID\r\n	 group by p.project_id, p.profil\r\n	 order by p.project_id\r\n) CA_PROFIL , cegid_project\r\nwhere \r\n   CA_PROFIL.project_id = cegid_project.cegid \r\ngroup by CA_PROFIL.project_id\r\n\r\n\r\n'),
('Previsionnel CA_projet', 'Previsionnel CA_projet', 'Previsionnel CA par projet', 'select CA_PROFIL.project_id, CA_PROFIL.NAME, status, \r\n	sum(uo_possible) as uo_possible, sum(CA_possible) as CA_possible, \r\n	sum(uo_consomme) as uo_consomme, sum(consomme_total) as consomme_total, \r\n	least(  sum(CA_PROFIL.CA_possible) , sum(CA_PROFIL.consomme_total) ) as CA_realise from\r\n(\r\n	select p.project_id, cp.NAME,  p.profil, c.cout, c.uo as uo_possible, (c.uo * c.cout ) as CA_possible, sum(p.uo) uo_consomme,  (sum(p.uo)*cout) as consomme_total, least( (sum(p.uo)*cout) , c.uo * cout ) as CA_realise\r\n	from ( \r\n			(\r\n				select PROJECT_ID, DATE, PROFIL_ID as PROFIL, (select 0) as USER_ID,  (select 0) as UO\r\n				from  cegid_project_cout c\r\n			)\r\n			union\r\n			(\r\n				select PROJECT_ID, DATE, PROFIL, USER_ID,  UO from  cegid_pointage_previsionnel  \r\n			)\r\n	) p, cegid_project_cout c, cegid_project cp\r\n	where \r\n	 cp.CEGID =p.PROJECT_ID\r\n	 and year(p.date)=${year} and year(c.date)=${year} \r\n	 and p.PROJECT_ID = c.PROJECT_ID\r\n	 and p.PROFIL = c.PROFIL_ID\r\n	 group by p.project_id, p.profil\r\n	 order by p.project_id\r\n) CA_PROFIL , cegid_project\r\nwhere \r\n   CA_PROFIL.project_id = cegid_project.cegid \r\ngroup by CA_PROFIL.project_id\r\n\r\n\r\n'),
('Previsionnel CA_projet_clos', 'Previsionnel CA_projet_clos', 'Previsionnel CA par projet . prise en compte projet clos', 'select CA_PROFIL.project_id, CA_PROFIL.NAME, status, \r\n	sum(uo_possible) as uo_possible, sum(CA_possible) as CA_possible, \r\n	sum(uo_consomme) as uo_consomme, sum(consomme_total) as consomme_total, \r\n	if (status="Clos",sum(CA_PROFIL.CA_possible), least(  sum(CA_PROFIL.CA_possible) , sum(CA_PROFIL.consomme_total) )) as CA_realise from\r\n(\r\n	select p.project_id, cp.NAME,  p.profil, c.cout, c.uo as uo_possible, (c.uo * c.cout ) as CA_possible, sum(p.uo) uo_consomme,  (sum(p.uo)*cout) as consomme_total, least( (sum(p.uo)*cout) , c.uo * cout ) as CA_realise\r\n	from ( \r\n			(\r\n				select PROJECT_ID, DATE, PROFIL_ID as PROFIL, (select 0) as USER_ID,  (select 0) as UO\r\n				from  cegid_project_cout c\r\n			)\r\n			union\r\n			(\r\n				select PROJECT_ID, DATE, PROFIL, USER_ID,  UO from  cegid_pointage_previsionnel  \r\n			)\r\n	) p, cegid_project_cout c, cegid_project cp\r\n	where \r\n	 cp.CEGID =p.PROJECT_ID\r\n	 and year(p.date)=${year} and year(c.date)=${year} \r\n	 and p.PROJECT_ID = c.PROJECT_ID\r\n	 and p.PROFIL = c.PROFIL_ID\r\n	 group by p.project_id, p.profil\r\n	 order by p.project_id\r\n) CA_PROFIL , cegid_project\r\nwhere \r\n   CA_PROFIL.project_id = cegid_project.cegid \r\ngroup by CA_PROFIL.project_id\r\n\r\n\r\n');


UPDATE `version` SET `DATE` = '2018-01-08 00:00:00', `value` = '0.28.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.27_vers_0.28', '101', '2018-01-08 00:00:00', 'correction requestes CA', '0.28.0');



COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
