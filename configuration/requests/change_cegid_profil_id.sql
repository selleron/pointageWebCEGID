-- changer l'ID d'un profil
-- => besoin de maj de différentes tables
-- avant d'executer positionner :
-- @ID_OLD  -- ancien nom
-- @ID_NEW  -- nouveau nom


SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;


set @ID_OLD="FERIER";
set @ID_NEW="FERIE";

-- update
INSERT INTO `cegid_profil` (`ID`, `NAME`, `VISIBLE`) 
SELECT @ID_NEW, concat(`NAME`,"_"),  `VISIBLE` FROM `cegid_profil`
WHERE ID=@ID_OLD;


-- deplacement des références
UPDATE cegid_pointage                      SET PROFIL=@ID_NEW WHERE PROFIL=@ID_OLD;
UPDATE cegid_pointage_previsionnel_history SET PROFIL=@ID_NEW WHERE PROFIL=@ID_OLD;
UPDATE cegid_pointage_previsionnel         SET PROFIL=@ID_NEW WHERE PROFIL=@ID_OLD;
UPDATE cegid_pointage_voulu			       SET PROFIL=@ID_NEW WHERE PROFIL=@ID_OLD;
UPDATE cegid_pointage_import               SET PROFIL=@ID_NEW WHERE PROFIL=@ID_OLD;

set @ID_OLD_VERSION=(select value from version where id='data');
set @PATCH_DESCRIPTION=(select concat('update profil ',@ID_OLD,' to ', @ID_NEW));

UPDATE `version` SET `DATE` = now(), `value` = ROUND((value+0.01),2), `description` = @PATCH_DESCRIPTION WHERE `version`.`id` = 'data';

set @ID_NEW_VERSION=(select value from version where id='data');
set @PATCH_NAME=(    concat('patch_data_' , @ID_OLD_VERSION , '_' , @ID_NEW_VERSION ));

INSERT INTO `version` (`id`, `order`, `DATE`, `description`, `value`) VALUES (
    @PATCH_NAME, 
    '300', 
    now(), 
    @PATCH_DESCRIPTION , 
    @ID_NEW_VERSION);





-- suppression de l'ancien projet
-- dangereux à cause des delete cascade
DELETE FROM cegid_profil WHERE ID=@ID_OLD;

SET FOREIGN_KEY_CHECKS=1;
ROLLBACK;
-- COMMIT;

