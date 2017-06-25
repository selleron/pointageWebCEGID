-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Dim 19 Mars 2017 à 08:54
-- Version du serveur :  5.5.54-0+deb8u1
-- Version de PHP :  5.6.29-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `test`
--

--
-- Contenu de la table `member`
--

INSERT INTO `member` (`id_member`, `id`, `pseudo`, `passe`, `url`, `destination`, `pays`, `remarques`, `email`) VALUES
(1, 'r4xeb4xyjoh2n4xqfo1e', 'admin', 'bU.we73eauE', 'http://localhost/default.php', '/default.php', 'france', 'administrateur', 'adminmon_site.com'),
(2, 'z167487ci6746zxrvugb', 'root', 'xC6N/EVpOi6', 'http://localhost/default.php', '/default.php', 'france', 'administrateur', 'root@mon_site.com'):

--
-- Contenu de la table `member_group`
--

INSERT INTO `member_group` (`ID`, `NAME`, `DESCRIPTION`) VALUES
(1, 'user', 'utilisateur de base'),
(2, 'super_user', 'droit de gestion'),
(3, 'admin', 'tous les droits');

--
-- Contenu de la table `relation_member_member_group`
--

INSERT INTO `relation_member_member_group` (`ID_MEMBER`, `ID_MEMBER_GROUP`) VALUES
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(2, 3);

--
-- Contenu de la table `requetes`
--

INSERT INTO `requetes` (`ID`, `NAME`, `DESCRIPTION`, `SQL_REQUEST`) VALUES
('liste_des _requetes', 'requetes', 'la listes des requetes', 'select * from requetes'),
('select_all_access_counter', 'Liste table access counter', 'Liste de la table access_counter', 'select * from access_counter'),
('select_all_access_history', 'Liste table access history', 'Liste de la table access_history', 'select * from access_history'),
('stat_access', 'statistiques accÃ¨s counter', 'statistiques sur les accÃ¨s (counter)', 'select count(*) access_counter from `access_counter` '),
('stat_access bots', 'statistiques accÃ¨s bots', 'statistiques sur les accÃ¨s des bots', 'select count(*) access_counter from `access_counter` where `user_agent` like "%bot%"'),
('stat_access_history', 'statistiques accÃ¨s history', 'statistiques sur les accÃ¨s (history)', 'select count(*) access_history from `access_history` '),
('suppression_bot_ac', 'suppression des bots (access counter)', 'suppression des bots sur access_counter', 'delete from `access_counter` where `user_agent` like "%bot%"\r\n'),
('suppression_bot_ah', 'suppression des bots (access history)', 'suppression des bots (access history)', 'delete from `access_history` where `user_agent` like "%bot%"\r\n');

--
-- Contenu de la table `version`
--

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES
('data', 1, '2017-02-26 23:00:00', 'backup depuis database pi', '2017-02-27'),
('database', 2, '2017-02-04 23:00:00', 'version base de donnees', '0.9.0'),
('php', 3, '2017-03-17 23:00:00', 'version fichier php', '0.1.11.5');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
