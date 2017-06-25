-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Dim 05 Février 2017 à 13:06
-- Version du serveur :  5.5.53-0+deb8u1
-- Version de PHP :  5.6.29-0+deb8u1

SET FOREIGN_KEY_CHECKS=0;
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

-- --------------------------------------------------------

--
-- Structure de la table `access_counter`
--

DROP TABLE IF EXISTS `access_counter`;
CREATE TABLE IF NOT EXISTS `access_counter` (
  `IP` varchar(255) NOT NULL,
  `COUNTER_ACCESS` int(11) NOT NULL,
  `DATE_LAST_ACCESS` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ID_MEMBER` varchar(255) NOT NULL,
  `USER_AGENT` varchar(255) NOT NULL,
  `URL` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `access_history`
--

DROP TABLE IF EXISTS `access_history`;
CREATE TABLE IF NOT EXISTS `access_history` (
  `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IP` varchar(255) NOT NULL,
  `ID_MEMBER` varchar(255) NOT NULL,
  `USER_AGENT` varchar(255) NOT NULL,
  `URL` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `cegid_pointage`
--

DROP TABLE IF EXISTS `cegid_pointage`;
CREATE TABLE IF NOT EXISTS `cegid_pointage` (
  `PROJECT_ID` varchar(10) NOT NULL,
  `DATE` date NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `PROFIL` varchar(10) NOT NULL,
  `UO` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `cegid_profil`
--

DROP TABLE IF EXISTS `cegid_profil`;
CREATE TABLE IF NOT EXISTS `cegid_profil` (
  `ID` varchar(10) NOT NULL,
  `NAME` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `cegid_project`
--

DROP TABLE IF EXISTS `cegid_project`;
CREATE TABLE IF NOT EXISTS `cegid_project` (
  `CEGID` varchar(10) NOT NULL,
  `NAME` varchar(30) NOT NULL,
  `PRIX_VENTE` double NOT NULL,
  `DEBUT` date NOT NULL,
  `FIN` date NOT NULL,
  `FIN_GARANTIE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `cegid_project_cout`
--

DROP TABLE IF EXISTS `cegid_project_cout`;
CREATE TABLE IF NOT EXISTS `cegid_project_cout` (
  `PROJECT_ID` varchar(10) NOT NULL,
  `PROFIL_ID` varchar(10) NOT NULL,
  `UO` float NOT NULL,
  `COUT` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `cegid_user`
--

DROP TABLE IF EXISTS `cegid_user`;
CREATE TABLE IF NOT EXISTS `cegid_user` (
`ID` int(10) NOT NULL,
  `NAME` varchar(30) NOT NULL,
  `NOM` varchar(30) NOT NULL,
  `PRENOM` varchar(30) NOT NULL,
  `PROFIL` varchar(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16065 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `login_history`
--

DROP TABLE IF EXISTS `login_history`;
CREATE TABLE IF NOT EXISTS `login_history` (
  `DATE_HISTORY` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_member` int(11) NOT NULL,
  `IP` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='historique des connections';

-- --------------------------------------------------------

--
-- Structure de la table `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE IF NOT EXISTS `member` (
`id_member` int(10) NOT NULL,
  `id` varchar(20) NOT NULL DEFAULT '----',
  `pseudo` varchar(40) NOT NULL,
  `passe` varchar(100) NOT NULL,
  `url` varchar(100) DEFAULT NULL,
  `destination` varchar(60) NOT NULL DEFAULT '/default.php',
  `pays` varchar(20) DEFAULT NULL,
  `remarques` varchar(200) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `member_group`
--

DROP TABLE IF EXISTS `member_group`;
CREATE TABLE IF NOT EXISTS `member_group` (
  `ID` int(11) NOT NULL,
  `NAME` char(30) NOT NULL,
  `DESCRIPTION` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='groupes utilisateurs';

-- --------------------------------------------------------

--
-- Structure de la table `relation_member_member_group`
--

DROP TABLE IF EXISTS `relation_member_member_group`;
CREATE TABLE IF NOT EXISTS `relation_member_member_group` (
  `ID_MEMBER` int(11) NOT NULL COMMENT 'id du member',
  `ID_MEMBER_GROUP` int(11) NOT NULL COMMENT 'id du group'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='relation member et member_group';

-- --------------------------------------------------------

--
-- Structure de la table `requetes`
--

DROP TABLE IF EXISTS `requetes`;
CREATE TABLE IF NOT EXISTS `requetes` (
  `ID` varchar(100) NOT NULL COMMENT 'identifiant',
  `NAME` varchar(100) NOT NULL,
  `DESCRIPTION` text,
  `SQL_REQUEST` text NOT NULL COMMENT 'la requête stockée'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table contenant des requetes';

-- --------------------------------------------------------

--
-- Structure de la table `table_counter`
--

DROP TABLE IF EXISTS `table_counter`;
CREATE TABLE IF NOT EXISTS `table_counter` (
  `name` varchar(100) NOT NULL,
  `value` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `version`
--

DROP TABLE IF EXISTS `version`;
CREATE TABLE IF NOT EXISTS `version` (
  `id` varchar(100) NOT NULL COMMENT 'identifiant',
  `order` int(11) DEFAULT NULL COMMENT 'permet d''ordonnancer les informations de version',
  `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'date',
  `description` varchar(255) DEFAULT NULL COMMENT 'description',
  `value` varchar(100) NOT NULL COMMENT 'valeur souvent XX.YY.ZZ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='table des versions';

--
-- Index pour les tables exportées
--

--
-- Index pour la table `access_counter`
--
ALTER TABLE `access_counter`
 ADD PRIMARY KEY (`IP`);

--
-- Index pour la table `access_history`
--
ALTER TABLE `access_history`
 ADD KEY `DATE` (`DATE`);

--
-- Index pour la table `cegid_pointage`
--
ALTER TABLE `cegid_pointage`
 ADD PRIMARY KEY (`PROJECT_ID`,`DATE`,`USER_ID`,`PROFIL`), ADD KEY `USER_ID` (`USER_ID`), ADD KEY `PROFIL` (`PROFIL`), ADD KEY `PROJECT_ID` (`PROJECT_ID`);

--
-- Index pour la table `cegid_profil`
--
ALTER TABLE `cegid_profil`
 ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `cegid_project`
--
ALTER TABLE `cegid_project`
 ADD PRIMARY KEY (`CEGID`), ADD UNIQUE KEY `NAME` (`NAME`), ADD KEY `CEGID` (`CEGID`);

--
-- Index pour la table `cegid_project_cout`
--
ALTER TABLE `cegid_project_cout`
 ADD KEY `cegid_project_cout_ibfk_1` (`PROJECT_ID`), ADD KEY `cegid_project_cout_ibfk_2` (`PROFIL_ID`);

--
-- Index pour la table `cegid_user`
--
ALTER TABLE `cegid_user`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `NAME` (`NAME`), ADD KEY `user_profil_profil_id` (`PROFIL`);

--
-- Index pour la table `login_history`
--
ALTER TABLE `login_history`
 ADD KEY `id_member` (`id_member`);

--
-- Index pour la table `member`
--
ALTER TABLE `member`
 ADD PRIMARY KEY (`id_member`), ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- Index pour la table `member_group`
--
ALTER TABLE `member_group`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `NAME` (`NAME`);

--
-- Index pour la table `relation_member_member_group`
--
ALTER TABLE `relation_member_member_group`
 ADD KEY `ID_MEMBER` (`ID_MEMBER`), ADD KEY `ID_MEMBER_GROUP` (`ID_MEMBER_GROUP`);

--
-- Index pour la table `requetes`
--
ALTER TABLE `requetes`
 ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `table_counter`
--
ALTER TABLE `table_counter`
 ADD PRIMARY KEY (`name`);

--
-- Index pour la table `version`
--
ALTER TABLE `version`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `cegid_user`
--
ALTER TABLE `cegid_user`
MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16065;
--
-- AUTO_INCREMENT pour la table `member`
--
ALTER TABLE `member`
MODIFY `id_member` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `cegid_pointage`
--
ALTER TABLE `cegid_pointage`
ADD CONSTRAINT `cegid_pointage_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `cegid_user` (`ID`),
ADD CONSTRAINT `cegid_pointage_ibfk_2` FOREIGN KEY (`PROFIL`) REFERENCES `cegid_profil` (`ID`),
ADD CONSTRAINT `cegid_pointage_ibfk_3` FOREIGN KEY (`PROJECT_ID`) REFERENCES `cegid_project` (`CEGID`);

--
-- Contraintes pour la table `cegid_project_cout`
--
ALTER TABLE `cegid_project_cout`
ADD CONSTRAINT `cegid_project_cout_ibfk_1` FOREIGN KEY (`PROJECT_ID`) REFERENCES `cegid_project` (`CEGID`),
ADD CONSTRAINT `cegid_project_cout_ibfk_2` FOREIGN KEY (`PROFIL_ID`) REFERENCES `cegid_profil` (`ID`);

--
-- Contraintes pour la table `cegid_user`
--
ALTER TABLE `cegid_user`
ADD CONSTRAINT `cegid_user_ibfk_1` FOREIGN KEY (`PROFIL`) REFERENCES `cegid_profil` (`ID`);

--
-- Contraintes pour la table `login_history`
--
ALTER TABLE `login_history`
ADD CONSTRAINT `login_history_ibfk_1` FOREIGN KEY (`id_member`) REFERENCES `member` (`id_member`);

--
-- Contraintes pour la table `relation_member_member_group`
--
ALTER TABLE `relation_member_member_group`
ADD CONSTRAINT `relation_member_member_group_ibfk_3` FOREIGN KEY (`ID_MEMBER`) REFERENCES `member` (`id_member`) ON DELETE CASCADE,
ADD CONSTRAINT `relation_member_member_group_ibfk_4` FOREIGN KEY (`ID_MEMBER_GROUP`) REFERENCES `member_group` (`ID`) ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
