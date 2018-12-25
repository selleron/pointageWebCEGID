-- Reset pointage
-- 

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

set @ID_DATE_DEBUT = "2018-01-01";  -- date yyyy-mm-dd
set @ID_DATE_FIN   = "2019-12-31";  -- date yyyy-mm-dd


DELETE FROM `cegid_pointage` WHERE 
    `PROJECT_ID`>="P19900"
AND `PROJECT_ID`<="P19999"
AND `DATE` >= @ID_DATE_DEBUT
AND `DATE` <= @ID_DATE_FIN;

-- DELETE FROM `cegid_pointage_previsionnel` WHERE 
--    `PROJECT_ID`>="P19900"
-- AND `PROJECT_ID`<="P19999"
-- AND `DATE` >= @ID_DATE_DEBUT
-- AND `DATE` <= @ID_DATE_FIN;





SET FOREIGN_KEY_CHECKS=1;
ROLLBACK;
-- COMMIT;
