-- Preparation pointage a partir de previsionnel pour une date
-- positionne à 0 les UO par User/Project
-- => preparation des users (on ne sera pas obligé de creer les lignes user par projet dabs le pointage
-- @ID_DATE  -- date ou inserer des UO à 0 s'il existe dans previsonnel


SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;


set @ID_DATE="2018-01-01";  -- date yyyy-mm-dd

-- creation nouveau projet par copie

INSERT INTO `test`.`cegid_pointage` (`PROJECT_ID`, `DATE`, `USER_ID`, `PROFIL`, `UO`)
SELECT `PROJECT_ID`, `DATE`, `USER_ID`, `PROFIL`, 0 FROM `cegid_pointage_previsionnel`
  WHERE `DATE` = @ID_DATE 
 	AND concat(PROJECT_ID, month(DATE), USER_ID, PROFIL) not in (       
             select concat(PROJECT_ID, month(DATE), USER_ID, PROFIL) from cegid_pointage WHERE year(`DATE`)=year(@ID_DATE) and month(`DATE`)=month(@ID_DATE)
        );
        


SET FOREIGN_KEY_CHECKS=1;
ROLLBACK;
-- COMMIT;
