# patch pointage data base CEGID
# begin version 0.9
# end version 0.10
#
# modification table cegid_project_cout
#


SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";



ALTER TABLE `cegid_project_cout` ADD `DATE` DATE NULL FIRST;
#ALTER TABLE cegid_project_cout DROP PRIMARY KEY ;
ALTER TABLE `cegid_project_cout` ADD `ID` INT NOT NULL FIRST, ADD PRIMARY KEY (`ID`) ;
ALTER TABLE `cegid_project_cout` CHANGE `ID` `ID` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `cegid_project_cout` ADD UNIQUE( `DATE`, `PROJECT_ID`, `PROFIL_ID`);

ALTER TABLE `cegid_project_cout` DROP FOREIGN KEY `cegid_project_cout_ibfk_1`; 
ALTER TABLE `cegid_project_cout` ADD CONSTRAINT `cegid_project_cout_ibfk_1` FOREIGN KEY (`PROJECT_ID`) REFERENCES `test`.`cegid_project`(`CEGID`) ON DELETE CASCADE ON UPDATE RESTRICT; 

ALTER TABLE `cegid_project_cout` DROP FOREIGN KEY `cegid_project_cout_ibfk_2`; 
ALTER TABLE `cegid_project_cout` ADD CONSTRAINT `cegid_project_cout_ibfk_2` FOREIGN KEY (`PROFIL_ID`) REFERENCES `test`.`cegid_profil`(`ID`) ON DELETE CASCADE ON UPDATE RESTRICT;



UPDATE `version` SET `DATE` = '2017-03-19 00:00:00', `value` = '0.10.0' WHERE `version`.`id` = 'database';
INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES ('patch_database_0.9_vers_0.10', '101', '2017-03-19 00:00:00', 'update cegid_project_cout', '0.10.0');

COMMIT;
