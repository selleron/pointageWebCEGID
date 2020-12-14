-- Preparation pointage previsionnel à partir d'un autre previsionnel 
-- positionne a  0 les UO par User/Project
-- => preparation des users (on ne sera pas obligé de creer les lignes user par projet dans le pointage previsionnel)
-- @ID_DATE  -- date ou inserer des UO a  0 s'il existe dans previsonnel


SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;


set @ID_DATE_REF="2020-12-01";  -- date de reference yyyy-mm-dd
set @ID_DATE_NEW="2021-01-01";  -- date d'insertion  (en generale le 01/01/xxxx) yyyy-mm-dd


set @ID_PROJECT_REF="P2X901";   -- id du projet de reference
set @ID_PROJECT_NEW="P2Y901";   -- id du projet a peupler

-- creation nouveau pointage de projet par copie depuis le provisionnel
-- ne cree le pointage que pour (annee-mois) donnÃ© et pour les tuples non deja existant (projet, user, profil)
-- place le nombre d'UO a 0

INSERT INTO `test`.`cegid_pointage_previsionnel` (`PROJECT_ID`, `DATE`, `USER_ID`, `PROFIL`, `UO`)
SELECT @ID_PROJECT_NEW, @ID_DATE_NEW, USER_ID, `PROFIL`, 0 FROM `cegid_pointage_previsionnel`
  WHERE `DATE` = @ID_DATE_REF 
  	AND `PROJECT_ID` = @ID_PROJECT_REF
 	AND concat(PROJECT_ID, month(DATE), USER_ID, PROFIL) not in (       
             select concat(PROJECT_ID, month(DATE), USER_ID, PROFIL) from cegid_pointage_previsionnel WHERE year(`DATE`)=year(@ID_DATE) and month(`DATE`)=month(@ID_DATE)
        );
        


SET FOREIGN_KEY_CHECKS=1;
ROLLBACK;
-- COMMIT;
