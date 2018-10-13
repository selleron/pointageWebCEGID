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

--
-- Structure de la table `cegid_status_devis`
--

CREATE TABLE `cegid_file_code` (
  `ID` varchar(15) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `ORDRE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `cegid_status_devis`
--


INSERT INTO `cegid_file_code` (`ID`, `NAME`, `ORDRE`) VALUES
('UNDEF', 'indefini', 1),
('AR_CMD', 'AR Commande'     , 10),
('CDC', 'Cahier Des Charges' , 11),
('CHIFF', 'Chiffrage', 20),
('CMD', 'Commande', 30),
('PT', 'Proposition tecnique'             , 40),
('PF', 'Proposition financière'           , 41),
('PTF', 'Prposition tecnique & financière', 42),
('DRAFT', 'DRAFT'                         , 43),
('FACT', 'Facture', 50),
('FACT_CLIE', 'Facture client'                 , 51),
('FACT_FRAIS_CLIE', 'Facture frais client'     , 52),
('FACT_FOUR', 'Facture fournisseur'            , 53),
('FACT_FRAIS_FOUR', 'Facture frais fournisseur', 54),
('TIME_SHEET', 'Temps passé', 60);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `cegid_file_code`
--
ALTER TABLE `cegid_file_code`
  ADD PRIMARY KEY (`ID`);
  

  
ALTER TABLE `cegid_file` ADD `CODE` VARCHAR(15) NOT NULL DEFAULT 'UNDEF' AFTER `REFERENCE`; 
ALTER TABLE `cegid_file` ADD FOREIGN KEY (`CODE`) REFERENCES `cegid_file_code`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;  
  

--
-- table de frais de mission
--



CREATE TABLE `cegid_frais_mission` (
  `ID` varchar(15) NOT NULL,
  `DATE` date DEFAULT NULL,
  `PROJECT_ID` varchar(10) NOT NULL,
  `COUT` double NOT NULL,
  `COMMENTAIRE` varchar(150),
  `STATUS_ID` varchar(10) NOT NULL DEFAULT 'UNDEF'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `cegid_project_cout`
--
ALTER TABLE `cegid_frais_mission`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `cegid_frais_mission_ibfk_1` (`PROJECT_ID`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `cegid_frais_mission`
--
-- ALTER TABLE `cegid_frais_mission`
--    MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour la table `cegid_frais_mission`
--
ALTER TABLE `cegid_frais_mission`
  ADD CONSTRAINT `cegid_frais_mission_ibfk_1` FOREIGN KEY (`PROJECT_ID`) REFERENCES `cegid_project` (`CEGID`) ON DELETE CASCADE;

  
  --
-- update description des requetes CEGID 
--

UPDATE `cegid_requetes` 
  SET `DESCRIPTION` = 'UO Reportable : \r\nPermet de connaitre les U.O restant.\r\nNe prend pas en compte : <br>\r\n- les projets cloturés <br>\r\n- les projets ayant des UO definis l\'année suivante.' WHERE `cegid_requetes`.`ID` = 'UO_RESTANT_CLOTURE';

UPDATE `cegid_requetes` 
  SET `DESCRIPTION` = 'Vérifie que le prix de vente indiqué dans le projet correspond a la somme des couts d\'un projet (toutes années confondues).<br>\r\n\r\nN\'affiche que les projets ayant une différence => incoherence.' WHERE `cegid_requetes`.`ID` = 'CHECK_PRIX_VENTE';
  
UPDATE `cegid_requetes` 
  SET `NAME` = 'PRIX Vente et CA prévisionnel', 
      `DESCRIPTION` = 'Prix de vente et CA prévisionnel.<br>\r\nPermet de comparer pour l\'ensemble des projets, le prix de vente et le CA prévisionnel.', 
      `SQL_REQUEST` = 'SELECT cpc.PROJECT_ID, cp.NAME, cp.STATUS, sum(cpc.uo) UO_possible, cp.PRIX_VENTE, sum(cpc.uo*cpc.cout) as CA\r\nFROM cegid_project_cout cpc, cegid_project cp\r\nWHERE \r\n cp.CEGID = cpc.PROJECT_ID\r\nGROUP BY cpc.PROJECT_ID' WHERE `cegid_requetes`.`ID` = 'PRIX_VENTE';
--
-- Declaration Modification
--
  
UPDATE `version` SET `DATE` = '2018-10-13 00:00:00', `value` = '0.31.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.30_vers_0.31', '101', '2018-10-06 00:00:00', 'ajout fiche de frais', '0.31.0');

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_php_0.1.27.19 vers_0.1.28.00', '200', '2018-10-06 00:00:00', 'ajout fiche de frais dans les pages projets', '0.1.28.00');
UPDATE `version` SET `DATE` = '2018-10-06 00:00:00', `description` = 'version fichier php minimal', `value` = '0.1.28.00' WHERE `version`.`id` = 'php';


COMMIT;


