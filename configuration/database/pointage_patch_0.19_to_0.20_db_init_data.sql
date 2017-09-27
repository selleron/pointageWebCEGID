-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mer. 27 sep. 2017 à 16:33
-- Version du serveur :  10.1.25-MariaDB
-- Version de PHP :  7.1.7

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

-- --------------------------------------------------------

--
-- Structure de la table `requetes`
--


--
-- Déchargement des données de la table `requetes`
--

INSERT INTO `requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`) VALUES
('CA_profil', 'CA par profil', 'CA par profil', 'select p.project_id, cp.NAME,  p.profil, sum(p.uo) , c.cout, (sum(p.uo)*cout) as total\r\nfrom cegid_pointage p, cegid_project_cout c, cegid_project cp\r\nwhere \r\n cp.CEGID =p.PROJECT_ID\r\n and year(p.date)=2017 and year(c.date)=2017 \r\n and p.PROJECT_ID = c.PROJECT_ID\r\n and p.PROFIL = c.PROFIL_ID\r\n group by p.project_id, p.profil\r\n order by p.project_id'),
('CA_project', 'CA par projet', 'CA par projet', 'select project_id, NAME, sum(uo),  sum(total) from \r\n(select p.project_id, cp.NAME,  p.profil, sum(p.uo) uo, c.cout, (sum(p.uo)*cout) as total\r\nfrom cegid_pointage p, cegid_project_cout c, cegid_project cp\r\nwhere \r\n cp.CEGID =p.PROJECT_ID\r\n and year(p.date)=2017 and year(c.date)=2017 \r\n and p.PROJECT_ID = c.PROJECT_ID\r\n and p.PROFIL = c.PROFIL_ID\r\n group by p.project_id, p.profil\r\n order by p.project_id\r\n ) as CA\r\n  group by project_id, NAME\r\n  order by project_id');

UPDATE `version` SET `DATE` = '2017-09-27 00:00:00', `value` = '0.20.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.19_vers_0.20', '101', '2017-09-27 00:00:00', 'add data request', '0.20.0');


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
